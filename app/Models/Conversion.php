<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversion extends Model
{
    protected $fillable = [
        'ticket_id','description', 'attachments', 'sender'
    ];

    /*public function replyBy(){
        if($this->sender=='user'){
            return $this->ticket;
        }else if($this->sender=='system'){
            return $this->ticket;
        }
        else{
            return $this->hasOne('App\Models\User','id','sender')->first();
        }
    }*/
    /*public function replyBy()
    {
        if (in_array($this->sender, ['user', 'system'])) {
            return $this->ticket;
        }

        return $this->hasOne(\App\Models\User::class, 'id', 'sender')->first();
    }*/

    public function replyBy()
    {
        // Devuelve siempre una relación, no un resultado
        if (in_array($this->sender, ['user', 'system'])) {
            return $this->belongsTo(Ticket::class, 'ticket_id');
        }
        return $this->belongsTo(User::class, 'sender');
    }
    public function getReplyByRoleName()
    {
        $replyBy = $this->replyBy;
        if ($replyBy instanceof \App\Models\User) {
            return $replyBy->roles->first()->display_name ?? '';
        }
        return '';
    }

    public function ticket(){
        return $this->hasOne('App\Models\Ticket','id','ticket_id');
    }


    public  static function change_status($ticket_id)
    {
        $ticket = Ticket::find($ticket_id);
        $ticket->status = 'In Progress';
        $ticket->update();
        return $ticket;
    }
}
