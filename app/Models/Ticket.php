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
