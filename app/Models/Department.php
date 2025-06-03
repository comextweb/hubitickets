<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Department extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
        'manager_id',
        'created_by'
    ];

    // Relación muchos-a-muchos con usuarios
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
                   ->withTimestamps();
    }

    // Relación con el manager del departamento
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    // Relación con el creador del departamento
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}