<?php

namespace Workdo\WhatsAppChatBotAndChat\Http\Controllers;

use App\Http\Controllers\TicketConversionController;
use App\Models\Conversion;
use App\Models\Ticket;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SendWhatsAppMessageController extends TicketConversionController
{
    private $accessToken;
    private $phoneNumberId;


    public function __construct()
    {
        $settings = getCompanyAllSettings();
        $this->accessToken = isset($settings['whatsapp_chatbot_access_token']) ? $settings['whatsapp_chatbot_access_token'] : '';
        $this->phoneNumberId = isset($settings['whatsapp_chatbot_phone_number_id']) ? $settings['whatsapp_chatbot_phone_number_id'] : '';
    }

    public function sendMessage(Request $request, $ticket, $user)
    {
        $whatsappNumber = $ticket->mobile_no;
        $message = strip_tags(html_entity_decode($request->reply_description));
        $url = "https://graph.facebook.com/v21.0/{$this->phoneNumberId}/messages";
        $attachments = [];
        $errorMessage = null;

        // Step 1: Upload images locally
        $localFilePaths = [];
        if ($request->hasfile(key: 'reply_attachments')) {
            $localFilePaths = $this->handleFileUpload($request, $ticket);
            if (isset($localFilePaths['status']) && $localFilePaths['status']  == 'error') {
                return response()->json([
                    'status' => 'error',
                    'message' => $localFilePaths['message'],
                ]);
            }
        }

        // Step 2: Upload images to Meta API and get media IDs
        $uploadedMedia = [];
        if (!empty($localFilePaths)) {
            $uploadedMedia = $this->uploadMediaToMeta($localFilePaths);
            if (isset($uploadedMedia['status']) && $uploadedMedia['status'] == 'error') {
                $errorMessage = $uploadedMedia['message'];
                $status = 'error';
            }
        }

        // Step 3: Prepare payloads
        $payloads = [];

        // First, send the text message (if exists)
        if (!empty($message)) {
            $payloads[] = [
                'messaging_product' => 'whatsapp',
                'to' => $ticket->mobile_no,
                'type' => 'text',
                'text' => ['body' => $message]
            ];
        }

        // Send images separately
        foreach ($uploadedMedia as $mediaId) {
            $payloads[] = [
                'messaging_product' => 'whatsapp',
                'to' => $ticket->mobile_no,
                'type' => 'image',
                'image' => ['id' => $mediaId]
            ];
        }

        // Step 4: Send messages to WhatsApp
        foreach ($payloads as $payload) {
            $response = Http::withToken($this->accessToken)->post($url, $payload);
            $responseData = $response->json();
            if (!empty($responseData['messages']) && isset($responseData['messages'][0]['id'])) {
                $status = 'success';
            } else {
                $errorMessage = $responseData['error']['message'] ?? 'Unknown error occurred';
                $status = 'error';
            }
        }

        // Step 5: Store message and images in conversation table
        $conversion = new Conversion();
        $conversion->sender = isset($user) ? $user->id : 'user';
        $conversion->ticket_id = $ticket->id;
        $conversion->description = $message; // Store text message
        $conversion->attachments = !empty($localFilePaths) ? json_encode($localFilePaths) : '';
        $conversion->save();

        // Notify users via Pusher and email (Show live msg on Copy link page.)
        $this->managePusherAndEmailNotification($conversion, $ticket, $request->all());

        return response()->json([
            'status' => $status,
            'errorType' => 'whatsapp',
            'message' => $errorMessage,
            'new_message' => $conversion->description ?? '',
            'timestamp' => \Carbon\Carbon::parse($conversion->created_at)->format('l h:ia'),
            'sender_name' => $conversion->replyBy?->name ?? 'Unknown',
            'attachments' => json_decode($conversion->attachments),
            'baseUrl' => env('APP_URL'),
        ]);
    }

    //  Upload images to Meta (WhatsApp API) and get media IDs.
    private function uploadMediaToMeta($filePaths)
    {
        $uploadedMedia = [];
        $mediaUploadUrl = "https://graph.facebook.com/v21.0/{$this->phoneNumberId}/media";

        foreach ($filePaths as $filePath) {
            $response = Http::withToken($this->accessToken)->attach('file', file_get_contents(base_path($filePath)), basename($filePath))
                ->post($mediaUploadUrl, [
                    'messaging_product' => 'whatsapp'
                ]);

            $responseData = $response->json();

            if (isset($responseData['id'])) {
                $uploadedMedia[] = $responseData['id'];
            } else {
                return [
                    'status' => 'error',
                    'message' => $responseData['error']['message'] ?? 'Unknown error while uploading media'
                ];
            }
        }
        return $uploadedMedia;
    }

    public function askForCloseTicket(Request $request, $ticketId)
    {
        $ticketId = decrypt($ticketId);
        $ticket = Ticket::where('id', $ticketId)->first();

        if (!$ticket) {
            return redirect()->back()->with('error', 'Ticket Not Found.');
        }

        $response = $this->askForCloseTicketMessage($ticket->mobile_no);

        if ($response->successful()) {
            return redirect()->back()->with('success', 'Message sent successfully.');
        } else {
            return redirect()->back()->with('error', __($response['error']['message']));
        }
    }

    private function askForCloseTicketMessage($recipient)
    {
        $buttons = [
            [
                'type' => 'reply',
                'reply' => ['id' => 'close_ticket_yes', 'title' => 'Yes'],
            ],
            [
                'type' => 'reply',
                'reply' => ['id' => 'close_ticket_no', 'title' => 'No'],
            ],
        ];
        return $this->sendInteractiveMessage($recipient, "Do you want to close the ticket? Please select an option:", $buttons);
    }

    // Sending Interactive Message 
    private function sendInteractiveMessage($recipient, $bodyText, $buttons)
    {
        $url = "https://graph.facebook.com/v21.0/{$this->phoneNumberId}/messages";
        $response = Http::withToken($this->accessToken)->post($url, [
            'messaging_product' => 'whatsapp',
            'to' => $recipient,
            'type' => 'interactive',
            'interactive' => [
                'type' => 'button',
                'body' => ['text' => $bodyText],
                'action' => ['buttons' => $buttons],
            ],
        ]);

        return $response;
    }
}
