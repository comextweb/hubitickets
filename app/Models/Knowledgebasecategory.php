<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Knowledgebasecategory extends Model
{
    use HasFactory, BelongsToCompany;

    protected $table = 'knowledge_base_category';
    protected $fillable = [
        'title'
    ];

    public function knowledgebase()
    {
        return $this->hasMany(Knowledge::class, 'category', 'id');
    }
}
