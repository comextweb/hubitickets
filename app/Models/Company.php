<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'ruc',
        'phone',
        'is_active',
        'created_by',
        'slug',
        'config'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'config' => 'json'
    ];

    public function getRouteKeyName()
    {
        return 'subdomain';
    }
}