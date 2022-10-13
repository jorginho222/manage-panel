<?php

namespace App\Models;

use App\Models\Client;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SellOrder extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'status',
        'shipping_cost',
        'total_price',
        'client_id',
        'product_id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'partial_price');
    }

    public function getAvailableStatusAttribute()
    {
        $availableStatus = [
            1 => 'Pendiente',
            2 => 'Enviada',
            3 => 'Pagada',
            4 => 'Producto Enviado',
            5 => 'Producto Recibido',
        ];

        return $availableStatus;
    }
}
