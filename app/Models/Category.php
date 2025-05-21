<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name', 'color','created_by' , 'parent_id'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'category_id');
    }

    public function getTickets()
    {
        return $this->hasMany(Ticket::class,'category_id' ,'id');
    }


}
