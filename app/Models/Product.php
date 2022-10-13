<?php

namespace App\Models;

use App\Models\SellOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'trademark',
        'specifications',
        'price',
        'stock',
        'sells',
    ];

    public function sellOrders()
    {
        return $this->belongsToMany(SellOrder::class)->withPivot('quantity', 'partial_price');
    }
}
