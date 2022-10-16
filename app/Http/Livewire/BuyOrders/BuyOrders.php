<?php

namespace App\Http\Livewire\BuyOrders;

use App\Http\Traits\OrderTrait;
use App\Models\Supply;
use Livewire\Component;
use App\Models\BuyOrder;
use App\Models\Provider;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class BuyOrders extends Component
{
    use OrderTrait;

    // Mostrar / ocultar
    public $modalForm = false;
    public $deleteConfirmation = false;
    public $showOrderSupplies = false;
    public $showEditStatus = false;
    public $showDeleteModal = false;
    public $showSupplyComponent = false;
    // public $showProviderSelect = true;
    public $showChangeProvider = false;
    public $showRestOfOptions = false;
    public $enableCreateButton = false;
    
    // Datos de la orden seleccionada (show, delete)
    public $modelId;
    public $orderSupplies;
    public $selectedOrder;
    public $deleteOrderId;

    // Datos para crear la orden (create)
    public $provider;
    public $providerId;
    public $providerSupplies;
    public $supplyList;
    public $partialPrice = 0;
    public $shippingCost;
    public $totalPrice = 0;

    public $statusId;

    protected $listeners = ['goToAddSupplies', 'addSupplyComponent', 'finalizeOrder'];

    /************** Modales *********/

    /**
     * Muestra el formulario para crear registro
     */
    public function showCreateModal()
    {
        $this->resetValidation();
        $this->reset();
        $this->statusId = 1;
        $this->modalForm = true;
        $this->emit('enableProviderSelect2'); // Habilitando el select2 en el modal, por medio de js
    }

    public function showEditOrderStatus($buyOrderStatus, $buyOrderId)
    {
        $this->statusId = $buyOrderStatus;
        $this->modelId = $buyOrderId;
        $this->showEditStatus = true;
    }

    public function showDeleteModal($orderId)
    {
        $this->modelId = $orderId;
        $this->showDeleteModal = true;
    }

    /******* Acciones *******/

    public function goToAddSupplies()
    {
        // $this->showProviderSelect = false;
        $this->getProviderSupplies();
        $this->showSupplyComponent();
        $this->showChangeProvider = true;
    }
    
    public function showSupplyComponent()
    {
        $this->showSupplyComponent = true;    // Pasando los insumos del proovedor a cada componente
    }

    public function finalizeOrder($supplyList , $totalSuppliesPrice)
    {
        $this->supplyList = $supplyList;
        $this->partialPrice = $totalSuppliesPrice;
        $this->totalPrice = $totalSuppliesPrice;

        $this->showRestOfOptions = true;
        $this->enableCreateButton();
    }

    public function enableCreateButton()
    {
        $this->enableCreateButton = true;
    }

    public function seeOrderSupplies($buyOrderId)
    {
        $this->getOrderSupplies($buyOrderId);
        $this->modelId = $buyOrderId;
        $this->showOrderSupplies = true;
    }

    public function changeProvider()
    {
        $this->reset([
            'showChangeProvider',
            'showRestOfOptions',
            'showSupplyComponent',
            'supplyList',
            'partialPrice',
            'shippingCost',
            'statusId',
            'totalPrice',
        ]);

        $this->emitTo('supplies.order-supplies', 'resetArticles');
        $this->emit('enableProviderSelect');
    }

    /*************** Getters *********/

    public function getOrdersData()
    {
        return BuyOrder::all();
    }

    public function getAllProviders()
    {
        return Provider::all();
    }

    private function getProviderSupplies()
    {
        if ($this->providerId != null) {
            $this->provider = Provider::findOrFail($this->providerId);
            $this->providerSupplies = $this->provider->supplies;
        }
    }

    public function getOrderSupplies($buyOrderId)
    {
        $buyOrder = BuyOrder::find($buyOrderId);
        $this->selectedOrder = $buyOrder;
        $this->orderSupplies = $buyOrder->supplies;
    }

    public function getAvailableOrderStatus()
    {
        $order = new BuyOrder();
        return $order->available_status;
    }

    /******** CRUD **********/
    public function store()
    {
        $this->validate();

        $order = $this->provider->buyOrders()->create($this->modelData());

        foreach ($this->supplyList as $supply) {
            $element[$supply['id']] = [
                'quantity' => $supply['quantity'],
                'partial_price' => $supply['partialPrice'],
            ];
        }

        $order->supplies()->attach($element);
        
        $this->reset();
        $this->emit('enableProviderSelect');
    }

    public function updateOrderStatus()
    {
        BuyOrder::where('id', $this->modelId)->update([
            'status' => $this->statusId,
        ]);
        $this->showEditStatus = false;
        $this->reset();
    }

    public function destroyOrder()
    {
        $buyOrder = BuyOrder::find($this->modelId);
        $buyOrder->supplies()->detach();
        $buyOrder->delete();
        $this->reset();
    }

    /**
     * Validation rules
     * @return array
     */
    public function rules()
    {
        return [
            'statusId' => ['required'],
        ];
    }

    /**
     * Array con los datos a insertar
     * @return array
     */
    private function modelData()
    {
        return [
            'status' => $this->statusId,
            'shipping_cost' => $this->shippingCost,
            'total_price' => $this->totalPrice,
        ];
    }

    public function render()
    {
        return view('livewire.buy-orders.buy-orders', [
            'buyOrders' => $this->getOrdersData(),
            'providers' => $this->getAllProviders(),
            'availableStatus' => $this->getAvailableOrderStatus(),
        ]);
    }
}
