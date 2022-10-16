<?php

namespace App\Http\Livewire\SellOrders;

use App\Models\Client;
use App\Models\Product;
use Livewire\Component;
use App\Models\SellOrder;
use App\Http\Traits\OrderTrait;

class SellOrders extends Component
{
    use OrderTrait;

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

    public function showEditOrderStatus($sellOrderStatus, $sellOrderId)
    {
        $this->statusId = $sellOrderStatus;
        $this->modelId = $sellOrderId;
        $this->showEditStatus = true;
    }

    public function showDeleteModal($orderId)
    {
        $this->modelId = $orderId;
        $this->showDeleteModal = true;
    }

    /******* Acciones *******/

    public function goToAddProducts()
    {
        if ($this->clientId !=null) {
            $this->client = Client::findOrFail($this->clientId);
            $this->getAvailableProducts();
            $this->showProductComponent();
            $this->showChangeClient = true;
        }
    }

    public function showProductComponent()
    {
        $this->showProductComponent = true;
    }

    public function finalizeOrder($productList , $totalProductsPrice)
    {
        $this->productList = $productList;
        $this->partialPrice = $totalProductsPrice;
        $this->totalPrice = $totalProductsPrice;

        $this->showRestOfOptions = true;
        $this->enableCreateButton();
    }

    public function enableCreateButton()
    {
        $this->enableCreateButton = true;
    }

    public function seeOrderProducts($sellOrderId)
    {
        $this->getOrderProducts($sellOrderId);
        $this->modelId = $sellOrderId;
        $this->showOrderProducts = true;
    }

    public function changeClient()
    {
        $this->reset([
            'showChangeClient',
            'showRestOfOptions',
            'showProductComponent',
            'productList',
            'partialPrice',
            'shippingCost',
            'statusId',
            'totalPrice',
        ]);

        $this->emitTo('products.order-products', 'resetArticles');
        $this->emit('enableClientSelect');
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
        $this->availableProducts = Product::where('stock', '>', 0)->get();
    }

    public function getOrderProducts($sellOrderId)
    {
        $sellOrder = SellOrder::find($sellOrderId);
        $this->selectedOrder = $sellOrder;
        $this->orderProducts = $sellOrder->products;
    }

    public function getAvailableOrderStatus()
    {
        $order = new SellOrder();
        return $order->available_status;
    }

    /******** CRUD **********/
    public function store()
    {
        $this->validate();

        $order = $this->client->sellOrders()->create($this->modelData());

        foreach ($this->productList as $product) {
            $element[$product['id']] = [
                'quantity' => $product['quantity'],
                'partial_price' => $product['partialPrice'],
            ];
        }

        $order->products()->attach($element);
        
        $this->reset();
        $this->emit('enableClientSelect');
    }

    public function updateOrderStatus()
    {
        SellOrder::where('id', $this->modelId)->update([
            'status' => $this->statusId,
        ]);
        $this->showEditStatus = false;
        $this->reset();
    }

    public function destroyOrder()
    {
        $sellOrder = SellOrder::find($this->modelId);
        $sellOrder->products()->detach();
        $sellOrder->delete();
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
        return view('livewire.sell-orders.sell-orders', [
            'sellOrders' => $this->getOrdersData(),
            'clients' => $this->getAllClients(),
            'availableStatus' => $this->getAvailableOrderStatus(),
        ]);
    }
}
