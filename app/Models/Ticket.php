<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Workdo\Tags\Entities\Tags;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_id',
        'name',
        'email',
        'mobile_no',
        'category_id',
        'priority',
        'subject',
        'status',
        'is_assign',
        'description',
        'created_by',
        'attachments',
        'note',
        'type',
    ];


    public static $statues = [
        'New Ticket',
        'In Progress',
        'On Hold',
        'Closed',
        'Resolved',
    ];

    public function conversions()
    {
        return $this->hasMany('App\Models\Conversion', 'ticket_id', 'id')->orderBy('id');
    }

   /* need to remove this  
    protected $appends = ['responsetime', 'responseTimeconvertinhours'];
    public function getResponsetimeAttribute()
    {

        $ticketCreateTime = strtotime($this->created_at);
        $datetime_1 = date('Y-m-d H:i:s', $ticketCreateTime);

        $coTime = Conversion::where('ticket_id', $this->id)->latest()->first();

        if (empty($coTime)) {
            return "-";
        }

        $coTime = $coTime->created_at;
        $ConversionTime = strtotime($coTime);
        $datetime_2 = date('Y-m-d H:i:s', $ConversionTime);
        $diff = Carbon::createFromFormat('Y-m-d H:i:s', $datetime_2)->diffForHumans($datetime_1);
        return $diff;
    }

    public function getResponseTimeConvertInHoursAttribute()
    {

        // $maxTotalResTime = $this->priorities->policies->response_within;
        $maxTotalResTime = !empty($this->priorities->policies->response_within) ? $this->priorities->policies->response_within : 0;


        $coTime = Conversion::where('ticket_id', $this->id)->latest()->first();

        if (!$coTime) {
            return 'off';
        }

        $coTime = $coTime->created_at;
        $maxTime = null;

        // if ($this->priorities->policies->response_time == 'Minute') {
        //     $maxTime = Carbon::parse($this->created_at)->addMinutes($maxTotalResTime);
        // }
        // if ($this->priorities->policies->response_time == 'Hour') {
        //     $maxTime = Carbon::parse($this->created_at)->addHour($maxTotalResTime);
        // }
        // if ($this->priorities->policies->response_time == 'Day') {
        //     $maxTime = Carbon::parse($this->created_at)->addDays($maxTotalResTime);
        // }
        // if ($this->priorities->policies->response_time == 'Month') {
        //     $maxTime = Carbon::parse($this->created_at)->addMonths($maxTotalResTime);
        // }
        // if($maxTime < $coTime){
        //     return true;
        // }else{
        //     return false;
        // }
    }


    protected $append = ['resolvetime', 'resolveTimeconvertinhours'];

    public function getResolvetimeAttribute()
    {

        $ticketResolveTime = strtotime($this->reslove_at);
        $datetime_1 = date('Y-m-d H:i:s', $ticketResolveTime);

        $ticketCreateTime = strtotime($this->created_at);
        $datetime_2 = date('Y-m-d H:i:s', $ticketCreateTime);


        if ($this->reslove_at == '0000-00-00 00:00:00') {
            return '-';
        }
        $diff = Carbon::createFromFormat('Y-m-d H:i:s', $datetime_1)->diffForHumans($datetime_2);
        return $diff;
    }


    public function getResolveTimeConvertInHoursAttribute()
    {
        $maxTotalResolveTime = !empty($this->priorities->policies->resolve_within) ? $this->priorities->policies->resolve_within : 0;

        $ticketResolveTime = strtotime($this->reslove_at);
        $datetime_1 = date('Y-m-d H:i:s', $ticketResolveTime);
        $maxReTime = null;

        if ($this->priorities->policies->resolve_time == 'Hour') {
            $maxReTime = Carbon::parse($this->created_at)->addHour($maxTotalResolveTime);
        }
        if ($this->priorities->policies->resolve_time == 'Minute') {
            $maxReTime = Carbon::parse($this->created_at)->addMinutes($maxTotalResolveTime);
        }
        if ($this->priorities->policies->resolve_time == 'Day') {
            $maxReTime = Carbon::parse($this->created_at)->addDays($maxTotalResolveTime);
        }

        if ($this->priorities->policies->resolve_time == 'Month') {
            $maxReTime = Carbon::parse($this->created_at)->addMonths($maxTotalResolveTime);
        }


        if ($maxReTime < $datetime_1) {
            return true;
        } else {
            return false;
        }
    }


    public static function Managepriority($priority)
    {
        $priorityArr  = explode(',', $priority);
        $unitRate = 0;

        foreach ($priorityArr as $username) {

            $priority     = Priority::find($username);

            if($priority)
            {
                $unitRate     = $priority->name;
            }
            else {
                $unitRate = '-'; 
                
            }
        }
        return $unitRate;
    } 
    */


    public  function getAgentDetails(){
        return $this->hasOne(User::class, 'id', 'is_assign');
    }
    public function getCategory()
    {
        return $this->hasOne('App\Models\Category', 'id', 'category_id');
    }

    public function getPriority()
    {

        return $this->hasOne('App\Models\Priority', 'id', 'priority');
    }

    public function getTicketCreatedBy(){
        return $this->hasOne(User::class,'id','created_by');
    }

    public static function category($category)
    {
        
        $unitRate = 0;
        $category     = Category::find($category);
        if ($category) {
                $unitRate = $category->name;
            } else {
                $unitRate = '-'; 
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

            $aclose_ticket = Ticket::where('status', '=', 'Closed')->whereDate('created_at', $arrDate[$i])->get();
            $close_ticket[] = count($aclose_ticket);
        }

        $data['open_ticket']    = $open_ticket;
        $data['close_ticket']      = $close_ticket;

        return $data;
    }

    public static function getTicketTypes() {
        $ticketTypes = [
            'Unassigned',
            'Assigned',
        ];
    
        if (moduleIsActive('WhatsAppChatBotAndChat')) {
            $ticketTypes[] = 'Whatsapp';
        }

        if(moduleIsActive('InstagramChat')) {
            $ticketTypes[] = 'Instagram';
        }
        
        if(moduleIsActive('FacebookChat')) {
            $ticketTypes[] = 'Facebook';
        }
    
        return $ticketTypes;
    }

    public function messages(){

        return $this->hasMany(Conversion::class,'ticket_id');
    }

    public function unreadMessge($id)
    {

        return $this->messages()->where('ticket_id',$id)->where('sender','user')->where('is_read',0);
    }


    public function latestMessages($id)
    {
        $conversion = $this->messages()->where('ticket_id', $id)->latest()->first();
        return $conversion ? Str::limit(strip_tags(html_entity_decode($conversion->description)), 30, '...') : '';
    }

    public function getTagsAttribute()
    {
        if(moduleIsActive('Tags')){
        $tagIds = explode(',', $this->tags_id);

        return Tags::whereIn('id', $tagIds)->get();
    }
    }
}
