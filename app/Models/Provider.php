<?php

namespace App\Models;

use App\Models\Supply;
use App\Models\BuyOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Provider extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'company_name',
        'about',
        'email',
        'phone',
        'location',
        'address',
    ];

    public function supplies()
    {
        return $this->hasMany(Supply::class, 'provider_id');
    }

    public function buyOrders()
    {
        return $this->hasMany(BuyOrder::class, 'provider_id');
    }
}
