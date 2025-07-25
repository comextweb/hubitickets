<?php

namespace App\Http\Controllers;

use App\Events\TicketReply;
use App\Events\UpdateTicketStatus;
use App\Models\Category;
use App\Models\Conversion;
use App\Models\CustomField;
use App\Models\Priority;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Exception;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

use Illuminate\Support\Facades\Log;
use Pusher\Pusher;
use Workdo\FacebookChat\Http\Controllers\SendFacebookMessageController;
use Workdo\InstagramChat\Http\Controllers\SendInstagramMessageController;
use Workdo\TicketNumber\Entities\TicketNumber;
use Workdo\WhatsAppChatBotAndChat\Entities\UserState;
use Workdo\WhatsAppChatBotAndChat\Http\Controllers\SendWhatsAppMessageController;

class TicketConversionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (Auth::user()->isAbleTo('ticket manage')) {
            $tikcettype = Ticket::getTicketTypes();
            $settings = getCompanyAllSettings();
            if (Auth::user()->hasRole('admin') || Auth::user()->isAbleTo('ticket manage all')) {
                $tickets = Ticket::with('getAgentDetails', 'getCategory', 'getPriority', 'getTicketCreatedBy');

            }elseif (Auth::user()->isAbleTo('ticket manage department')) {
                // Obtiene IDs de departamentos del usuario
                $userDepartmentIds = Auth::user()->departments->pluck('id')->toArray();
                
                $tickets = Ticket::with('getAgentDetails', 'getCategory', 'getPriority', 'getTicketCreatedBy')
                    //->whereIn('department_id', $userDepartmentIds); // Solo tickets de sus departamentos
                    ->where(function ($query) use ($userDepartmentIds) {
                        $query->whereIn('department_id', $userDepartmentIds)
                            ->orWhere('is_assign', Auth::user()->id)
                            ->orWhere('created_by', Auth::user()->id);
                    });
            
            }
            //if (Auth::user()->hasRole('admin') || Auth::user()->isAbleTo('ticket manage all')) {
            //    $tickets = Ticket::with('getAgentDetails', 'getCategory', 'getPriority', 'getTicketCreatedBy');
            //} 
            elseif (Auth::user()->hasRole('customer')) {
                $tickets = Ticket::with('getAgentDetails', 'getCategory', 'getPriority', 'getTicketCreatedBy')->where('email', Auth::user()->email);
            } else {
                $tickets = Ticket::with('getAgentDetails', 'getCategory', 'getPriority', 'getTicketCreatedBy')->where(function ($query) {
                    $query->where('is_assign', Auth::user()->id)
                        ->orWhere('created_by', Auth::user()->id);
                });
            }

            if ($request->tikcettype != null) {
                $tickets->where('type', $request->tikcettype);
            }

            if ($request->priority != null) {
                $tickets->where('priority', $request->priority);
            }

            if ($request->status != null) {
                $tickets->where('status', $request->status);
            }

            if ($request->tags != null) {
                $tickets->whereRaw("FIND_IN_SET(?, tags_id)", [$request->tags]);
            }


            $tickets = $tickets->orderBy('id', 'desc')->get();

            $totalticket = $tickets->count();
            $ticketsWithMessages = $tickets->map(function ($ticket) {
                $latestMessage = $ticket->latestMessages($ticket->id);
                $unreadMessageCount = $ticket->unreadMessge($ticket->id)->count();
                $ticket->tag = $ticket->getTagsAttribute();
                $ticket->latest_message = $latestMessage;
                $ticket->unread = $unreadMessageCount;
                $ticket->ticket_id = moduleIsActive('TicketNumber') ? TicketNumber::ticketNumberFormat($ticket->id) : $ticket->ticket_id;
                return $ticket;
            });

            if ($request->ajax()) {
                // Return the tickets along with the latest message and unread count
                return response()->json([
                    'tickets' => $ticketsWithMessages, // Use the processed ticketsWithMessages
                ]);
            }
            //$priorities = Priority::where('created_by', creatorId())->get();
            $priorities = Priority::all();


