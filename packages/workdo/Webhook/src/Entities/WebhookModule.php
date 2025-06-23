<?php

namespace Workdo\Webhook\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebhookModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'module',
        'submodule'
    ];
}
