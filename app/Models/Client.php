<?php

namespace App\Models;

use App\Models\SellOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'identifier_number',
        'email',
        'phone',
        'location',
        'address',
        'interests',
    ];

    public function getIdTypeAttribute()
    {
        if ($this->type === 'Persona') {
            return 'CUIL';
        }
        if ($this->type === 'Empresa') {
            return 'CUIT';
        }
    }

    public function getIdNumberAttribute()
    {
        return substr($this->identifier_number, 0, 2) . '-' . substr($this->identifier_number, 2, 8) . '-' . substr($this->identifier_number, 10);
    }

    public function sellOrders()
    {
        return $this->hasMany(SellOrder::class, 'client_id');
    }

}
