<?php

namespace App\Http\Livewire\Providers;

use Livewire\Component;
use App\Models\Provider;
use Illuminate\Validation\Rule;
use Livewire\WithPagination;

class Providers extends Component
{
    use WithPagination;

    public $modalForm = false;
    public $editing = false;
    public $showAboutProvider = false;  
    public $deleteConfirmation = false;
    public $deleteCompanyName;
    public $modelId;

    public $search = '';

    public $companyName;
    public $about;
    public $email;
    public $phoneArea;
    public $phone;
    public $location;
    public $address;

    /**** Index y busqueda */
    
    public function index()
    {
        // helper search() (AppServiceProvider)
        return Provider::search('company_name', $this->search)->paginate(20);
    }

    /**
     * Actualizar paginador cada vez que se realiza una busqueda
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /************** Modales *********/

    public function showModalForm()
    {
        $this->resetValidation();
        $this->reset();
        $this->modalForm = true;
    }

    public function showAboutProvider($name, $about)
    {
        $this->companyName = $name;
        $this->about = $about;
        $this->showAboutProvider = true;
    }

    /**
     *  Muestra el formulario para actualizar registro
     */
    public function showUpdateModal($id)
    {
        $this->showModalForm();
        $this->editing = true;
        $this->modelId = $id;

        $provider = Provider::find($this->modelId);
        $parser = explode('-', $provider->phone);

        $this->companyName = $provider->company_name;
        $this->about = $provider->about;
        $this->email = $provider->email;
        $this->phoneArea = $parser[0];
        $this->phone = $parser[1];
        $this->location = $provider->location;
        $this->address = $provider->address;
    }

    /**
     * Muestra modal de confirmacion para eliminar registro
     */
    public function showDeleteModal($id, $companyName)
    {
        $this->modelId = $id;
        $this->deleteCompanyName = $companyName;    // Nombre del Proovedor que deseo eliminar
        $this->deleteConfirmation = true;
    }

    /************ CRUD ********/

    /**
     * Almacenar registro
     */
    public function store()
    {
        $this->validate();
        Provider::create($this->modelData());
        $this->reset();
    }

    /**
     * Actualizar registro
     */
    public function update()
    {
        $this->validate();
        Provider::where('id', $this->modelId)
            ->update($this->modelData());

        $this->reset();
    }

    /**
     * Eliminar registro
     */
    public function delete()
    {
        $provider = Provider::find($this->modelId);
        $provider->supplies()->delete();
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
            'companyName' => ['required', Rule::unique('providers', 'company_name')->ignore($this->modelId), 'max:50'],
            'about' => ['required', 'max:150'],
            'email' => ['required', Rule::unique('providers', 'email')->ignore($this->modelId), 'email'],
            'phoneArea' => ['required', 'max:5'],
            'phone' => ['required', 'max:10'],
            'location' => ['required', 'max:20'],
            'address' => ['required', 'max:100'],
        ];
    }

    /**
     * Array con los datos a insertar
     * @return array
     */
    public function modelData()
    {
        return [
            'company_name' => $this->companyName,
            'about' => $this->about,
            'email' => $this->email,
            'phone' => $this->phoneArea . '-' . $this->phone,
            'location' => $this->location,
            'address' => $this->address,
        ];
    }

    public function render()
    {
        return view('livewire.providers.providers', [
            'providers' => $this->index(),
        ]);
    }
}
