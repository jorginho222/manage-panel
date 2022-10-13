<?php

namespace App\Http\Livewire\SellOrders;

use App\Models\Client;
use App\Models\Product;
use Livewire\Component;
use App\Models\SellOrder;

class SellOrders extends Component
{
    // Mostrar / ocultar
    public $modalForm = false;
    public $deleteConfirmation = false;
    public $showOrderProducts = false;
    public $showEditStatus = false;
    public $showDeleteModal = false;
    public $showProductComponent = false;
    public $showChangeClient = false;
    public $showRestOfOptions = false;
    public $enableCreateButton = false;

    // Datos de la orden seleccionada (show, delete)
    public $modelId;
    public $orderProducts;
    public $selectedOrder;
    public $deleteOrderId;

    // Datos para crear la orden (create)
    public $client;
    public $clientId;
    public $availableProducts;
    public $productList;
    public $partialPrice = 0;
    public $shippingCost;
    public $totalPrice = 0;

    public $statusId;

    protected $listeners = ['goToAddProducts', 'finalizeOrder'];

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
        $this->emit('enableClientSelect2'); // Habilitando el select2 en el modal, por medio de js
    }

    /******* Acciones *******/

    public function goToAddProducts()
    {
        if ($this->clientId !=null) {
            $this->getAvailableProducts();
            $this->showProductComponent();
            $this->showChangeClient = true;
        }
    }

    public function showProductComponent()
    {
        $this->showProductComponent = true;
    }

    /*************** Getters *********/

    public function getOrdersData()
    {
        return SellOrder::all();
    }

    public function getAllClients()
    {
        return Client::all();
    }
    
    public function getAvailableProducts()
    {
        $this->availableProducts = Product::where('stock', '>', 0);
    }

    public function getAvailableOrderStatus()
    {
        $order = new SellOrder();
        return $order->available_status;
    }

    public function render()
    {
        return view('livewire.sell-orders.sell-orders', [
            'sellOrders' => $this->getOrdersData(),
            'clients' => $this->getAllClients(),
            'availableStatus' => $this->getAvailableOrderStatus(),
        ]);
    }
}
