<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

use App\Models\Category;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_id',
        'name',
        'email',
        'category',
        'subject',
        'status',
        'description',
        'attachments',
        'note',
    ];

    protected $appends = ["color"];

    public function getColorAttribute()
    {
       
        $category = Category::find($this->attributes['category']);
        $category = (!empty($category->color)) ? $category->color : '';

        return $category;
    }

    public function getCategoryAttribute()
    {
        $category = Category::find($this->attributes['category']);
        $category = (!empty($category->name)) ? $category->name : '';

        return $category;
    }

    public function getAttachmentsAttribute()
    {
        $attachments = $this->attributes['attachments'];
        $attachment = json_decode($attachments, true);
            $attachments_arr=[];
            foreach ($attachment as $key => $value) {
                    $attachments_arr[]=$value;
            }
        return $attachments_arr;
    }

    // public static function latestTicket()
    // {
    //     $latest_tickets = Ticket::orderBy('id', 'desc')->take(5)->get();

    //     $tickets=[];
    //     foreach($latest_tickets as $ticket){
            
    //         if($ticket->attachments){

    //             $attachment = json_decode($ticket->attachments, true);
                
    //             $attachments=[];
    //             foreach ($attachment as $key => $value) {
    //                 $attachments[]=$value;
    //             }
        
    //         }

    //         $tickets[]=[
    //             'id'            => $ticket->id != null ? $ticket->id :'',
    //             'ticket_id'     => $ticket->ticket_id,
    //             'name'          => $ticket->name,
    //             'email'         => $ticket->email,
    //             'category'      => $ticket->tcategory->name,
    //             'color'         => $ticket->tcategory->color,
    //             'subject'       => $ticket->subject,
    //             'status'        => $ticket->status,
    //             'description'   => $ticket->description,
    //             'attachments'   => $attachments,
    //             'note'          => $ticket->note,
    //         ];
    //     }

    //     return $tickets;
    // }

    public static function Ticket($data)
    {

    // if($data['search']){
        //     $search = $data['search'];
            
        //     $ticket_query->where('name', 'like', "%$search%")->orwhere('ticket_id', 'like', "%$search%");
        // }

        // if($data['status'] && $data['period']){

        //     if($data['status']){
        //         $status = "";
        //     }

        //     if($data['status']){
        //         $status = "";
        //     }

        //     if($data['status']){
        //         $status = "";
        //     }

        //     if($data['period'] == 'today'){

                
        //     }

        //     if($data['period'] == 'week'){
        //         $previous_week = strtotime("-1 week +1 day");
        //         $start_week = strtotime("last sunday midnight",$previous_week);
        //         $end_week = strtotime("next saturday",$start_week);
        //         $startDate = date("Y-m-d",$start_week);
        //         $endDate = date("Y-m-d",$end_week);
            
        //     }

        //     if($data['period'] == 'month'){

        //         $startDate=1;
        //         $endDate=1;
        //     }

        //     $ticket_query->where('status', $data['status'])->whereBetween('created_at', [$startDate, $endDate]);

        // }

       
        // else{
        // }
        // $ticket_query->orderBy('id', 'desc');   
     

        return $tickets;
    }



    public function conversions()
    {
        return $this->hasMany('App\Models\Api\Conversion', 'ticket_id', 'id')->orderBy('id');
    }

    public function tcategory()
    {
        return $this->hasOne('App\Models\Category', 'id', 'category');
    }

    public static function category($category)
    {
        $categoryArr  = explode(',', $category);
        $unitRate = 0;
        foreach($categoryArr as $username)
        {
            $category     = Category::find($category);
            $unitRate     = $category->name;
        }
        return $unitRate;
    }
    
    
    public static function getIncExpLineChartDate()
    {
      
        $m             = date("m");
        $de            = date("d");
        $y             = date("Y");
        $format        = 'Y-m-d';
        $arrDate       = [];
        $arrDateFormat = [];

        for($i = 7; $i >= 0; $i--)
        {
            $date = date($format, mktime(0, 0, 0, $m, ($de - $i), $y));

            $arrDay[]        = date('D', mktime(0, 0, 0, $m, ($de - $i), $y));
            $arrDate[]       = $date;
            $arrDateFormat[] = date("d", strtotime($date)) .'-'.__(date("M", strtotime($date)));
        }
        $data['day'] = $arrDateFormat;

        $open_ticket = array();
        $close_ticket = array();

        for($i = 0; $i < count($arrDate); $i++)
        {

            $aopen_ticket = Ticket::whereIn('status', ['On Hold','In Progress'])->whereDate('created_at', $arrDate[$i])->get();
            $open_ticket[] =  count($aopen_ticket);
            // unset($open_ticket);

            $aclose_ticket = Ticket::where('status', '=', 'Closed')->whereDate('created_at', $arrDate[$i])->get();
            $close_ticket[] = count($aclose_ticket);

            // unset($close_ticket);
        }

        $data['open_ticket']    = $open_ticket;
        $data['close_ticket']      = $close_ticket;

        return $data;
    }
}
