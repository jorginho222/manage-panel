<?php

namespace App\Http\Livewire\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class CrudProducts extends Component
{
    use WithPagination;

    public $search = '';
    public $modalForm = false;
    public $editing = false;
    public $updatingSpecifications = false;
    public $deleteConfirmation = false;
    public $showAboutProduct = false;

    public $modelId;
    public $title;
    public $trademark;
    public $price;
    public $stock;
    public $specificationKey;
    public $specificationValue;
    public $specifications = [];

    public $productInfo;

    /**** Index y busqueda */

    public function index()
    {
        // helper search() (AppServiceProvider)
        return Product::search('title', $this->search)->paginate(10);
    }

    /**
     * Actualizar paginador cada vez que se realiza una busqueda
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /*** Modales ****/

    public function showModalForm()
    {
        $this->resetValidation();
        $this->reset([
            'title',
            'trademark',
            'price',
            'stock',
            'specifications',
        ]);
        $this->modalForm = true;
    }

    public function showAboutProduct()
    {
        $this->showAboutProduct = true;
    }

    public function showUpdateModal($id)
    {
        $this->modelId = $id;
        $this->showModalForm();
        $this->editing = true;

        $product = Product::find($this->modelId);
        
        $this->title = $product->title;
        $this->trademark = $product->trademark;
        $this->price = $product->price;
        $this->stock = $product->stock;
        $this->specifications = explode(', ', $product->specifications);
    }
    
    /**
     * Muestra modal de confirmacion para eliminar registro
     */
    public function showDeleteModal($id)
    {
        $this->modelId = $id;
        $this->deleteConfirmation = true;
    }

    /******** CRUD Especificaciones ****/

    public function storeSpecification()
    {
        $this->updatingSpecifications = true;
        $this->validate();
        $this->specifications[] = $this->specificationKey . ': ' . $this->specificationValue;
        $this->reset([
            'specificationKey',
            'specificationValue',
        ]);
    }

    public function deleteSpecification($key)
    {
        unset($this->specifications[$key]);
    }

    /****** CRUD *****/

    public function store()
    {
        $this->updatingSpecifications = false;
        $this->validate();
        Product::create($this->modelData());
        $this->reset();
    }

    public function show($id)
    {
        $this->productInfo = Product::find($id);
        
        $this->specifications = explode(', ', $this->productInfo->specifications);

        $this->showAboutProduct();
    }

    public function update()
    {
        $this->updatingSpecifications = false;
        $this->validate();
        Product::where('id', $this->modelId)
            ->update($this->modelData());
        
        $this->reset();
    }

    public function delete()
    {
        $product = Product::find($this->modelId);
        $product->delete();
        $this->reset();
    }

    /******** Validacion y datos *****/

    /**
     * Validation rules
     * @return array
     */
    public function rules()
    {
        if ($this->updatingSpecifications) {
            return [
                'specificationKey' => ['required', 'max:30'],
                'specificationValue' => ['required', 'max:30'],
            ];
        }

        return [
            'title' => ['required', 'max:150'],
            'trademark' => ['required', 'max:50'],
            'price' => ['required', 'min:1'],
            'stock' => ['required'],
        ];
    }

    /**
     * Array con los datos a insertar
     * @return array
     */
    public function modelData()
    {
        return [
            'title' => $this->title,
            'trademark' => $this->trademark,
            'price' => $this->price,
            'stock' => $this->stock,
            'specifications' => implode(', ', $this->specifications),
        ];
    }

    public function render()
    {
        return view('livewire.products.crud-products', [
            'products' => $this->index(),
        ]);
    }
}
