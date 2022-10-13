<div>
    <div class="flex justify-end px-4 py-3 text-right items-centre sm:py-6">
        <x-jet-button wire:click="showModalForm">
            Nuevo Cliente
        </x-jet-button>
    </div>

    
    {{-- Data Table --}}
    <div class="flex flex-col">
        <div class="my-2 overflow-x-auto sm:mx-6 lg:mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                    <div class="space-y-4">

                        <div class="w-80">
                            <x-jet-input placeholder="Buscar Clientes..." class="block w-full mt-1" type="text"
                            wire:model.debounce.500ms="search" />
                        </div>
                        @if (isset($clients))
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <div class="">
                                            <th class="px-5">ID</th>
                                            <th class="px-5">Empresa/Persona</th>
                                            <th class="px-5">Nombre</th>
                                            <th class="px-5">e-mail</th>
                                            <th class="px-5">Telefono</th>
                                            <th class="px-5">Localidad</th>
                                            <th class="px-5">Acciones</th>
                                        </div>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($clients as $client)
                                            <tr wire:loading.class="opacity-50" wire:target="search">
                                                <td class="px-4">
                                                    {{ $client->id }}
                                                </td>
                                                <td class="px-4">
                                                    {{ $client->type }}
                                                </td>
                                                <td class="px-4">
                                                    {{ $client->name }}
                                                </td>
                                                <td class="px-4">
                                                    {{ $client->email }}
                                                </td>
                                                <td class="px-4">
                                                    {{ $client->phone }}
                                                </td>
                                                <td class="px-4">
                                                    {{ $client->location }}
                                                </td>
                                                <td class="flex justify-end gap-2 mt-3 table-data">
                                                    <button title="Acerca del cliente" wire:click="show({{ $client->id}})" class="px-2 text-gray-100 bg-blue-600 rounded-md hover:bg-blue-500">
                                                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                    </button>
                                                    <button title="Editar cliente" wire:click="showUpdateModal({{ $client->id }})" class="px-1 text-gray-100 bg-orange-400 rounded-md hover:bg-orange-300">
                                                        <i class="fa fa-edit" aria-hidden="true"></i>
                                                    </button>
                                                    <button title="Eliminar cliente" wire:click="showDeleteModal({{ $client->id }})" class="px-2 text-gray-100 bg-red-600 rounded-md hover:bg-red-500">
                                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr wire:loading.class="opacity-50" wire:target="search">
                                                <td colspan="8">
                                                    <div class="flex items-center justify-center">
                                                        <span class="py-6">No se encontraron clientes.</span> 
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse 
                                    </tbody>
                                </table>
                            @else
                                <div>
                                    <span class="py-6">No hay ningun cliente registrado en sistema.</span> 
                                </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="px-3 mb-3">
        {{ $clients->links() }}
    </div>

    {{-- Modal para crear/editar clientes --}}
    <x-jet-dialog-modal wire:model="modalForm">
        <x-slot name="title">
            @if($editing)
                Actualizar
            @else
                Crear
            @endif
                Cliente
        </x-slot>

        <x-slot name="content">
            <div class="mt-4">
                <select id="select-client-type" wire:model="clientType" class="rounded-lg">
                    <option value="" selected>Seleccione tipo de cliente...</option>
                    <option value="Empresa">Empresa</option>
                    <option value="Persona">Persona</option>
                </select>
                @error('clientType')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="name" value="Nombre" />
                <x-jet-input id="name" class="block w-full mt-1" type="text" name="name"
                    wire:model.debounce.500ms="name" />
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="identifier_number" value="CUIT/CUIL" />
                <x-jet-input id="identifier_number" class="block w-40 mt-1" type="number" name="identifierNumber"
                    wire:model.debounce.500ms="identifierNumber" />
                @error('identifierNumber')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" class="block w-full mt-1" type="email" name="email"
                    wire:model.debounce.500ms="email" />
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="" value="{{ __('Telefono') }}" />
                <div class="flex gap-3">
                    <div>
                        <x-jet-label for="phone_area" value="{{ __('Cod. Area') }}" />
                        <x-jet-input id="phone_area" class="block w-24 mt-1" type="number" name="phoneArea"
                            wire:model.debounce.500ms="phoneArea" />
                        @error('phoneArea')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <x-jet-label for="phone" value="{{ __('Numero') }}" />
                        <x-jet-input id="phone" class="block w-full mt-1" type="number" name="phone"
                            wire:model.debounce.500ms="phone" />
                        @error('phone')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <x-jet-label for="location" value="{{ __('Localidad') }}" />
                <x-jet-input id="location" class="block w-full mt-1" type="text" name="location"
                    wire:model.debounce.500ms="location" />
                @error('location')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="address" value="{{ __('Direccion') }}" />
                <x-jet-input id="address" class="block w-full mt-1" type="text" name="address"
                    wire:model.debounce.500ms="address" />
                @error('address')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="mt-6">
                <p>Agregar intereses (opcional)</p>
            </div>

            <div class="flex gap-6 mt-4">
                <div>
                    <x-jet-input id="interest" class="block mt-1 w-72" type="text" name="interest"
                        placeholder="ej. Tecnologia"                   
                        wire:model.debounce.500ms="interest" />
                    @error('interest')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mt-3">
                    <button wire:click="storeInterest">Agregar</button>
                </div>
            </div>

            <div class="flex gap-3 mt-5">
                @foreach ($interests as $key => $item)
                    <div class="flex gap-3 px-3 py-2 border-2 border-gray-400 rounded-lg bg-slate-200">
                        <div>
                            {{ $item }}
                        </div>
                        <div>
                            <button wire:click="deleteInterest({{ $key }})" class="text-gray-700">X</button> 
                        </div>
                    </div>
                @endforeach
            </div>
            
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalForm')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-jet-secondary-button>

            @if ($modelId)
                <div>
                    <x-jet-danger-button class="ml-3 client" wire:click="update" wire:loading.attr="disabled">
                        {{ __('Update') }}
                    </x-jet-danger-button>

                </div>
            @else
                <x-jet-danger-button class="ml-3 client" wire:click="store" wire:loading.attr="disabled">
                    {{ __('Create') }}
                </x-jet-danger-button>
            @endif
        </x-slot>
    </x-jet-dialog-modal>

    {{-- Modal para ver info cliente --}}

    <x-jet-dialog-modal wire:model="showAboutClient">
        <x-slot name="title" class="flex justify-center">
            @if(isset($clientInfo))
                <div class="">
                    <p class="text-2xl">
                        Nombre: {{ $clientInfo->name }} ({{ $clientInfo->type }})
                    </p>
                    
                </div>
            @endif

        </x-slot>
                
        <x-slot name="content">
            @if(isset($clientInfo))
                <p class="mt-2 text-lg">
                    {{ $clientInfo->id_type }}: {{ $clientInfo->id_number }} 
                </p>
                <p class="mt-2 text-lg">
                    Direccion: {{ $clientInfo->address }}, {{ $clientInfo->location }}
                </p>
                <p class="mt-2 text-lg">
                    Intereses: {{ $clientInfo->interests }}
                </p>
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('showAboutClient')" wire:loading.attr="disabled">
                {{ __('Cerrar') }}
            </x-jet-secondary-button>
        </x-slot>
    </x-jet-dialog-modal>

    {{-- Modal para eliminar proovedor --}}
    <x-jet-dialog-modal wire:model="deleteConfirmation">
        <x-slot name="title">
            {{ __('Eliminar proovedor') }}
        </x-slot>

        <x-slot name="content">
            <p>¿Esta seguro que desea eliminar al cliente con ID: {{ $modelId }}?</p> 
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('deleteConfirmation')" wire:loading.attr="disabled">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-3" wire:click="delete" wire:loading.attr="disabled">
                Eliminar
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>

</div>
