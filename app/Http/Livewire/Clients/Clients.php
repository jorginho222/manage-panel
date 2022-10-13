<?php

namespace App\Http\Livewire\Clients;

use App\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class Clients extends Component
{
    use WithPagination;

    public $modalForm = false;
    public $editing = false;
    public $deleteConfirmation = false;
    public $deleteClientName;
    public $updatingInterests = false;
    public $showAboutClient = false;
    
    public $search = '';
    
    public $modelId;
    public $clientType;
    public $name;
    public $identifierNumber;
    public $email;
    public $phoneArea;
    public $phone;
    public $location;
    public $address;
    public $interest;
    public $interests = [];

    public $clientInfo;

    /**** Index y busqueda */

    public function index()
    {
        // helper search() (AppServiceProvider)
        return Client::search('name', $this->search)->paginate(10);
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

    public function showAboutClient()
    {
        $this->showAboutClient = true;
    }

    public function showUpdateModal($id)
    {
        $this->showModalForm();
        $this->modelId = $id;

        $client = Client::find($this->modelId);
        $parser = explode('-', $client->phone);

        $this->clientType = $client->type;
        $this->name = $client->name;
        $this->identifierNumber = $client->identifier_number;
        $this->email = $client->email;
        $this->phoneArea = $parser[0];
        $this->phone = $parser[1];
        $this->location = $client->location;
        $this->address = $client->address;
        $this->interests = explode(' ', $client->interests);
        
    }

    /**
     * Muestra modal de confirmacion para eliminar registro
     */
    public function showDeleteModal($id)
    {
        $this->modelId = $id;
        $this->deleteConfirmation = true;
    }

    public function storeInterest()
    {
        $this->updatingInterests = true;
        $this->validate();
        $this->interests[] = $this->interest;
        $this->closeInterestForm();
    }

    public function deleteInterest($key)
    {
        unset($this->interests[$key]);
    }

    public function closeInterestForm()
    {
        $this->reset([
            'interest',
            'updatingInterests',
        ]);
    }

    /*********** CRUD  ******/

    public function store()
    {
        $this->updatingInterests = false;
        $this->validate();
        Client::create($this->modelData());
        $this->reset();
    }

    public function show($id)
    {
        $this->clientInfo = Client::find($id);
        $this->showAboutClient();
    }

    public function update()
    {
        $this->updatingInterests = false;
        $this->validate();
        Client::where('id', $this->modelId)
            ->update($this->modelData());

        $this->reset();
    }

    public function delete()
    {
        $client = Client::find($this->modelId);
        $client->delete();
        $this->reset();
    }

    /******** Validacion y datos *****/

    /**
     * Validation rules
     * @return array
     */
    public function rules()
    {
        if ($this->updatingInterests) {
            return [
                'interest' => ['required', 'max:30'],
            ];
        }

        return [
            'clientType' => ['required'],
            'name' => ['required', 'max:50', Rule::unique('clients', 'name')->ignore($this->modelId)],
            'identifierNumber' => ['required', 'min:11', 'max:11', Rule::unique('clients', 'identifier_number')->ignore($this->modelId)],
            'email' => ['required', 'email', Rule::unique('clients', 'email')->ignore($this->modelId)],
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
            'type' => $this->clientType,
            'name' => $this->name,
            'identifier_number' => $this->identifierNumber, 
            'email' => $this->email,
            'phone' => $this->phoneArea . '-' . $this->phone,
            'location' => $this->location,
            'address' => $this->address,
            'interests' => implode(' ', $this->interests),
        ];
    }

    public function render()
    {
        return view('livewire.clients.clients', [
            'clients' => $this->index(),
        ]);
    }
}
