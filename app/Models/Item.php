<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Item_Condition;
use App\Models\User;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'brand',
        'price',
        'image_url',
        'condition_id',
        'seller_id',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function itemCondition()
    {
        return $this->belongsTo(Item_Condition::class, 'condition_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function isFavoritedByAuthUser()
    {
        if (!auth()->check()) {
            return false;
        }

        return $this->favorites()->where('user_id', auth()->id())->exists();
    }
}
