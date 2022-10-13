<?php

namespace App\Http\Livewire\Supplies;

use App\Models\Supply;
use Livewire\Component;

class OrderSupplies extends Component
{
    public $showNewSupplyButton = true;
    public $enableActions = true;
    public $showSupplySelector = false;
    public $showQuantityPrice = false;
    public $showOptions = false;
    public $showSupplyList = false;
    public $showActions = true;
    public $showUpdateButton = false;
    public $showFinalizeOrderButton = false;
    public $enableFinalizeOrderButton = true;

    public $supplies;
    public $supplyId;
    public $supplyTitle;
    public $quantity = 1;
    public $price;

    public $supplyList = [];
    public $supplyListKey;

    public $partialPrice;
    public $orderData = [];

    public $totalSuppliesPrice;

    // public $disableSelectQuantity = true;

    protected $listeners = ['getSupplyData','passOrderData', 'resetSupplies'];

    public function mount($supplies)
    {
        $this->supplies = $supplies;
    }

    public function newSupply()
    {
        $this->showNewSupplyButton = false;
        $this->enableFinalizeOrderButton = false;
        $this->enableActions = false;
        $this->showSupplySelector = true;
    }

    public function getSupplyData()
    {
        if ($this->supplyId != null) {
            $supply = Supply::findOrFail($this->supplyId);
            $this->supplyTitle = $supply->title;
            $this->price = $supply->price;
            $this->partialPrice = $this->price;
            $this->reset('quantity');
            
            $this->showQuantityPrice = true;
            $this->showOptions = true;
            // $this->showSupplyData = true;
        } else {
            $this->showQuantityPrice = false;
            $this->showOptions = false;
        }
    }

    public function moreQuantity()
    {
        $this->quantity ++;
        $this->partialPrice = $this->price * $this->quantity;
    }
    public function lessQuantity()
    {
        $this->quantity --;
        $this->partialPrice = $this->price * $this->quantity;
    }
    
    public function addSupply()
    {
        $this->supplyList[] = $this->modelData();  
        $this->setTotalPrice();
        $this->closeSupplyForm();
        $this->showSupplyList = true;
        $this->showFinalizeOrderButton = true;
    }

    public function editSupply($key)
    {
        $this->supplyListKey = $key;
        $supply = $this->supplyList[$key];

        $this->supplyId = $supply['id'];
        $this->supplyTitle = $supply['title'];
        $this->price = $supply['price'];
        $this->quantity = $supply['quantity'];
        $this->partialPrice = $supply['partialPrice'];

        $this->enableFinalizeOrderButton = false;
        $this->enableActions = false;
        $this->showNewSupplyButton = false;
        $this->showSupplySelector = true;
        $this->showQuantityPrice = true;
        $this->showUpdateButton = true;
        $this->showOptions = true;
    }

    public function updateSupply()
    {
        $this->supplyList[$this->supplyListKey] = $this->modelData();  
        $this->setTotalPrice();
        $this->closeSupplyForm();
        $this->showFinalizeOrderButton = true;
    }

    public function deleteSupply($key)
    {
        unset($this->supplyList[$key]);
        $this->setTotalPrice();

        if (empty($this->supplyList)) {
            $this->showSupplyList = false;
            $this->showFinalizeOrderButton = false;       
        }
    }

    public function setTotalPrice()
    {
        $total = 0;
        foreach ($this->supplyList as $supply) {
            $total += $supply['partialPrice'];
        }

        $this->totalSuppliesPrice = $total;
    }

    public function closeSupplyForm()
    {
        $this->reset([
            'supplyId',
            'supplyTitle',
            'quantity',
            'price',
            'partialPrice',
            'showNewSupplyButton',
            'enableActions',
            'enableFinalizeOrderButton',
            'showSupplySelector',
            'showOptions',
            'showUpdateButton',
            'showQuantityPrice',
        ]);
    }

    public function modelData()
    {
        return [
            'id' => $this->supplyId,
            'title' => $this->supplyTitle,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'partialPrice' => $this->partialPrice,
        ];
    }

    public function finalizeOrder()
    {
        $this->showActions = false;
        $this->showNewSupplyButton = false;
        $this->emitUp('finalizeOrder', $this->supplyList, $this->totalSuppliesPrice);
        $this->showFinalizeOrderButton = false;
    }

    public function resetSupplies()
    {
        $this->reset();
    }

    public function render()
    {
        return view('livewire.supplies.order-supplies');
    }
}
