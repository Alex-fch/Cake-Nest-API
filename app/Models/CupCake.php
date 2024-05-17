<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CupCake extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'quantity',
        'is_available',
        'is_advertised',
        'price'
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class)->using(CupcakeOrder::class)->withPivot('price', 'quantity');
    }
}
