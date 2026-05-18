<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item_condition extends Model
{
    use HasFactory;

    protected $fillable = [
        'condition',
    ];

    public function items()
    {
        return $this->hasMany(Item::class, 'condition_id');
    }
}
