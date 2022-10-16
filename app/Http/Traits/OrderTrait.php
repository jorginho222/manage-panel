<?php

namespace App\Http\Traits;

trait OrderTrait
{

    public function updatedShippingCost()
    {
        // Sumando el precio de envio
        if ($this->shippingCost == "") {
            $this->shippingCost = 0;
        }
        
        $this->totalPrice = $this->partialPrice + $this->shippingCost;
    }
}