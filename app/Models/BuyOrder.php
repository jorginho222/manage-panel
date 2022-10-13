<?php

namespace App\Models;

use App\Models\Supply;
use App\Models\Provider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BuyOrder extends Model
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
        'provider_id',
        'supply_id',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }

    public function supplies()
    {
        return $this->belongsToMany(Supply::class)->withPivot('quantity', 'partial_price');
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

