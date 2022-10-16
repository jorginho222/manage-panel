<?php

namespace App\Http\Livewire\Supplies;

use App\Http\Traits\OrderArticlesTrait;
use App\Models\Supply;
use Livewire\Component;

class OrderSupplies extends Component
{
    use OrderArticlesTrait;

    public $orderType = 'Buy';

    protected $listeners = ['passOrderData', 'resetArticles'];

    public function render()
    {
        return view('livewire.supplies.order-supplies');
    }
}
