<?php

namespace Workdo\Tags\Entities;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tags extends Model
{

    use HasFactory,BelongsToCompany;

    protected $fillable = [
        'name',
        'color',
        'created_by',
        'company_id'
    ];
}
