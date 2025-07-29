<?php

namespace Workdo\WhatsAppChatBotAndChat\Http\Controllers;

use App\Events\CreateTicket;
use App\Http\Controllers\TicketConversionController;
use App\Models\Category;
use App\Models\Conversion;
use App\Models\Ticket;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use  Illuminate\Support\Facades\Auth;
use Pusher\Pusher;
use Workdo\TicketNumber\Entities\TicketNumber;
use Workdo\WhatsAppChatBotAndChat\Entities\UserState;



class WhatsappChatBotController extends TicketConversionController
{
    private $accessToken;
    private $phoneNumberId;


    public function __construct()
    {
        $settings = getCompanyAllSettings();
        $this->accessToken = isset($settings['whatsapp_chatbot_access_token']) ? $settings['whatsapp_chatbot_access_token'] : '';
        $this->phoneNumberId = isset($settings['whatsapp_chatbot_phone_number_id']) ? $settings['whatsapp_chatbot_phone_number_id'] : '';
    }
    // Message receive Webhook
    public function receiveMessages(Request $request)
    {
        if (isset($request->hub_mode) && $request->hub_mode == 'subscribe') {
            return response($request->hub_challenge, 200);
        }
        $data = $request->all();

        if (isset($data['entry'][0]['changes'][0]['value']['messages'][0])) {
            $message = $data['entry'][0]['changes'][0]['value']['messages'][0];
            $sender = $message['from'];
            $type = $message['type'];

            if (in_array($type, ['image', 'document', 'video'])) {
                $checkExistingChat = UserState::where('user_mobile', $sender)->first();
                if ($checkExistingChat) {
                    $ticketNumber = $checkExistingChat->ticket_id;
                    $this->handleExistingChatMedia($sender, $message, $ticketNumber);
                } else {
                    // $this->handleMediaMessage($sender, $message);
                    $this->sendMessage($sender, "Invalid Input.");
                }
            } elseif ($type === 'text') {
                $text = $message['text']['body'];
                $this->handleTextMessage($sender, $text);
            } elseif ($type === 'interactive') {
                $interactive = $message['interactive'];
                if (isset($interactive['button_reply'])) {
                    $buttonId = $interactive['button_reply']['id'];
                    $this->handleButtonClick($sender, $buttonId);
                } elseif (isset($interactive['list_reply'])) {
                    $listId = $interactive['list_reply']['id'];
                    $uniqueKey = $interactive['list_reply']['description'];
                    $this->handleListReply($sender, $listId, $uniqueKey);
                }
            }

            // Respond with a success message
            return response()->json(['status' => 'Message received successfully']);
        }
        return response()->json(['status' => 'No Message Found.'], 400);
    }

    // Handle Media 
    // private function handleMediaMessage($sender, $message)
    // {
    //     $caption = $message['caption'] ?? '';

    //     if (isset($message['image'])) {
    //         $mediaType = 'image';
    //     } elseif (isset($message['document'])) {
    //         $mediaType = 'document';
    //     } elseif (isset($message['video'])) {
    //         $mediaType = 'video';
    //     }

    //     if ($mediaType) {
    //         $mediaId = $message[$mediaType]['id'];
    //         $fileUrl = $this->downloadMedia($mediaId);
    //         $this->processAttachment($sender, $fileUrl, $mediaType, $caption);
    //     } else {
    //         $this->sendMessage($sender, "Unable to process the attachment. Please try again.");
    //     }
    // }

    // API call for download media. (need to check & change the code.)
    // private function downloadMedia($mediaId)
    // {
    //     $url = "https://graph.facebook.com/v21.0/{$mediaId}";
    //     $response = Http::withToken($this->accessToken)->get($url);

