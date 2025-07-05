<?php

namespace App\Http\Controllers;

use App\Constants\CustomFieldsConstants;
use App\Events\CreateTicket;
use App\Events\CreateTicketFrontend;
use App\Events\TicketReply;
use App\Events\VerifyReCaptchaToken;
use App\Models\Category;
use App\Models\Conversion;
use App\Models\CustomField;
use App\Models\Faq;
use App\Mail\SendTicket;
use App\Models\UserCatgory;
use App\Mail\SendTicketAdmin;
use App\Mail\SendTicketReply;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Knowledge;
use App\Models\Knowledgebasecategory;
use App\Models\Languages;
use App\Models\Utility;
use App\Models\Settings;
use App\Models\Priority;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Pusher\Pusher;
use Exception;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    private $language;
    public function __construct()
    {
        // $this->middleware('2fa');

        if (!file_exists(storage_path() . "/installed")) {
            return redirect('install');
        }
        if (moduleIsActive('CustomerLogin')) {
            $this->middleware('CustomerLogin')->only(['index']);
        }


        $language = getActiveLanguage();
        App::setLocale(isset($language) ? $language : 'en');
    }
    public function index()
    {

        $this->middleware('2fa');

        if (!file_exists(storage_path() . "/installed")) {
            return redirect('install');
        }

        $customFields = CustomField::publicForm()->orderBy('order')->get();
        $categories = Category::get();
        $categoryTree = buildCategoryTree($categories);
        $priorities = Priority::get();

        $settings = getCompanyAllSettings();
        $language = isset($settings['default_language']) ? $settings['default_language'] : 'en';
        Session::put('default_language', $language);
        $ticket = null;
        return view('home', compact('categoryTree', 'customFields', 'settings', 'priorities', 'ticket'));
    }


    public function search($lang = '')
    {
        $settings = getCompanyAllSettings();
        if ($lang == '') {
            $lang = getActiveLanguage();
        } else {
            $lang = array_key_exists($lang, languages()) ? $lang : 'en';
        }
        $language = Languages::where('code', $lang)->first();
        App::setLocale($lang);
        return view('search', compact('settings', 'lang', 'language'));
    }

    public function faq()
    {
        $settings = getCompanyAllSettings();
        if ($settings['faq'] == 'on') {
            $faqs = Faq::get();
            return view('faq', compact('faqs', 'settings'));
        } else {
            return redirect('/');
        }
    }

    public function ticketSearch(Request $request)
    {
        $validation = [
            'ticket_id' => ['required'],
            'email' => ['required'],
        ];

        $this->validate($request, $validation);
        if (moduleIsActive('TicketNumber')) {
            $settings = getCompanyAllSettings();
            $ticketPrefix = $settings["ticket_number_prefix"] ?? '';
            $ticketId = str_replace($ticketPrefix, '', $request->ticket_id); // Prefix remove karo
            $ticketId = ltrim($ticketId, '0');
            $ticket_id = Ticket::where('id', $ticketId)->where('email', $request->email)->first();
            if (!$ticket_id) {
                return redirect()->back()->with('info', __('Ticket not found'));
            }
            $ticket = Ticket::where('ticket_id', '=', $ticket_id->ticket_id)->where('email', '=', $ticket_id->email)->first();
        } else {
            $ticket = Ticket::where('ticket_id', '=', $request->ticket_id)->where('email', '=', $request->email)->first();
        }
        if ($ticket) {
            return redirect()->route('home.view', Crypt::encrypt($ticket->ticket_id));
        } else {
            return redirect()->back()->with('info', __('Invalid Ticket Number'));
        }

        return view('search');
    }


    public function store(Request $request)
    {
        $settings = getCompanyAllSettings();
        if ($request->type == 'Ticket') {
            $validation = [
                'name' => 'required',
                'email' => 'required|email',
                'category' => 'required',
                'subject' => 'required',
                'status' => 'required',
                'description' => 'required',
                'priority' => 'required',
            ];

            $validation = [];
            if (isset($settings['RECAPTCHA_MODULE']) && $settings['RECAPTCHA_MODULE'] == 'yes') {
                if ($settings['google_recaptcha_version'] == 'v2-checkbox') {
                    $validation['g-recaptcha-response'] = 'required';
                } elseif ($settings['google_recaptcha_version'] == 'v3') {


                    $re = event(new VerifyReCaptchaToken($request));
                    if (!isset($re[0]['status']) || $re[0]['status'] != true) {
                        $key = 'g-recaptcha-response';
                        $request->merge([$key => null]); // Set the key to null

                        $validation['g-recaptcha-response'] = 'required';
                    }
                } else {
                    $validation = [];
                }
            } else {
                $validation = [];
            }

            // ---------------------------------------------------------------------------------

            $this->validate($request, $validation);
            $company = app('currentCompany');

            $ticket = new Ticket();
            $ticket->ticket_id = time();
            $ticket->name = $request->name;
            $ticket->email = $request->email;
            $ticket->category_id = $request->category;
            $ticket->priority = $request->priority;
            $ticket->subject = $request->subject;
            $ticket->company_id = $company->id ?? null;
            $ticket->status = "New Ticket";
            $ticket->type = "Unassigned";
            $ticket->description = $request->description;
            $ticket->created_by = User::where('type', 'admin')->first()->id ?? 0; // Default to 0 if no admin user found
            $data = [];
            if ($request->hasfile('attachments')) {
                $errors = [];
                foreach ($request->file('attachments') as $filekey => $file) {
                    $fileNameWithExt = $file->getClientOriginalName();
                    $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                    $extention = $file->getClientOriginalExtension();
                    $filenameToStore = $fileName . '_' . time() . '.' . $extention;
                    $dir = ('tickets/' . $ticket->ticket_id);
                    $path = multipleFileUpload($file, 'attachments', $filenameToStore, $dir);
                    if ($path['flag'] == 1) {
                        $data[] = $path['url'];
                    } elseif ($path['flag'] == 0) {
                        $errors = __($path['msg']);
                        return redirect()->back()->with('error', __($errors));
                    }
                }
            }
            $ticket->attachments = json_encode($data);
            $ticket->save();

            // Preparamos los datos para los campos personalizados
            $customFieldsData = $request->customField ?? [];

            // Buscamos el campo 'tipoUsuario' en la tabla custom_fields
            $tipoUsuarioField = CustomField::where('name', CustomFieldsConstants::TIPO_USUARIO)->first();
            
            // Si existe el campo, agregamos el valor "Externo"
            if ($tipoUsuarioField) {
                $customFieldsData[$tipoUsuarioField->id] = 'Externo';
            }

            CustomField::saveData($ticket, $customFieldsData);
            event(new CreateTicket($ticket, $request));


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
                    'id' => $ticket->id,
                    'tikcet_id' => $ticket->ticket_id,
                    'name' => $ticket->name,
                    'subject' => $ticket->subject,
                    'status' => $ticket->status,
                    'created_at' => $ticket->created_at->diffForHumans(),
                    'latestMessage' => $ticket->latestMessages($ticket->id),
                    'unreadMessge' => $ticket->unreadMessge($ticket->id)->count(),
                    'type' => $ticket->type,

                ];
            
                $channel = "new-ticket-1";
                $event = "new-ticket-event-1";
                $pusher->trigger($channel, $event, $data);
            }



            $error_msg = '';

            // send Email To The Customer
            sendTicketEmail('Send Mail To Customer', $settings, $ticket, $request, $error_msg);

            //Send Email To The Admin
            sendTicketEmail('Send Mail To Admin', $settings, $ticket, $request, $error_msg);
            sendTicketEmail('Send Mail To Creator', $settings, $ticket, $request, $error_msg);
            sendTicketEmail('New External Ticket', $settings, $ticket, $request, $error_msg);
            

            return redirect()->back()->with('create_ticket', __('Ticket created successfully') . ' <a href="' . route('home.view', Crypt::encrypt($ticket->ticket_id)) . '" target="_blank"><b>' . __('Your unique ticket link is this.') . '</b></a> ' . ((isset($error_msg)) ? '<br> <span class="text-danger">' . $error_msg . '</span>' : ''));
        } else {

            $validation = [
                'name' => 'required|string|max:255',
                'name' => 'required',
                'email' => 'required|email',
                'subject' => 'required',
                'description' => 'required',
            ];

            $validator = Validator::make($request->all(), $validation);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ]);
            }
            $company = app('currentCompany');

            $ticket = new Ticket();
            $ticket->ticket_id = time();
            $ticket->name = $request->name;
            $ticket->email = $request->email;
            $ticket->subject = $request->subject;
            $ticket->company_id = $company->id ?? null;
            $ticket->status = "New Ticket";
            $ticket->type = "Unassigned";
            $ticket->description = $request->description;
            $ticket->attachments = json_encode([]);
            $ticket->created_by = User::where('type', 'admin')->first()->id ?? 0; // Default to 0 if no admin user found
            $ticket->save();

            event(new CreateTicket($ticket, $request));


            $data = [
                'id' => $ticket->id,
                'tikcet_id' => $ticket->ticket_id,
                'name' => $ticket->name,
                'subject' => $ticket->subject,
                'status' => $ticket->status,
                'created_at' => $ticket->created_at->diffForHumans(),
                'latestMessage' => $ticket->latestMessages($ticket->id),
                'unreadMessge' => $ticket->unreadMessge($ticket->id)->count(),
                'type' => $ticket->type,
            ];

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

                $channel = "new-ticket-1";
                $event = "new-ticket-event-1";
                $pusher->trigger($channel, $event, $data);
            }

            $error_msg = '';

            // send Email To The Customer
            sendTicketEmail('Send Mail To Customer', $settings, $ticket, $request, $error_msg);

            //Send Email To The Admin
            sendTicketEmail('Send Mail To Admin', $settings, $ticket, $request, $error_msg);
            sendTicketEmail('Send Mail To Creator', $settings, $ticket, $request, $error_msg);
            sendTicketEmail('New External Ticket', $settings, $ticket, $request, $error_msg);

            $data['status'] = 'success';
            $data['message'] = __('Ticket Create Successfully');
            return $data;
        }
    }

    public function view($ticket_id)
    {
        try {
            $ticket_id = decrypt($ticket_id);
            $ticket = Ticket::with('conversions','getCategory', 'getPriority', 'getTicketCreatedBy','getDepartment','getAgentDetails')->where('ticket_id', '=', $ticket_id)->first();
            $settings = getCompanyAllSettings();
            $encrypt_id_agent = request()->query('id_agent', null); // null si no viene
            $decrypt_id_agent = null;
            if($encrypt_id_agent){
                $decrypt_id_agent = decrypt($encrypt_id_agent);            
            }
            $users = User::where('type', 'agent')->get();

            if ($ticket) {
                return view('show', compact('ticket', 'settings','decrypt_id_agent','users'));
            } else {
                return redirect()->back()->with('error', __('Ticket Not Found.'));
            }
        } catch (\Throwable $th) {
            return redirect()->back();
        }
    }

    public function reply(Request $request, $ticket_id)
    {
        $rules = [
            'reply_description' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $message = $validator->getMessageBag();
            return redirect()->back()->with('error', $message->first())->withInput();
        }

        $ticket_id = decrypt($ticket_id);
        $ticket = Ticket::where('ticket_id', '=', $ticket_id)->first();
        if ($ticket) {

            $id_agent = $request->get('id_agent', null); // null si no viene
                                 
            // determinar sender según id_agent
            $sender = 'user';
            if (!empty($id_agent)) {
                $sender = $id_agent ?? 'user';
            }

            $summernoteContent = $request->reply_description;
            if (!empty($summernoteContent) || $request->hasfile('reply_attachments')) {
                $conversion = new Conversion();
                $conversion->ticket_id = $ticket->id;
                $conversion->description = $summernoteContent;
                $conversion->sender = $sender;

                if ($request->hasfile('reply_attachments')) {
                    foreach ($request->file('reply_attachments') as $filekey => $file) {
                        $fileNameWithExt = $file->getClientOriginalName();
                        $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                        $extention = $file->getClientOriginalExtension();
                        $filenameToStore = $fileName . '_' . time() . '.' . $extention;
                        $dir = ('tickets/' . $ticket->ticket_id);
                        $path = multipleFileUpload($file, 'reply_attachments', $filenameToStore, $dir);

                        if ($path['flag'] == 1) {
                            $data[] = $path['url'];
                        } elseif ($path['flag'] == 0) {
                            return redirect()->back()->with('error', __($path['msg']));
                        }
                    }
                    $conversion->attachments = json_encode($data);
                }
                $conversion->save();


                if ($ticket) {
                    $ticket->status = 'In Progress';
                    $ticket->update();
                }

                event(new TicketReply($conversion, $request));
                
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
                    //ENVIAR EVENTO A COVERSACION INTERNA
                    $data = [
                        'id' => $conversion->id,
                        'tikcet_id' => $conversion->ticket_id,
                        'ticket_email' => $conversion->ticket->email,
                        'ticket_unique_id' => $ticket->id,
                        'new_message' => $conversion->description ?? '',
                        'timestamp' => \Carbon\Carbon::parse($conversion->created_at)->format('d/m/Y, h:ia'),
                        'sender_name' => $conversion->replyBy?->name ?? '',
                        'sender_email' => $conversion->replyBy?->email ?? '',
                        'sender' => $conversion->sender,
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
                    if (!empty($ticket->is_assign) && $ticket->is_assign != $ticket->created_by ) {
                        $pusher->trigger("ticket-reply-{$ticket->is_assign}", "ticket-reply-event-{$ticket->is_assign}", $data);
                    }
                    //$pusher->trigger($channel, $event, $data);


                    //ENVIAR EVENTO A LA VISTA DE CONVERSACION PUBLICA
                    $data = [
                        'converstation' => $conversion,
                        'replyByRole' => $conversion->replyBy->type,
                        'id' => $conversion->id,
                        'ticket_id' => $conversion->ticket_id,
                        'ticket_number' => $ticket->ticket_id,
                        'new_message' => $conversion->description ?? '',
                        'sender_name' => $conversion->replyBy?->name,
                        'attachments' => json_decode($conversion->attachments),
                        'timestamp' =>$conversion->created_at->diffForHumans(),
                        'baseUrl' => env('APP_URL'),
                    ];
                    $channel = "ticket-reply-send-$ticket->ticket_id";
                    $event = "ticket-reply-send-event-$ticket->ticket_id";
                    if (strlen(json_encode($data)) > 10240) {
                        Log::warning('Pusher payload too large for ticket: ' . $ticket->ticket_id);
                    } else {
                        $pusher->trigger($channel, $event, $data);
                    }

                }

                $request->merge(['type' => 'frontend']);
                $sender_name = $conversion->replyBy?->name;
                $request->merge(['sender_name' => $sender_name]);
                

                // Send Emails
                $error_msg = '';
                
                if($id_agent != $ticket->is_assign){
                    sendTicketEmail('Reply Mail To Agent', $settings, $ticket, $request, $error_msg);
                }
                if($id_agent != $ticket->created_by){
                    sendTicketEmail('Reply Mail To Creator', $settings, $ticket, $request, $error_msg);
                }

                if($sender!='user'){
                    sendTicketEmail('Reply Mail To Customer', $settings, $ticket, $request, $error_msg);
                }

                sendTicketEmail('Reply Mail To Admin', $settings, $ticket, $request, $error_msg);

                return redirect()->back()->with('success', __('Reply Added Successfully'));
            } else {
                return redirect()->back()->with('error', __('Please add a description or attachment.'));
            }
        } else {
            return redirect()->back()->with('error', __('Ticket Not Found.'));
        }
    }

    public function knowledge(Request $request)
    {
        $settings = getCompanyAllSettings();
        if (isset($settings['knowledge_base']) && $settings['knowledge_base'] == 'on') {
            $knowledgeBaseCategory = Knowledgebasecategory::with('knowledgebase')->orderBy('id', 'desc')->get();
            $knowledgeBase = Knowledge::with('getCategoryInfo')->get();

            return view('knowledge', compact('knowledgeBaseCategory', 'knowledgeBase', 'settings'));
        } else {
            return redirect('/');
        }
    }

    public function knowledgeDescription(Request $request, $knowledgebaseId)
    {
        try {
            $knowledgebaseId = decrypt($knowledgebaseId);
            $descriptions = knowledge::where('id', $knowledgebaseId)->first();
            if ($descriptions) {
                $settings = getCompanyAllSettings();
                return view('knowledgedesc', compact('descriptions', 'settings'));
            } else {
                return redirect()->route('knowledge')->with('error', 'Knowledgebase Not Found.');
            }
        } catch (Exception $e) {
            return redirect()->back();
        }
    }
}
