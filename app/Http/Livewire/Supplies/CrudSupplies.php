<?php

namespace App\Http\Livewire\Supplies;

use App\Models\Supply;
use Livewire\Component;
use App\Models\Provider;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class CrudSupplies extends Component
{
    use WithPagination;

    public $search = '';
    public $modalForm = false;
    public $editing = false;
    public $deleteConfirmation = false;
    
    public $modelId;
    public $title;
    public $trademark;
    public $price;
    public $providerId = "";

    /**** Index y busqueda */

    public function index()
    {
        // helper search() (AppServiceProvider)
        return Supply::search('title', $this->search)->paginate(10);
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
            'providerId',
        ]);
        $this->modalForm = true;
        $this->emit('enableSupplyProviderSelect2', $this->providerId); // Habilitando el select2 en el modal, por medio de js
    }

    /**
     *  Muestra el formulario para actualizar registro
     */
    public function showUpdateModal($id)
    {
        $this->modelId = $id;
        $this->showModalForm();
        $this->editing = true;

        $supply = Supply::find($this->modelId);

        $this->title = $supply->title;
        $this->trademark = $supply->trademark;
        $this->price = $supply->price;
        $this->providerId = $supply->provider_id;

        $this->emit('enableSupplyProviderSelect2', $this->providerId); // Habilitando el select2 en el modal, por medio de js
    }

    /**
     * Muestra modal de confirmacion para eliminar registro
     */
    public function showDeleteModal($id)
    {
        $this->modelId = $id;
        $this->deleteConfirmation = true;
    }

    /****** CRUD ******/    

    /**
     * Almacenar registro
     */
    public function store()
    {
        $this->validate();
        Supply::create($this->modelData());
        $this->reset();
    }

    /**
     * Actualizar registro
     */
    public function update()
    {
        $this->validate();
        Supply::where('id', $this->modelId)
            ->update($this->modelData());

        $this->reset();
    }

    /**
     * Eliminar registro
     */
    public function delete()
    {
        $provider = Supply::find($this->modelId);
        $provider->delete();
        $this->reset();
    }

    /******** Validacion y datos *****/

    /**
     * Validation rules
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['required', Rule::unique('supplies', 'title')->ignore($this->modelId), 'max:150'],
            'trademark' => ['required', 'max:50'],
            'price' => ['required', 'min:1'],
            'providerId' => ['required'],
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
            'provider_id' => $this->providerId,
        ];
    }

    public function render()
    {
        $providers = Provider::all();

        return view('livewire.supplies.crud-supplies', [
            'supplies' => $this->index(),
            'providers' => $providers,
        ]);
    }
}