    //     if ($response->successful()) {
    //         $mediaData = $response->json();
    //         $mediaUrl = $mediaData['url'];
    //         $mimeType = $mediaData['mime_type'] ?? 'application/octet-stream';
    //         $extension = $this->getFileExtensionFromMime($mimeType);
    //         // Fetch the media file
    //         $mediaResponse = Http::withToken($this->accessToken)->get($mediaUrl);
    //         if ($mediaResponse->successful()) {
    //             $baseUploadPath = base_path("uploads/whatsappchatbot");
    //             if (!file_exists($baseUploadPath)) {
    //                 mkdir($baseUploadPath, 0755, true);
    //             }
    //             $fileName = time() . "." . $extension;
    //             $filePath = $baseUploadPath . $fileName;
    //             file_put_contents($filePath, $mediaResponse->body());
    //             $relativePath = "uploads/whatsappchatbot/" . $fileName;
    //             return $relativePath;
    //         }
    //     }

    //     return null;
    // }

    // Processing the Attachment
    // private function processAttachment($sender, $fileUrl, $mediaType, $caption)
    // {
    //     Log::info($fileUrl);
    // }


    private function getFileExtensionFromMime($mimeType)
    {
        $mimeMap = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'video/mp4' => 'mp4',
            'video/mpeg' => 'mpeg',
            'application/pdf' => 'pdf',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'application/vnd.ms-excel' => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
            'application/vnd.ms-powerpoint' => 'ppt',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
            'text/plain' => 'txt',
            'application/zip' => 'zip',
        ];

