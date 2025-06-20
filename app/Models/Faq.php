<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'title', 'description'
    ];
}
