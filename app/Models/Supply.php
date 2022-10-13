<?php

namespace App\Models;

use App\Models\BuyOrder;
use App\Models\Provider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supply extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'title',
        'trademark',
        'price',
        'provider_id'
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }

    public function buyOrders()
    {
        return $this->belongsToMany(BuyOrder::class, 'supply_id')->withPivot('quantity', 'partial_price');
    }
}