        return $mimeMap[$mimeType] ?? 'bin'; // Default to .bin if unknown MIME type
    }

    // Handle Text Message Once User Send the Message
    private function handleTextMessage($sender, $text)
    {
        $state = UserState::where('user_mobile', $sender)->first();
        if (!$state) {
            $this->sendWelcomeMessage($sender);
        } else {
            switch ($state->state) {
                case 'awaiting_customer_name':
                    $state->update(['state' => 'awaiting_customer_email', 'customer_name' => $text]);
                    $this->askCustomerEmail($sender);
                    break;
                case 'awaiting_customer_email':
                    $state->update(['state' => 'awaiting_ticket_subject', 'email' => $text]);
                    $this->askTicketSubject($sender);
                    break;
                case 'awaiting_ticket_subject':
                    $state->update(['state' => 'awaiting_category', 'subject' => $text]);
                    $this->askCategory($sender);
                    break;
                case 'awaiting_issue':
                    $state->update(['description' => $text]);
                    $this->createTicket($state->customer_name, $state->email, $sender, $state->state, $state->subject,  $text, $state->category_id);
                    $state->delete();
                    break;
                case 'existing_chat':
                    $this->storeUserConverstion($sender, $text, $state->ticket_id, 'user');
                    break;
                default:
                    $this->sendWelcomeMessage($sender);
            }
        }
    }

    // Send Interactive Message For Create or Search Ticket
    private function sendWelcomeMessage($recipient)
    {
        $buttons = [
            [
                'type' => 'reply',
                'reply' => ['id' => 'search_ticket', 'title' => 'Search Ticket'],
            ],
            [
                'type' => 'reply',
                'reply' => ['id' => 'create_ticket', 'title' => 'Create Ticket'],
            ],
            [
                'type' => 'reply',
                'reply' => ['id' => 'existing_ticket', 'title' => 'Existing Ticket'],
            ]
        ];
        $this->sendInteractiveMessage($recipient, "Welcome! Please Select An Option:", $buttons);
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

    // Handle Interactive Button Click

    private function handleButtonClick($sender, $buttonId)
    {
        if ($buttonId === 'search_ticket') {
            $this->searchTickets($sender);
        } elseif ($buttonId === 'create_ticket') {
            $this->askCustomerName($sender);
        } elseif ($buttonId === 'existing_ticket') {
            $this->showTicketList($sender);
        } elseif ($buttonId === 'close_ticket_yes') {
            $this->closeExistingChat($sender);
        } elseif ($buttonId === 'close_ticket_no') {
            $this->continueExistingChat($sender);
        } elseif (is_numeric($buttonId)) {
            $this->askIssue($sender, $buttonId);
        } else {
            $this->sendMessage($sender, "Invalid selection. Please try again.");
        }
    }

    // If User Select the Create Ticket Button then Ask Subject First
    private function askCustomerName($recipient)
    {
        UserState::updateOrCreate(['user_mobile' => $recipient], ['state' => 'awaiting_customer_name']);
        $this->sendMessage($recipient, "Please Enter Your Name");
    }

    //  Ask For Customer Email
    private function askCustomerEmail($recipient)
    {
        $this->sendMessage($recipient, "Please Enter Your Email");
    }

    // Ask For Ticket Subject
    private function askTicketSubject($recipient)
    {
        $this->sendMessage($recipient, "Please Enter Your Ticket Subject");
    }

    // Send Simple Plain Message 
    private function sendMessage($recipient, $content)
    {
        $url = "https://graph.facebook.com/v21.0/{$this->phoneNumberId}/messages";
        return Http::withToken($this->accessToken)->post($url, [
            'messaging_product' => 'whatsapp',
            'to' => $recipient,
            'type' => 'text',
            'text' => ['body' => $content],
        ]);
    }

    // Ask For Ticket Category
    private function askCategory($recipient)
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->sendMessage($recipient, "No categories available. Please contact support.");
            return;
        }

        $categoryData = $categories->map(function ($category): array {
            return [
                'id' => $category->id,
                'title' => $category->name,
                'description' => "category_list"
            ];
        })->toArray();

        $sections = [
            [
                'title' => 'Available Categories',
                'rows' => $categoryData,
            ],
        ];

        $this->sendListMessage($recipient, 'Please Select The Category For Your Ticket', 'Select Category', $sections);
    }


    // Send the List Message To The User Such as Category List , Priority List , Ticket List
    private function sendListMessage($recipient, $bodyText, $buttonText, $sections)
    {
        $url = "https://graph.facebook.com/v21.0/{$this->phoneNumberId}/messages";
        return Http::withToken($this->accessToken)->post($url, [
            'messaging_product' => 'whatsapp',
            'to' => $recipient,
            'type' => 'interactive',
            'interactive' => [
                'type' => 'list',
                'body' => ['text' => $bodyText],
                'footer' => ['text' => 'Choose an option'],
                'action' => [
                    'button' => $buttonText,
                    'sections' => $sections,
                ],
            ],
        ]);
    }


    // Handling List Reply Such as Ticket Category Selected By Customer
    private function handleListReply($sender, $listId, $uniqueKey)
    {
        // continue with  chat
        if ($uniqueKey == "existing_ticket") {
            $this->continueToChat($sender, $listId);
            return;
        }

        // Check if the list reply is for ticket search        
        if ($uniqueKey == "view_ticket") {
            $this->sendTicketLink($sender, $listId);
            return;
        }

        // Check if the list reply is for category selection
        if ($uniqueKey == "category_list") {
            $category = Category::find($listId);
            if ($category) {
                UserState::updateOrCreate(['user_mobile' => $sender], ['state' => 'awaiting_issue', 'category_id' => $category->id]);
                $this->askIssue($sender, $listId);
                return;
            }
        }


        $this->sendMessage($sender, "Invalid selection. Please try again.");
    }

    // If User Select The Category then Next Ask For The Issue
    private function askIssue($recipient, $text)
    {
        $this->sendMessage($recipient, content: "Please Describe Your Issue In Detail.");
    }

    // Create A Ticket
    private function createTicket($customerName, $customerEmail, $mobileNumber, $state,  $ticketSubject, $issueDescription, $categoryId)
    {
        $ticket = Ticket::create([
            'ticket_id' => time(),
            'subject' => $ticketSubject,
            'name' => $customerName,
            'email' => $customerEmail,
            'mobile_no' => $mobileNumber,
            'category_id' => $categoryId,
            'description' => $issueDescription,
            'type' => 'Whatsapp',
            'created_by' => 1
        ]);

        event(new CreateTicket($ticket, $ticket));

        $settings = getCompanyAllSettings();
        // Manage Pusher
        if (
            isset($settings['CHAT_MODULE']) && $settings['CHAT_MODULE'] == 'yes' &&
            isset($settings['PUSHER_APP_KEY'], $settings['PUSHER_APP_CLUSTER'], $settings['PUSHER_APP_ID'], $settings['PUSHER_APP_SECRET']) &&
            !empty($settings['PUSHER_APP_KEY']) &&
            !empty($settings['PUSHER_APP_CLUSTER']) &&
            !empty($settings['PUSHER_APP_ID']) &&
            !empty($settings['PUSHER_APP_SECRET'])
        ) {
            $options = array(
                'cluster' => $settings['PUSHER_APP_CLUSTER'],
                'useTLS' => true,
            );

            $pusher = new Pusher(
                $settings['PUSHER_APP_KEY'],
                $settings['PUSHER_APP_SECRET'],
                $settings['PUSHER_APP_ID'],
                $options
            );

            $data = [
                'id'        => $ticket->id,
                'tikcet_id' => $ticket->ticket_id,
                'name'      => $ticket->name,
                'subject'   => $ticket->subject,
                'status'    => $ticket->status,
                'created_at' => $ticket->created_at->diffForHumans(),
                'latestMessage' => $ticket->latestMessages($ticket->id),
                'unreadMessge' => $ticket->unreadMessge($ticket->id)->count(),
                'type' => $ticket->type,
            ];

            $channel = "new-ticket-1";
            $event = "new-ticket-event-1";
            $pusher->trigger($channel, $event, $data);
        }
        

        if (moduleIsActive('TicketNumber')) {
            $ticketNumber = TicketNumber::ticketNumberFormat($ticket->id);
        } else {
            $ticketNumber = $ticket->ticket_id;
        }


        $this->sendMessage($mobileNumber, "Your ticket has been created successfully. Here is your Ticket Number: {$ticketNumber}");
    }


    // If user Press Search Ticket Button from Interactive Message 
    private function searchTickets($recipient)
    {
        $tickets = Ticket::where('mobile_no', $recipient)->where('status', '!=', 'Closed')->get();

        if ($tickets->isEmpty()) {
            $this->sendMessage($recipient, "No tickets found associated with your number.");
            return;
        }

        $options = $tickets->map(function ($ticket) {
            return [
                'id' => $ticket->ticket_id,
                'title' => substr($ticket->subject, 0, 20) . (strlen($ticket->subject) > 20 ? '...' : ''),
                'description' => "view_ticket",
            ];
        })->toArray();

        $sections = [
            [
                'title' => 'Here Is Your All Tickets',
                'rows' => $options,
            ],
        ];

        $this->sendListMessage($recipient, 'Select a ticket:',  'Available Tickets', $sections);
    }

    // Send the selected Ticket Link To The User
    private function sendTicketLink($recipient, $ticketId)
    {
        $ticket = Ticket::where('ticket_id', $ticketId)->first();
        if ($ticket) {
            $this->sendMessage($recipient, "You can view your ticket from this link :  " . route('home.view', ['id' => encrypt($ticket->ticket_id)]));
        } else {
            $this->sendMessage($recipient, "Sorry, We Are Not Able To Find Your Ticket.");
        }
    }

    // Show TicketList To The Users For Continue to Ticket Chat
    private function showTicketList($recipient)
    {
        $tickets = Ticket::where('mobile_no', $recipient)->where('status', '!=', 'Closed')->get();

        if ($tickets->isEmpty()) {
            $this->sendMessage($recipient, "No tickets found associated with your number.");
            return;
        }

        $options = $tickets->map(function ($ticket) {
            return [
                'id' => $ticket->ticket_id,
                'title' => substr($ticket->subject, 0, 20) . (strlen($ticket->subject) > 20 ? '...' : ''),
                'description' => "existing_ticket",
            ];
        })->toArray();


        $sections = [
            [
                'title' => 'Select Ticket',
                'rows' => $options,
            ],
        ];

        $this->sendListMessage($recipient, 'Select a ticket:', 'Available Tickets', $sections);
    }

    // Find the Ticket And Continue To Chat
    private function continueToChat($recipient, $ticketId)
    {
        $ticket = Ticket::where('ticket_id', $ticketId)->where('status', '!=', 'Closed')->first();
        if ($ticket) {

            if (moduleIsActive('TicketNumber')) {
                $ticketNumber = TicketNumber::ticketNumberFormat($ticket->id);
            } else {
                $ticketNumber = $ticket->ticket_id;
            }

            $this->handleAdminMessage($recipient, "You have selected Ticket Id: " . $ticketNumber . ". How can we assist you further?", $ticket->id);
        } else {
            $this->sendMessage($recipient, "Sorry, We Are Not Able To Find Your Ticket.");
        }
    }

    // Handle Admin Messages
    private function handleAdminMessage($recipient, $content, $ticketId)
    {
        $userState = UserState::where('ticket_id', $ticketId)->first();
        if (!$userState) {
            UserState::create([
                'ticket_id' => $ticketId,
                'user_mobile' => $recipient,
                'state' => 'existing_chat',
            ]);
        }
        $this->sendMessageWithTicketNumber($recipient, $content, $ticketId, 'admin');
    }

    // Send Text Msg Between The User & Admin With TicketNumber (Continue to Chat)
    private function sendMessageWithTicketNumber($recipient, $content, $ticketId, $sender)
    {
        $url = "https://graph.facebook.com/v21.0/{$this->phoneNumberId}/messages";
        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $recipient,
            'type' => 'text',
            'text' => ['body' => $content],
        ];

        $response = Http::withToken($this->accessToken)->post($url, $payload);

        if ($response->successful()) {
            $conversion =  Conversion::create([
                'ticket_id' => $ticketId,
                'description' => $content,
                'sender' => $sender == 'admin' ? 1 : 'user',
            ]);

            // manage pusher
            $settings = getCompanyAllSettings();
            $ticket = Ticket::where('id', $ticketId)->first();
            if (
                isset($settings['CHAT_MODULE']) && $settings['CHAT_MODULE'] == 'yes' &&
                isset($settings['PUSHER_APP_KEY'], $settings['PUSHER_APP_CLUSTER'], $settings['PUSHER_APP_ID'], $settings['PUSHER_APP_SECRET']) &&
                !empty($settings['PUSHER_APP_KEY']) &&
                !empty($settings['PUSHER_APP_CLUSTER']) &&
                !empty($settings['PUSHER_APP_ID']) &&
                !empty($settings['PUSHER_APP_SECRET'])
            ) {
                $options = array(
                    'cluster' => $settings['PUSHER_APP_CLUSTER'],
                    'useTLS' => true,
                );

                $pusher = new Pusher(
                    $settings['PUSHER_APP_KEY'],
                    $settings['PUSHER_APP_SECRET'],
                    $settings['PUSHER_APP_ID'],
                    $options
                );

                $data = [
                    'id'        => $conversion->id,
                    'tikcet_id' => $conversion->ticket_id,
                    'ticket_unique_id' => $ticketId,
                    'new_message' => process_content_images($conversion->description),
                    'timestamp'   => \Carbon\Carbon::parse($conversion->created_at)->format('l h:ia'),
                    'sender_name' => $conversion->replyBy?->name,
                    'attachments' => json_decode($conversion->attachments),
                    'baseUrl'     => env('APP_URL'),
                    'latestMessage' => $ticket->latestMessages($ticket->id),
                    'unreadMessge' => $ticket->unreadMessge($ticket->id)->count(),
                ];

                // Channel For Show RealTime Msg on Admin or Agent Side 
                if ($ticket->is_assign == null) {
                    $channel = "ticket-reply-$ticket->created_by";
                    $event = "ticket-reply-event-$ticket->created_by";
                } else {
                    $channel = "ticket-reply-$ticket->is_assign";
                    $event = "ticket-reply-event-$ticket->is_assign";
                }
                $pusher->trigger($channel, $event, $data);

                // Channel For Show RealTime Msg on Ticket View Page

                $data = [
                    'id'        => $conversion->id,
                    'ticket_id' => $conversion->ticket_id,
                    'ticket_number' => $ticket->ticket_id,
                    'new_message' => process_content_images($conversion->description),
                    'sender_name' => $conversion->replyBy?->name ?? '',
                    'attachments' => json_decode($conversion->attachments),
                    'timestamp'   => \Carbon\Carbon::parse($conversion->created_at)->format('l h:ia'),
                    'baseUrl'     => env('APP_URL'),
                ];
                $channel = "ticket-reply-send-$ticket->ticket_id";
                $event = "ticket-reply-send-event-$ticket->ticket_id";
                $pusher->trigger($channel, $event, $data);
            }
        }


        return $response;
    }

    private function storeUserConverstion($recipient, $text, $ticketId, $sender)
    {
        $ticket = Ticket::where('id', $ticketId)->first();
        $conversion = Conversion::create([
            'ticket_id' => $ticketId,
            'description' => $text,
            'sender' => $sender,
        ]);

        $settings = getCompanyAllSettings();

        // manage pusher
        if (
            isset($settings['CHAT_MODULE']) && $settings['CHAT_MODULE'] == 'yes' &&
            isset($settings['PUSHER_APP_KEY'], $settings['PUSHER_APP_CLUSTER'], $settings['PUSHER_APP_ID'], $settings['PUSHER_APP_SECRET']) &&
            !empty($settings['PUSHER_APP_KEY']) &&
            !empty($settings['PUSHER_APP_CLUSTER']) &&
            !empty($settings['PUSHER_APP_ID']) &&
            !empty($settings['PUSHER_APP_SECRET'])
        ) {
            $options = array(
                'cluster' => $settings['PUSHER_APP_CLUSTER'],
                'useTLS' => true,
            );

            $pusher = new Pusher(
                $settings['PUSHER_APP_KEY'],
                $settings['PUSHER_APP_SECRET'],
                $settings['PUSHER_APP_ID'],
                $options
            );

            $data = [
                'id'        => $conversion->id,
                'tikcet_id' => $conversion->ticket_id,
                'ticket_unique_id' => $ticketId,
                'new_message' => process_content_images($conversion->description),
                'timestamp'   => \Carbon\Carbon::parse($conversion->created_at)->format('l h:ia'),
                'sender_name' => $conversion->replyBy?->name ?? '',
                'attachments' => json_decode($conversion->attachments),
                'baseUrl'     => env('APP_URL'),
                'latestMessage' => $ticket->latestMessages($ticket->id),
                'unreadMessge' => $ticket->unreadMessge($ticket->id)->count(),
            ];

            // Channel For Show RealTime Msg on Admin or Agent Side 
            if ($ticket->is_assign == null) {
                $channel = "ticket-reply-$ticket->created_by";
                $event = "ticket-reply-event-$ticket->created_by";
            } else {
                $channel = "ticket-reply-$ticket->is_assign";
                $event = "ticket-reply-event-$ticket->is_assign";
            }
            $pusher->trigger($channel, $event, $data);

            // Channel For Show RealTime Msg on Ticket View Page

            $data = [
                'id'        => $conversion->id,
                'ticket_id' => $conversion->ticket_id,
                'ticket_number' => $ticket->ticket_id,
                'new_message' => process_content_images($conversion->description),
                'sender_name' => $conversion->replyBy?->name ?? '',
                'attachments' => json_decode($conversion->attachments),
                'timestamp'   => \Carbon\Carbon::parse($conversion->created_at)->format('l h:ia'),
                'baseUrl'     => env('APP_URL'),
            ];
            $channel = "ticket-reply-send-$ticket->ticket_id";
            $event = "ticket-reply-send-event-$ticket->ticket_id";
            $pusher->trigger($channel, $event, $data);
        }
    }


    // Handle Existing Chat Media. 
    private function handleExistingChatMedia($sender, $message, $ticketNumber)
    {
        $caption = $message['caption'] ?? '';

        if (isset($message['image'])) {
            $mediaType = 'image';
        } elseif (isset($message['document'])) {
            $mediaType = 'document';
        } elseif (isset($message['video'])) {
            $mediaType = 'video';
        }

        if ($mediaType) {
            $mediaId = $message[$mediaType]['id'];
            $fileUrl = $this->downloadExistingChatMedia($mediaId, $ticketNumber);
            $this->processExistingChatAttachment($sender, $fileUrl, $ticketNumber);
        } else {
            $this->sendMessage($sender, "Unable to process the attachment. Please try again.");
        }
    }

    // API call for download media. (need to check & change the code.)
    private function downloadExistingChatMedia($mediaId, $ticketNumber)
    {
        $url = "https://graph.facebook.com/v21.0/{$mediaId}";
        $response = Http::withToken($this->accessToken)->get($url);

        if ($response->successful()) {
            $mediaData = $response->json();
            $mediaUrl = $mediaData['url'];
            $mimeType = $mediaData['mime_type'] ?? 'application/octet-stream';
            $extension = $this->getFileExtensionFromMime($mimeType);
            // Fetch the media file
            $mediaResponse = Http::withToken($this->accessToken)->get($mediaUrl);
            if ($mediaResponse->successful()) {
                $ticket = Ticket::where('id', $ticketNumber)->first();
                $baseUploadPath = base_path("uploads/tickets/{$ticket->ticket_id}/");

                if (!file_exists($baseUploadPath)) {
                    mkdir($baseUploadPath, 0755, true);
                }
                $fileName = "whatsapp_" . time() . "." . $extension;
                $filePath = $baseUploadPath . $fileName;

                file_put_contents($filePath, $mediaResponse->body());
                $relativePath = "uploads/tickets/{$ticket->ticket_id}/" . $fileName;
                return $relativePath;
            }
        }
        return null;

        // For Local Testing
        // $ticket = Ticket::where('id', $ticketNumber)->first();
        // $baseUploadPath = base_path("uploads/tickets/{$ticket->ticket_id}/");

        // if (!file_exists($baseUploadPath)) {
        //     mkdir($baseUploadPath, 0755, true);
        // }

        // $fileName = "whatsapp_" . time() . "." . 'jpg';
        // $filePath = $baseUploadPath . $fileName;
        // file_put_contents($filePath, 'local');
        // $relativePath = "uploads/tickets/{$ticket->ticket_id}/" . $fileName;
        // return $relativePath;
    }

    // Process Existing Chat Attchment Store in Converstation Table
    private function processExistingChatAttachment($sender, $fileUrl, $ticketNumber)
    {
        $data[] = $fileUrl;
        $conversion = Conversion::create([
            'ticket_id' => $ticketNumber,
            'attachments' => isset($fileUrl) ? json_encode($data)  : '',
            'sender' => 'user',
        ]);


        // manage pusher
        $settings = getCompanyAllSettings();
        $ticket = Ticket::where('id', $ticketNumber)->first();
        if (
            isset($settings['CHAT_MODULE']) && $settings['CHAT_MODULE'] == 'yes' &&
            isset($settings['PUSHER_APP_KEY'], $settings['PUSHER_APP_CLUSTER'], $settings['PUSHER_APP_ID'], $settings['PUSHER_APP_SECRET']) &&
            !empty($settings['PUSHER_APP_KEY']) &&
            !empty($settings['PUSHER_APP_CLUSTER']) &&
            !empty($settings['PUSHER_APP_ID']) &&
            !empty($settings['PUSHER_APP_SECRET'])
        ) {
            $options = array(
                'cluster' => $settings['PUSHER_APP_CLUSTER'],
                'useTLS' => true,
            );

            $pusher = new Pusher(
                $settings['PUSHER_APP_KEY'],
                $settings['PUSHER_APP_SECRET'],
                $settings['PUSHER_APP_ID'],
                $options
            );

            $data = [
                'id'        => $conversion->id,
                'tikcet_id' => $conversion->ticket_id,
                'ticket_unique_id' => $ticketNumber,
                'new_message' => process_content_images($conversion->description),
                'timestamp'   => \Carbon\Carbon::parse($conversion->created_at)->format('l h:ia'),
                'sender_name' => $conversion->replyBy?->name ?? '',
                'attachments' => json_decode($conversion->attachments),
                'baseUrl'     => env('APP_URL'),
                'latestMessage' => $ticket->latestMessages($ticket->id),
                'unreadMessge' => $ticket->unreadMessge($ticket->id)->count(),
            ];


            // Channel For Show RealTime Msg on Admin or Agent Side 
            if ($ticket->is_assign == null) {
                $channel = "ticket-reply-$ticket->created_by";
                $event = "ticket-reply-event-$ticket->created_by";
            } else {
                $channel = "ticket-reply-$ticket->is_assign";
                $event = "ticket-reply-event-$ticket->is_assign";
            }

            $pusher->trigger($channel, $event, $data);

            // Channel For Show RealTime Msg on Ticket View Page

            $data = [
                'id'        => $conversion->id,
                'ticket_id' => $conversion->ticket_id,
                'ticket_number' => $ticket->ticket_id,
                'new_message' => process_content_images($conversion->description),
                'sender_name' => $conversion->replyBy?->name ?? '',
                'attachments' => json_decode($conversion->attachments),
                'timestamp'   => \Carbon\Carbon::parse($conversion->created_at)->format('l h:ia'),
                'baseUrl'     => env('APP_URL'),
            ];
            $channel = "ticket-reply-send-$ticket->ticket_id";
            $event = "ticket-reply-send-event-$ticket->ticket_id";
            $pusher->trigger($channel, $event, $data);
        }
    }



    // Close Existing Chat
    private function closeExistingChat($recipient)
    {
        $getTicketNumber = UserState::where('user_mobile', $recipient)->where('state', 'existing_chat')->first();
        if ($getTicketNumber) {
            $ticket = Ticket::where('id', $getTicketNumber->ticket_id)->first();
            $settings = getCompanyAllSettings();
            if ($ticket) {
                $ticket->status = "Closed";
                $ticket->save();
                $getTicketNumber->delete();

                if (moduleIsActive('TicketNumber')) {
                    $ticketNumber = TicketNumber::ticketNumberFormat($ticket->id);
                } else {
                    $ticketNumber = $ticket->ticket_id;
                }

                sendTicketEmail('Ticket Close', $settings, $ticket, $ticket, $error_msg);

                $this->sendMessage($recipient, 'Your Ticket Number ' . $ticketNumber . ' Has Been Closed.');
            } else {
                $this->sendMessage($recipient, "Sorry, We Are Not Able To Find Your Ticket.");
            }
        } else {
            $this->sendMessage($recipient, "Sorry, We Are Not Able To Find Your Ticket.");
        }
    }

    // If User Select Not Then Continue With Existing Chat

    private function continueExistingChat($recipient)
    {
        $this->sendMessage($recipient, "Okay, You Can Continue The Chat.");
    }
}
