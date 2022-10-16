<?php

namespace App\Http\Livewire\Products;

use Livewire\Component;
use App\Http\Traits\OrderArticlesTrait;

class OrderProducts extends Component
{
    use OrderArticlesTrait;

    public $orderType = 'Sell';

    public $search;

    protected $listeners = ['passOrderData', 'resetArticles'];

    public function render()
    {
        return view('livewire.products.order-products');
    }
}
