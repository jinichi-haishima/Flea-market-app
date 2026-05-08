<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'brand',
        'price',
        'image_url',
        'category_id',
        'condition_id',
        'seller_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function condition()
    {
        return $this->belongsTo(ItemCondition::class, 'condition_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
