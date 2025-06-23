<?php

namespace Workdo\Webhook\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Webhook extends Model
{
    use HasFactory;

    protected $fillable = [
        'module',
        'method',
        'action',
        'url',
        'created_by'
    ];

    public function module(){
        return $this->hasOne(WebhookModule::class,'id','action');
    }
}
