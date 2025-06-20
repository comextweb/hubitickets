<?php

namespace Workdo\WhatsAppChatBotAndChat\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserState extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'customer_name',
        'email',
        'user_mobile',
        'state',
        'subject',
        'category_id',
        'description'
    ];
}
