<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Mail\SendCloseTicket;
use App\Mail\SendTicket;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Ticket;
use App\Models\CustomField;
use App\Models\Conversion;
use App\Mail\SendTicketAdminReply;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    use ApiResponser;

    public function index(Request $request)
    {
        $ticket_query = Ticket::query()->select('tickets.id','tickets.ticket_id','tickets.name','tickets.email','tickets.is_assign','categories.name as category','categories.color as color','tickets.subject','tickets.status','tickets.description', 'tickets.mobile_no' , 'tickets.note','tickets.attachments')->join('categories', 'categories.id', '=', 'tickets.category_id')->get()
        ->map(function ($ticket) {    
            $ticket->attachments = json_decode($ticket->attachments , true) ?? [];
            if (!empty($ticket->attachments)) {
                $ticket->attachments = array_map(function ($attachment) {
                    return checkfile($attachment) ? getfile($attachment) : null; 
                }, $ticket->attachments);
            }
            return $ticket;
        });

        if($request->search){

            $ticket_query->where('name', 'like', "%{$request->search}%")->orWhere('ticket_id','like', "%{$request->search}%");
        }

        if($request->status || $request->period){


            if($request->period == "today"){

                $ticket_query->whereDate( 'created_at', '>', Carbon::now()->subDays(1)->toDateString());
            }

            if($request->period == "week"){
                $ticket_query->whereDate( 'created_at', '>', Carbon::now()->subDays(7)->toDateString());

            }

            if($request->period == "month"){
                $ticket_query->whereDate( 'created_at', '>', Carbon::now()->subDays(30)->toDateString() );
            }


            if($request->period == "progress"){

                $ticket_query->where( 'status', 'In Progress');
            }

            if($request->period == "closed"){
                $ticket_query->where( 'status', 'Closed');

            }

            if($request->period == "hold"){
                $ticket_query->where( 'status' , "On Hold");
            }
        }

        $tickets = $ticket_query->take(10);

        $ticket_in_progress = (clone $ticket_query)->where('status','In Progress')->take(10);
        $ticket_hold        = (clone $ticket_query)->where('status','On Hold')->take(10);
        $ticket_close       = (clone $ticket_query)->where('status','Closed')->take(10);
        $ticket_new         = (clone $ticket_query)->where('status','New Ticket')->take(10);
        $ticket_resolved    = (clone $ticket_query)->where('status','Resolved')->take(10);

        $new_ticket   = Ticket::whereDate('created_at', Carbon::today())->count();
        $open_ticket  = Ticket::whereIn('status', ['On Hold','In Progress'])->count();
        $close_ticket = Ticket::where('status', '=', 'Closed')->count();

        $total_ticket = $new_ticket + $open_ticket + $close_ticket;

        if($total_ticket != 0 )
        {
            $new_ticket   = round((float)((100 * $new_ticket)/$total_ticket));
            $open_ticket  = round((float)((100 * $open_ticket)/$total_ticket));
            $close_ticket = round((float)((100 * $close_ticket)/$total_ticket));
        }

        $statistics = [
            'new_ticket'   => $new_ticket,
            'open_ticket'  => $open_ticket,
            'close_ticket' => $close_ticket,
        ];

        $status = [
            ['status' => 'New Ticket', 'ticket' => array_values($ticket_new->toArray())],
            ['status' => 'In Progress', 'ticket' => array_values($ticket_in_progress->toArray())],
            ['status' => 'On Hold', 'ticket' => array_values($ticket_hold->toArray())],
            ['status' => 'Resolved', 'ticket' => array_values($ticket_resolved->toArray())],
            ['status' => 'Closed', 'ticket' => array_values($ticket_close->toArray())],
        ];

        if(!empty($tickets)){

            // $data = [
            //     'in_progress' => $ticket_in_progress,
            //     'hold'        => $ticket_tickets,
            //     'close'       => $ticket_close,
            //     'ticket'      => $tickets,
            //     'analytics'   => $statistics
            // ];

            $data = [
                'status'      => $status,
                'ticket'      => $tickets,
                'analytics'   => $statistics
            ];

            return $this->success($data);
        }else{
            $data = [
                'ticket'=>[],
            ];
            return $this->error($data , 'Data Not Found' , 200);
        }
    }

    public function store(Request $request)
    {    
        $validation = [
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|max:255',
            'category_id' => 'required|numeric',
            'is_assign'   => 'required|numeric',
            'subject'     => 'required|string|max:255',
            'status'      => 'required',
            'description' => 'required',
            'mobile_no'   => 'nullable|regex:/^\+\d{1,3}\d{9,13}$/',
        ];

        if($request->hasfile('attachments'))
        {
            $validation['attachments.*'] = 'mimes:zip,rar,jpeg,jpg,png,gif,svg,pdf,txt,doc,docx,application/octet-stream,audio/mpeg,mpga,mp3,wav|max:204800';
        }

        $validator = Validator::make(
            $request->all(), $validation
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            $data     = [];
            return $this->error($data , $messages->first() , 200);
        }

        $post               = $request->all();
        $post['ticket_id']  = time();
        $post['created_by'] = Auth::check() ? Auth::user()->id : creatorId();
        $data               = [];

        if ($request->hasfile('attachments')) {
            foreach ($request->file('attachments') as $filekey => $file) {
                $filenameWithExt = $file->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $file->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $dir        = ('tickets/' . $post['ticket_id']);
                $path = multipleFileUpload($file, 'attachments', $fileNameToStore, $dir);
                
                if ($path['flag'] == 1) {
                    $data[] = $path['url'];
                }
            }
        }
        $post['attachments'] = json_encode($data);
        $ticket              = Ticket::create($post);

        CustomField::saveData($ticket, $request->custom_field);

        // Send Email to User
        try
        {
            Mail::to($ticket->email)->send(new SendTicket($ticket));
        }
        catch(\Exception $e)
        {
            $error_msg = "E-Mail has been not sent due to SMTP configuration ";
        }

        // Send Email to
        if(isset($error_msg))
        {
            Session::put('smtp_error', '<span class="text-danger ml-2">' . $error_msg . '</span>');
        }
        Session::put('ticket_id', ' <a class="text text-primary" target="_blank" href="' . route('home.view', \Illuminate\Support\Facades\Crypt::encrypt($ticket->ticket_id)) . '"><b>' . __('Your unique ticket link is this.') . '</b></a>');


        if(!empty($ticket)){
            $data = [
                'ticket' => $ticket,
            ];
            return $this->success($data);
        }else{
            $data = [
                'ticket' => [],
            ];
            return $this->error($data , 'Data Not Found',200);
        }
    }

    public function update(Request $request)
    {        
        $validation = [
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|max:255',
            'category_id' => 'required|numeric',
            'is_assign'   => 'required|numeric',
            'subject'     => 'required|string|max:255',
            'status'      => 'required',
            'description' => 'required',
            'mobile_no'   => 'nullable|regex:/^\+\d{1,3}\d{9,13}$/',
        ];

        if($request->hasfile('attachments'))
        {
            $validation['attachments.*'] = 'mimes:zip,rar,jpeg,jpg,png,gif,svg,pdf,txt,doc,docx,application/octet-stream,audio/mpeg,mpga,mp3,wav|max:204800';
        }

        $validator = Validator::make(
            $request->all(), $validation
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            $data     = [];
            return $this->error($data , $messages->first() , 200);
        }

        $ticket = Ticket::find($request->id);
        if($ticket)
        {
            $post = $request->all();
            $data               = [];
            if ($request->hasfile('attachments')) {
                foreach ($request->file('attachments') as $filekey => $file) {
                    $filenameWithExt = $file->getClientOriginalName();
                    $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension       = $file->getClientOriginalExtension();
                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                    $dir        = ('tickets/' . $ticket->ticket_id);
                    $path = multipleFileUpload($file, 'attachments', $fileNameToStore, $dir);
                    
                    if ($path['flag'] == 1) {
                        $data[] = $path['url'];
                    }
                }
            }
            $post['attachments'] = json_encode($data);
            $ticket->update($post);
            CustomField::saveData($ticket, $request->custom_field);

            $error_msg = '';
            if($ticket->status == 'Closed')
            {
                // Send Email to User
                try
                {
                    Mail::to($ticket->email)->send(new SendCloseTicket($ticket));
                }
                catch(\Exception $e)
                {
                    $error_msg = "E-Mail has been not sent due to SMTP configuration ";
                }
            }

            $data = ['ticket'=>$ticket];

            return $this->success($data);

        }
        else
        {
            $data    = ['ticket'=>$ticket];
            $message = "Ticket does not exist";
            return $this->error($data , $message , 200);
        }
    }

    public function destroy(Request $request)
    {
        $ticket = Ticket::find($request->id);
        if($ticket){

            $ticket->delete();

            $data = [
                'ticket'=>[],
            ];
            return $this->success($data);
        }
        else
        {
            $message = "Ticket does not exist";
            return $this->error([] , $message , 200);
        }
    }

    public function openTicket(Request $request)
    {
        $ticket      = Ticket::find($request->id);
        if($ticket){

            $conversions = $ticket->conversions;

            $conversions_data = [];
            foreach($conversions as $conversion){

                $attachment  = json_decode($conversion->attachments, true);
                $attachments = [];
                if($attachment != null)
                {
                    foreach ($attachment as $key => $value) {
                        $attachments[]= $value;
                    }
                }            

                $conversions_data[]=[
                    'id'            => $conversion->id != null ? $conversion->id :'',
                    'ticket_id'     => $conversion->ticket_id,
                    'description'   => $conversion->description,
                    'attachments'   => $attachments,
                    'email'         => $ticket->email,
                ];
            }

            $ticket=[
                'id'            => $ticket->id != null ? $ticket->id :'',
                'ticket_id'     => $ticket->ticket_id,
                'name'          => $ticket->name,
                'email'         => $ticket->email,
                'mobile_no'     => $ticket->mobile_no,
                'category'      => $ticket->getCategory->name,
                'color'         => $ticket->getCategory->color,
                'subject'       => $ticket->subject,
                'status'        => $ticket->status,
                'description'   => $ticket->description,
                'attachments'   => isset($attachments)  ? $attachments : '',
                'note'          => $ticket->note,
            ];

            $data = [
                'ticket'        => $ticket,
                'conversion'    => $conversions_data,
            ];

            return $this->success($data);
        }
        else{
            $message = "Ticket does not exist";
            return $this->error([] , $message , 200);
        }
    }

    public function replayTicket(Request $request)
    {
        // $user = User::find($request->id);
        $user = Auth::user();
        if($user && $user->isAbleTo('ticket reply')) {
            $ticket = Ticket::find($request->ticket_id);
            if($ticket) {
                $validation = ['reply_description' => ['required']];
                if ($request->hasfile('reply_attachments')) {
                    $validation['reply_attachments.*'] = 'mimes:zip,rar,jpeg,jpg,png,gif,svg,pdf,txt,doc,docx,application/octet-stream,audio/mpeg,mpga,mp3,wav|max:204800';
                }
                $validator = Validator::make(
                    $request->all(), $validation
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    $data     = [];
                    return $this->error($data , $messages->first() , 200);
                }

                $post                = [];
                $post['sender']      = ($user)?$user->id:'user';
                $post['ticket_id']   = $ticket->id;
                $post['description'] = $request->reply_description;

                $data = [];
                if ($request->hasfile('reply_attachments')) {
                    foreach ($request->file('reply_attachments') as $file) {
                        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $ext = $file->getClientOriginalExtension();
                        $filenameToStore = $fileName . '_' . time() . '.' . $ext;
        
                        $dir = 'tickets/' . $post['ticket_id'];
                        $path = multipleFileUpload($file, 'reply_attachments', $filenameToStore, $dir);
        
                        if ($path['flag'] == 1) {
                            $data[] = $path['url'];
                        }
                    }
                }
                $post['attachments'] = json_encode($data);

                $conversion = Conversion::create($post);

                // Send Email to User
                try {
                    Mail::to($ticket->email)->send(new SendTicketAdminReply($ticket,$conversion));
                }catch (\Exception $e){
                    $error_msg = "E-Mail has been not sent due to SMTP configuration ";
                }

                $data = [
                    'replay'=>$conversion,
                ];

                return $this->success($data);
            }else{
                $message = "Ticket does not exist";
                return $this->error([] , $message , 200);
            }
        }else{
            $message = "User does not exist";
            return $this->error([] , $message , 200);
        }

        $data = [
            'ticket'=>$ticket,
        ];

        return $this->success($data);
    }

    public function ticketStatus()
    {
        $status = Ticket::$statues;

        $data = [
            'status' => $status
        ];

        return $this->success($data);
    }
}