            return view('admin.chats.new-chat', compact('tickets', 'tikcettype', 'totalticket', 'settings', 'priorities'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }


    public function getallTicket(Request $request)
    {
        $tickets = Ticket::where('id', '<', $request->lastTicketId)
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();
        $ticketsWithMessages = $tickets->map(function ($ticket) {
            $latestMessage = $ticket->latestMessages($ticket->id);
            $unreadMessageCount = $ticket->unreadMessge($ticket->id)->count();
            $ticket->tag = $ticket->getTagsAttribute();
            $ticket->latest_message = $latestMessage;
            $ticket->unread = $unreadMessageCount;
            $ticket->ticket_id = moduleIsActive('TicketNumber') ? TicketNumber::ticketNumberFormat($ticket->id) : $ticket->ticket_id;
            return $ticket;
        });

        return response()->json([
            'tickets' => $ticketsWithMessages,
        ]);
    }

    public function getticketDetails($ticket_id)
    {


        //$ticket = Ticket::with('conversions','getCategory', 'getPriority', 'getTicketCreatedBy','getDepartment','getAgentDetails')->find($ticket_id);
        $ticket = Ticket::with([
                'conversions' => function($query) {
                    $query->orderBy('id');
                },
                'getCategory:id,name',
                'getPriority:id,name,color',
                'getTicketCreatedBy:id,name',
                'getDepartment:id,name',
                'getAgentDetails:id,name'
            ])->find($ticket_id);


        if ($ticket) {
                /*$conversions = Conversion::where('ticket_id', $ticket_id)->get();
                foreach ($conversions as $conversion) {

                $conversion = Conversion::find($conversion->id);
                $conversion->is_read = 1;
                $conversion->update();
            }*/
            Conversion::where('ticket_id', $ticket_id)
            ->where('is_read', 0)
            ->update(['is_read' => 1]);


            $status = $ticket->status;
            $priority = $ticket->getPriority;
            
            $users = User::where('type', 'agent')->get();
            //$categories = Category::where('created_by', creatorId())->get();
            $categories = Category::all();
            $categoryTree = buildCategoryTree($categories);
            //$priorities = Priority::where('created_by', creatorId())->get();
            $priorities = Priority::all();
            $tikcettype = Ticket::getTicketTypes();
            $customFields = CustomField::where('is_core', false)->orderBy('order')->get();
            $departments = Department::where('is_active', true)->get(); // Asegúrate de importar el modelo Department
            $settings = getCompanyAllSettings();

            if (moduleIsActive('TicketNumber')) {
                $ticketNumber = TicketNumber::ticketNumberFormat($ticket->id);
            } else {
                $ticketNumber = $ticket->ticket_id;
            }

            $tickethtml = view('admin.chats.new-chat-messge', compact('ticket', 'users', 'categoryTree', 'priorities', 'tikcettype', 'customFields','departments', 'settings'))->render();

            $response = [
                'tickethtml' => $tickethtml,
                'status' => $status,
                'priority' => $priority,
                'unread_message_count' => $ticket->unreadMessge($ticket_id)->count(),
                'tag' => $ticket->getTagsAttribute(),
                'ticketNumber' => $ticketNumber,
                'currentTicket' => $ticket,
                'encryptedTicketId' => encrypt($ticket->ticket_id),
                'agentName' => $ticket->getAgentDetails?->name ?? 'No Asignado',
                'createdByName' => $ticket->getTicketCreatedBy?->name ?? 'No Asignado',
                'tipoUsuario' => $ticket->getTipoUsuario()
            ];
            return json_encode($response);
        } else {
            $response['status'] = 'error';
            $response['message'] = __('Ticket not found');
            return $response;
        }
    }

    public function statusChange(Request $request, $id)
    {
        $user = Auth::user();
        if (Auth::user()->isAbleTo('ticket edit')) {
            $status = $request->status;
            $ticket = Ticket::find($id);
            $settings = getCompanyAllSettings();
            if ($ticket) {
                $ticket->status = $status;
                if ($status == 'Resolved') {
                    $ticket->reslove_at = now();
                }
                $ticket->save();
                event(new UpdateTicketStatus($ticket, $request));
                if ($status == 'Closed') {
                    // Send Email To The Ticket User
                    $error_msg = '';
                    sendTicketEmail('Ticket Close', $settings, $ticket, $ticket, $error_msg);
                }


                $data['status'] = 'success';
                $data['message'] = __('Ticket status changed successfully.');
                return $data;
            } else {
                $data['status'] = 'error';
                $data['message'] = __('Ticket not found');
                return $data;
            }
        } else {
            $data['status'] = 'error';
            $data['message'] = __('Permission Denied.');
            return $data;
        }
    }


    public function replystore(Request $request, $ticket_id)
    {
        $user = Auth::user();

        if ($user->isAbleTo('ticket reply')) {

            $ticket = Ticket::find($ticket_id);
            $description = $request->reply_description;

            if ($ticket) {
                if ($description !== null || $request->hasfile('reply_attachments')) {
                    if ($ticket->type === 'Whatsapp' && UserState::where('ticket_id', $ticket->id)->where('state', 'existing_chat')->exists() && moduleIsActive('WhatsAppChatBotAndChat')) {
                        $whatsappController = new SendWhatsAppMessageController();
                        $response = $whatsappController->sendMessage($request, $ticket, $user);
                        return $response;
                    } elseif ($ticket->type === 'Instagram' && moduleIsActive('InstagramChat')) {
                        $instagramController = new SendInstagramMessageController();
                        $response = $instagramController->sendMessage($request, $ticket, $user);
                        return $response;
                    } elseif ($ticket->type === 'Facebook' && moduleIsActive('FacebookChat')) {
                        $facebookController = new SendFacebookMessageController();
                        $response = $facebookController->sendMessage($request, $ticket, $user);
                        return $response;
                    } else {
                        if ($request->hasfile('reply_attachments')) {
                            $validation['reply_attachments.*'] = 'mimes:zip,rar,jpeg,jpg,png,gif,svg,pdf,txt,doc,docx,application/octet-stream,audio/mpeg,mpga,mp3,wav|max:204800';
                            $this->validate($request, $validation);
                        }

                        $conversion = new Conversion();
                        if (moduleIsActive('CustomerLogin') && Auth::user()->hasRole('customer')) {
                            $conversion->sender = 'user';
                        } else {
                            $conversion->sender = isset($user) ? $user->id : 'user';
                        }
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

                        Conversion::change_status($ticket_id);

                        event(new TicketReply($conversion, $request));

                        // Manage Pusher
                        $this->managePusherAndEmailNotification($conversion, $ticket, $request);


                        return response()->json([
                            'converstation' =>  $conversion,
                            'new_message' => $conversion->description ?? '',
                            'timestamp' => \Carbon\Carbon::parse($conversion->created_at)->format('d/m/Y, h:ia'),
                            'sender_name' => $conversion->replyBy?->name,
                            'sender_email' => $conversion->replyBy?->email,
                            'ticket_email' => $ticket->email,
                            'attachments' => json_decode($conversion->attachments),
                            'baseUrl' => env('APP_URL'),
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


    // Handle File Uploading
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
                    $errors['status'] = 'error';
                    $errors['message'] = __($path['msg']);
                    return $errors;
                }
            }
        }

        return $data;
    }
    // Handling Pusher
    protected function managePusherAndEmailNotification($conversion, $ticket, $request)
    {
        $settings = getCompanyAllSettings();
        // **Pusher Notifications**
        if (
            isset($settings['CHAT_MODULE']) && $settings['CHAT_MODULE'] == 'yes' &&
            isset($settings['PUSHER_APP_KEY'], $settings['PUSHER_APP_CLUSTER'], $settings['PUSHER_APP_ID'], $settings['PUSHER_APP_SECRET']) &&
            !empty($settings['PUSHER_APP_KEY']) &&
            !empty($settings['PUSHER_APP_CLUSTER']) &&
            !empty($settings['PUSHER_APP_ID']) &&
            !empty($settings['PUSHER_APP_SECRET'])
        ) {
            $options = [
                'cluster' => $settings['PUSHER_APP_CLUSTER'],
                'useTLS' => true,
            ];

            $pusher = new Pusher(
                $settings['PUSHER_APP_KEY'],
                $settings['PUSHER_APP_SECRET'],
                $settings['PUSHER_APP_ID'],
                $options
            );

            $data = [
                'converstation' => $conversion,
                'replyByRole' => $conversion->replyBy?->type,
                'id' => $conversion->id,
                'ticket_id' => $conversion->ticket_id,
                'ticket_number' => $ticket->ticket_id,
                'new_message' => $conversion->description ?? '',
                'sender_name' => $conversion->replyBy?->name,
                'attachments' => json_decode($conversion->attachments),
                'timestamp' => \Carbon\Carbon::parse($conversion->created_at)->format('d/m/Y, h:ia'),
                'baseUrl' => env('APP_URL'),
            ];
            $channel = "ticket-reply-send-$ticket->ticket_id";
            $event = "ticket-reply-send-event-$ticket->ticket_id";
            if (strlen(json_encode($data)) > 10240) {
                Log::warning('Pusher payload too large for ticket: ' . $ticket->ticket_id);
            } else {
                $pusher->trigger($channel, $event, $data);
            }

            // CODIGO AGREGADO PARA ACTIVAR EL REAL TIME CHAT ENTRE AGENTES   6/4/2025 bloor OJO: Mientras se use Pusher van a ser mas moensajes enviados
            $data = [
                'id' => $conversion->id,
                'tikcet_id' => $conversion->ticket_id,
                'ticket_email' => $conversion->ticket->email,
                'ticket_unique_id' => $ticket->id,
                'new_message' => $conversion->description ?? '',
                'timestamp' => \Carbon\Carbon::parse($conversion->created_at)->format('d/m/Y, h:ia'),
                'sender_name' => $conversion->replyBy?->name,
                'sender_email' => $conversion->replyBy?->email,
                'attachments' => json_decode($conversion->attachments),
                'baseUrl' => env('APP_URL'),
                'latestMessage' => $ticket->latestMessages($ticket->id),
                'unreadMessge' => $ticket->unreadMessge($ticket->id)->count(),
            ];

            if (strlen(json_encode($data)) > 10240) {
                Log::warning('Pusher payload too large for ticket: ' . $ticket->ticket_id);
            } else {
                // Obtener el ID del usuario que está enviando el mensaje
                $senderId = $conversion->replyBy?->id;

                // Enviar al creador del ticket, solo si no es el remitente
                if ($ticket->created_by != $senderId) {
                    $pusher->trigger("ticket-reply-{$ticket->created_by}", "ticket-reply-event-{$ticket->created_by}", $data);
                }

                // Enviar al agente asignado, solo si existe y no es el remitente
                if (!empty($ticket->is_assign) && $ticket->is_assign != $senderId) {
                    $pusher->trigger("ticket-reply-{$ticket->is_assign}", "ticket-reply-event-{$ticket->is_assign}", $data);
                }
            }
            // FIN CODIGO AGREGADO PARA ACTIVAR EL REAL TIME CHAT ENTRE AGENTES    

        }

        // **Email Notifications**
        $error_msg = '';

        $sender_name = $conversion->replyBy?->name;
        $request->merge(['sender_name' => $sender_name]);
        sendTicketEmail('Reply Mail To Customer', $settings, $ticket, $request, $error_msg);
        

        // Obtener el agente asignado
        $agent = User::where('id', $ticket->is_assign)->first();
        $creator = User::where('id', $ticket->created_by)->first();
        

        // Enviar al agente o creador solo si:
        // - hay un agente asignado o creador
        // - y el usuario que responde NO es ese agente o creador
        $sender = $conversion->replyBy;
        if ($agent && $sender->id !== $agent->id) {
            sendTicketEmail('Reply Mail To Agent', $settings, $ticket, $request, $error_msg);
        }

        if ($creator && $sender->id !== $creator->id) {
            sendTicketEmail('Reply Mail To Creator', $settings, $ticket, $request, $error_msg);
        }
        
    }



    public function ticketNote(Request $request, $ticketId)
    {
        if (Auth::user()->isAbleTo('tiketnote store')) {
            $ticket = Ticket::where('id', $ticketId)->first();
            if ($ticket) {
                $settings = getCompanyAllSettings();
                return view('admin.chats.private-note', compact('ticket', 'settings'));
            } else {
                return response()->json(['error' => __('Ticket Not Found.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function ticketNoteStore(Request $request, $ticketId)
    {
        if (Auth::user()->isAbleTo('tiketnote store')) {
            $ticket = Ticket::where('id', $ticketId)->first();
            if ($ticket) {
                $ticket->note = $request->ticketPrivatnote ?? '';
                $ticket->save();
                return response()->json([
                    'status' => true,
                    'message' => __('Private Note Save Successfully.')
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => __('Ticket Not Found.')
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => __('Permission Denied.')
            ]);
        }
    }

    public function assignChange(Request $request, $id)
    {

        $assign = $request->assign;
        $ticket = Ticket::find($id);
        if ($ticket) {
            $old_agent_id = $ticket->is_assign;
            $ticket->is_assign = $assign;
            $ticket->type = "Assigned";
            $ticket->save();

            $this->createAgentChangeConversation($request, $ticket, $old_agent_id, $assign);

            $data['status'] = 'success';
            $data['message'] = __('Ticket assign successfully.');
            return $data;
        } else {
            $data['status'] = 'error';
            $data['message'] = __('Ticket not found');
            return $data;
        }
    }

    public function assignPublicChange(Request $request, $id)
    {
        try {
            $encryptedToken = $request->query('token');
            $decryptedToken = Crypt::decryptString($encryptedToken);
        } catch (DecryptException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token inválido o corrupto.',
            ], 401);
        }

        if ($decryptedToken !== config('app.public_token')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token inválido o acceso no autorizado.',
            ], 401);
        }

        $assign = $request->assign;
        $ticket = Ticket::find($id);
        if ($ticket) {
            $old_agent_id = $ticket->is_assign;
            $ticket->is_assign = $assign;
            $ticket->type = "Assigned";
            $ticket->save();
            $this->createAgentChangeConversation($request, $ticket, $old_agent_id, $assign);

            $data['status'] = 'success';
            $data['message'] = __('Ticket assign successfully.');
            return $data;
        } else {
            $data['status'] = 'error';
            $data['message'] = __('Ticket not found');
            return $data;
        }
    }
    protected function createAgentChangeConversation(Request $request, $ticket, $old_agent_id, $new_agent_id)
    {
        // Obtener nombres de los agentes
        $old_agent = $old_agent_id ? User::find($old_agent_id) : null;
        $new_agent = User::find($new_agent_id);
        
        // Determinar el mensaje según si había agente asignado antes
        if(Auth::check()){
            $message = Auth::user()->name . " ha reasignado este ticket a " . $new_agent->name;
        }else if ($old_agent) {
            $message = $old_agent->name . " ha reasignado este ticket a " . $new_agent->name;
        }else {
            $message = "Se ha asignado el agente " . $new_agent->name . " al ticket";
        }


        // Crear la conversación
        $conversion = new Conversion();
        $conversion->ticket_id = $ticket->id;
        $conversion->description = $message;
        $conversion->sender = 'system';
        $conversion->save();

        // Disparar evento Pusher
        $this->triggerPusherEvent($request,$ticket, $conversion);

        $settings = getCompanyAllSettings();
        
        $error_msg = '';
        sendTicketEmail('Send Mail To Agent', $settings, $ticket, $request, $error_msg);
    }

    protected function triggerPusherEvent(Request $request,$ticket, $conversion)
    {
        $pusher = getPusherInstance();
        if($pusher){
            $data = [
                'id' => $conversion->id,
                'ticket_unique_id' => $ticket->id,
                'message' => $conversion->description,
                'timestamp' => \Carbon\Carbon::parse($conversion->created_at)->diffForHumans()
            ];

            // Emitir solo un evento por ticket
            $pusher->trigger("ticket-agent-change-{$ticket->is_assign}", "ticket-agent-change-event-{$ticket->is_assign}", $data);
            if($ticket->is_assign != $ticket->created_by){
                $pusher->trigger("ticket-agent-change-{$ticket->created_by}", "ticket-agent-change-event-{$ticket->created_by}", $data);
            }
            // Mostrar el mensaje en el chat del usuario que cambia el agente
            if($ticket->is_assign != Auth::user()->id && $ticket->created_by != Auth::user()->id){
                $pusher->trigger("ticket-agent-change-" . Auth::user()->id, "ticket-agent-change-event-" . Auth::user()->id, $data);
            }

        }
    }


    public function departmentChange(Request $request, $id)
    {

        $department = $request->department;
        $ticket = Ticket::find($id);
        if ($ticket) {
            $ticket->department_id = $department;
            $ticket->save();
            $data['status'] = 'success';
            $data['message'] = __('Ticket department successfully.');
            return $data;
        } else {
            $data['status'] = 'error';
            $data['message'] = __('Ticket not found');
            return $data;
        }
    }

    


    public function categoryChange(Request $request, $id)
    {

        $category = $request->category;
        $ticket = Ticket::find($id);
        if ($ticket) {

            $ticket->category_id = $category;
            $ticket->save();

            $data['status'] = 'success';
            $data['message'] = __('Ticket category change successfully.');
            return $data;
        } else {
            $data['status'] = 'error';
            $data['message'] = __('Ticket not found');
            return $data;
        }
    }

    public function priorityChange(Request $request, $id)
    {
        $priority = $request->priority;
        $ticket = Ticket::find($id);
        if ($ticket) {

            $ticket->priority = $priority;
            $ticket->save();

            $data['status'] = 'success';
            $data['message'] = __('Ticket priority  change successfully.');
            $data['priority'] = $ticket->getPriority;
            return $data;
        } else {
            $data['status'] = 'error';
            $data['message'] = __('Ticket not found');
            return $data;
        }
    }

    // ticket name change

    public function ticketnameChange(Request $request, $id)
    {
        $ticket = Ticket::find($id);

        if ($ticket) {
            $validation = [
                'name' => 'required|string|max:255',
            ];

            $validator = Validator::make($request->all(), $validation);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first('name')
                ]);
            }

            $ticket->name = $request->name;
            $ticket->save();

            $data['status'] = 'success';
            $data['message'] = __('Ticket name changed successfully.');
            return response()->json($data);
        } else {
            $data['status'] = 'error';
            $data['message'] = __('Ticket not found');
            return response()->json($data);
        }
    }

    // ticket email change
    public function ticketemailChange(Request $request, $id)
    {
        $ticket = Ticket::find($id);

        if ($ticket) {
            $validation = [
                'email' => 'required|string|email|max:255',
            ];

            $validator = Validator::make($request->all(), $validation);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first('email')
                ]);
            }
            $ticket->email = $request->email;
            $ticket->save();
            $data['status'] = 'success';
            $data['message'] = __('Ticket email change successfully.');
            return response()->json($data);
        } else {
            $data['status'] = 'error';
            $data['message'] = __('Ticket not found');
            return response()->json($data);
        }
    }


    // ticket subject change

    public function ticketsubChange(Request $request, $id)
    {
        $ticket = Ticket::find($id);

        if ($ticket) {
            $validation = [
                'subject' => 'required|string|max:255',
            ];

            $validator = Validator::make($request->all(), $validation);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first('subject')
                ]);
            }
            $ticket->subject = $request->subject;
            $ticket->save();
            $data['status'] = 'success';
            $data['message'] = __('Ticket subject change successfully.');
            return response()->json($data);
        } else {
            $data['status'] = 'error';
            $data['message'] = __('Ticket not found');
            return response()->json($data);
        }
    }

    public function readmessge($ticket_id)
    {

        $ticket = Ticket::with('conversions')->find($ticket_id);
        if ($ticket) {
            $conversions = Conversion::where('ticket_id', $ticket_id)->get();
            foreach ($conversions as $conversion) {

                $conversion = Conversion::find($conversion->id);
                $conversion->is_read = 1;
                $conversion->update();
            }
            return true;
        } else {
            $response['status'] = 'error';
            $response['message'] = __('Ticket not found');
            return $response;
        }
    }

    // getMessge

    public function getMessage()
    {
        $cookie_val = json_decode($_COOKIE['ticket_user']);
        $ticket_id = $cookie_val->id;
        $settings = getCompanyAllSettings();
        $my_id = 'user';

        $ticket = Ticket::find($ticket_id);

        if ($ticket) {

            // Make read all unread message
            // Conversion::where(
            //     [
            //         'ticket_id' => $ticket_id,
            //         'sender' => $my_id,
            //     ]
            // )->update(['is_read' => 1]);
            Conversion::where([
                'ticket_id' => $ticket_id,
                'sender' => $my_id,
            ])->latest()->first()?->update(['is_read' => 0]);

            Conversion::where(
                [
                    'ticket_id' => $ticket_id,
                    'sender' => '1',
                ]
            )->update(['is_read' => 1]);


            // Get all message from selected user
            if ($ticket->is_assign == null) {
                $messages = Conversion::where(
                    function ($query) use ($ticket_id, $my_id) {
                        $query->where('ticket_id', $ticket_id)->where('sender', $my_id);
                    }
                )->oRwhere(
                        function ($query) use ($ticket_id, $my_id) {
                            $query->where('ticket_id', $ticket_id)->where('sender', '1');
                        }
                    )->get();
            } else {
                $messages = Conversion::where(function ($query) use ($ticket_id, $my_id) {
                    $query->where('ticket_id', $ticket_id)->where('sender', $my_id);
                })
                    ->orWhere(function ($query) use ($ticket_id, $ticket) {
                        $query->where('ticket_id', $ticket_id)->where('sender', $ticket->is_assign);
                    })
                    ->oRwhere(
                        function ($query) use ($ticket_id, $my_id) {
                            $query->where('ticket_id', $ticket_id)->where('sender', '1');
                        }
                    )
                    ->get();
            }


            return view('admin.chats.floating_message', ['messages' => $messages, 'settings' => $settings, 'ticket' => $ticket]);
        } else {
            return redirect()->back() - with('error', 'Ticket Not found!');
        }
    }

    public function sendFloatingMessage(Request $request)
    {

        $cookie_val = json_decode($_COOKIE['ticket_user']);

        $ticket_id = empty($_COOKIE['ticket_user']) ? 0 : $cookie_val->id;
        $message = $request->message;

        $ticket = Ticket::find($ticket_id);

        if ($ticket) {
            if ($message != null) {
                $conversion = new Conversion();
                $conversion->sender = 'user';
                $conversion->ticket_id = $ticket_id;
                $conversion->description = $message;
                $conversion->is_read = 0;
                $conversion->save();
            }

            if ($ticket) {
                $ticket->status = 'In Progress';
                $ticket->update();
            }
            $settings = getCompanyAllSettings();

            // pusher
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
                    'id' => $conversion->id,
                    'tikcet_id' => $conversion->ticket_id,
                    'ticket_unique_id' => $ticket->id,
                    'new_message' => $conversion->description ?? '',
                    'timestamp' => \Carbon\Carbon::parse($conversion->created_at)->format('d/m/Y, h:ia'),
                    'sender_name' => $conversion->replyBy?->name,
                    'attachments' => json_decode($conversion->attachments),
                    'baseUrl' => env('APP_URL'),
                    'latestMessage' => $ticket->latestMessages($ticket->id),
                    'unreadMessge' => $ticket->unreadMessge($ticket->id)->count(),
                ];


                /*if ($ticket->is_assign == null) {
                    $channel = "ticket-reply-$ticket->created_by";
                    $event = "ticket-reply-event-$ticket->created_by";
                } else {
                    $channel = "ticket-reply-$ticket->is_assign";
                    $event = "ticket-reply-event-$ticket->is_assign";
                }*/
                // Siempre enviar al creador del ticket
                $pusher->trigger("ticket-reply-{$ticket->created_by}", "ticket-reply-event-{$ticket->created_by}", $data);

                // Enviar al agente asignado si existe
                if (!empty($ticket->is_assign)) {
                    $pusher->trigger("ticket-reply-{$ticket->is_assign}", "ticket-reply-event-{$ticket->is_assign}", $data);
                }
                //$pusher->trigger($channel, $event, $data);
            }

            return true;
        }
    }

    public function ticketcustomfield($id)
    {
        if (Auth::user()->isAbleTo('custom field edit')) {
            $ticket = Ticket::find($id);
            if ($ticket) {
                $customFields = CustomField::where('is_core', false)->orderBy('order')->get();
                return view('admin.customFields.conversationformBuilder', compact('ticket', 'customFields'));
            } else {
                return redirect()->back()->with('error', 'Ticket Not Found.');
            }
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function ticketcustomfieldUpdate(Request $request, $ticket_id)
    {
        $ticket = Ticket::find($ticket_id);
        if ($ticket) {
            CustomField::saveData($ticket, $request->customField);
            return response()->json([
                'status' => true,
                'message' => __('Customfield Updated Successfully.')
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => __('Ticket Not Found.')
            ]);
        }
    }
}
