<?php

namespace Workdo\SendClose\Http\Controllers;

use App\Events\TicketReply;
use App\Events\UpdateTicketStatus;
use App\Models\Conversion;
use App\Models\Ticket;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Workdo\FacebookChat\Http\Controllers\SendFacebookMessageController;
use Workdo\InstagramChat\Http\Controllers\SendInstagramMessageController;
use Workdo\WhatsAppChatBotAndChat\Http\Controllers\SendWhatsAppMessageController;
use Workdo\WhatsAppChatBotAndChat\Entities\UserState;

class SendCloseController extends Controller
{
     public function sendclose(Request $request, $ticket_id)
     {
        $user = Auth::user();

        if (Auth::user()->isAbleTo('send-close-ticket-reply manage')) {

            $ticket = Ticket::find($ticket_id);
            $description = $request->reply_description;
            $settings = getCompanyAllSettings();
            if ($ticket) {
                if ($description !== null || $request->hasfile('reply_attachments')) {
                    if ($ticket->type === 'Whatsapp' && UserState::where('ticket_id', $ticket->id)->where('state', 'existing_chat')->exists() && moduleIsActive('WhatsAppChatBotAndChat')) 
                    {
                        $whatsappController = new SendWhatsAppMessageController();
                        $response = $whatsappController->sendMessage($request, $ticket, $user);
                        return $response;
                    }elseif($ticket->type === 'Instagram'){
                        $instagramController = new SendInstagramMessageController();
                        $response = $instagramController->sendMessage($request, $ticket, $user);
                        return $response;
                    }elseif($ticket->type === 'Facebook'){
                        $facebookController = new SendFacebookMessageController();
                        $response = $facebookController->sendMessage($request, $ticket, $user);
                        return $response;
                    } else {
                        if ($request->hasfile('reply_attachments')) {
                          
                            $validation['reply_attachments.*'] = 'mimes:zip,rar,jpeg,jpg,png,gif,svg,pdf,txt,doc,docx,application/octet-stream,audio/mpeg,mpga,mp3,wav|max:204800';
                            $request->validate($validation); 
                        }

                        $conversion = new Conversion();
                        $conversion->sender = isset($user) ? $user->id : 'user';
                        $conversion->ticket_id = $ticket->id;
                        $conversion->description = $request->reply_description;

                        if ($request->hasfile('reply_attachments')) {
                            $attachment = $this->handleFileUpload($request, $ticket);
                            if (isset($attachment['status']) && $attachment['status'] == 'error') {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => $attachment['message'],
                                ]);
                            }
                            $conversion->attachments = isset($attachment) ? json_encode($attachment) : '';
                        }


                        $conversion->save();

                        event(new TicketReply($conversion, $request));
                        
                        
                        $ticket->status = 'Closed';
                        $ticket->save();
                        event(new UpdateTicketStatus($ticket, $request));

                        // Send Reply Email To The Customer
                        $error_msg = '';

                        // Send Email To The Ticket User
                        sendTicketEmail('Ticket Close', $settings, $ticket, $ticket, $error_msg);

                        return response()->json([
                            'conversation' => $conversion,
                            'status' => 'success', 
                            'message' => __('Reply Send and Ticket Close Successfully.'),
                            'new_message' => $conversion->description ?? '',
                            'timestamp' => \Carbon\Carbon::parse($conversion->created_at)->format('l h:ia'),
                            'sender_name' => $conversion->replyBy?->name,
                            'attachments' => json_decode($conversion->attachments),
                            'baseUrl'     => env('APP_URL'),
                        ]);
                      
                    }
                }
            } else {
                $data['status'] = 'error';
                $data['message'] = __('Ticket Not Found.');
                return $data;
            }
        } else {
            $data['status'] = 'error';
            $data['message'] = __('Permission Denied.');
            return $data;
        }
    }

    protected function handleFileUpload(Request $request, $ticket)
    {
        $data = [];
        $errors = [];
        if ($request->hasfile('reply_attachments')) {
            foreach ($request->file('reply_attachments') as $file) {
                $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $ext = $file->getClientOriginalExtension();
                $filenameToStore = $fileName . '_' . time() . '.' . $ext;

                $dir = 'tickets/' . $ticket->ticket_id;
                $path = multipleFileUpload($file, 'reply_attachments', $filenameToStore, $dir);

                if ($path['flag'] == 1) {
                    $data[] = $path['url'];
                } elseif ($path['flag'] == 0) {
                    $errors = __($path['msg']);
                }
            }
        }

        return $data;
    }
}
