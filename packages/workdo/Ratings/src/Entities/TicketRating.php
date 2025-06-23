<?php

namespace Workdo\Ratings\Entities;

use App\Models\Ticket;
use App\Models\User;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketRating extends Model
{
    use HasFactory,BelongsToCompany;

    protected $fillable = [
        'ticket_id',
        'customer',
        'user_id',
        'rating_date',
        'rating',
        'description',
        'created_by',
        'company_id'

    ];

    public  function getAgentDetails(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public  function getTicketDetails(){
        return $this->hasOne(Ticket::class, 'id', 'ticket_id');
    }
}
