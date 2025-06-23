<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Laratrust\Models\Role as RoleModel;

class Role extends RoleModel
{
    use BelongsToCompany;

    public $guarded = [];

    protected $fillable = [
        'name',
        'created_by'
    ];
}
