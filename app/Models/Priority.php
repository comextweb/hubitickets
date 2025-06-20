<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{
    use HasFactory, BelongsToCompany;
    protected $fillable = [
        'name',
        'color',
        'created_by'
    ];

    public function getAllTickets()
    {
        return $this->hasMany(Ticket::class, 'priority', 'id');
    }
}
