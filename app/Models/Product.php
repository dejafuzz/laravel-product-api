<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'price',
        'description',
        'category',
        'images',
        'created_by',
        'created_by_id',
        'updated_at',
        'updated_by_id',
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'float'
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }
}