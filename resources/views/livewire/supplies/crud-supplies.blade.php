<div>
    <div class="flex justify-end px-4 py-3 text-right items-centre sm:py-6">
        <x-jet-button wire:click="showModalForm">
            Nuevo Insumo
        </x-jet-button>
    </div>

    {{-- Data Table --}}
    <div class="flex flex-col">
        <div class="my-2 overflow-x-auto sm:mx-6 lg:mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                    <div class="space-y-4">

                        <div class="w-80">
                            <x-jet-input placeholder="Buscar insumos..." class="block w-full mt-1" type="text"
                            wire:model.debounce.500ms="search" />
                        </div>
                        @if(isset($supplies))
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="">ID</th>
                                        <th class="">Titulo</th>
                                        <th class="">Marca</th>
                                        <th class="">Precio</th>
                                        <th class="">Proovedor</th>
                                        <th class="">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    
                                    @forelse($supplies as $supply)
                                        <tr wire:loading.class="opacity-50" wire:target="search">
                                            <td class="px-4 py-2">{{ $supply->id }}</td>
                                            <td class="px-4 py-2">{{ $supply->title }}</td>
                                            <td class="px-4 py-2">{{ $supply->trademark }}</td>
                                            <td class="px-4 py-2">${{ $supply->price }}</td>
                                            <td class="px-4 py-2">{{ $supply->provider->company_name }}</td>
                                            <td class="px-4 py-2">
                                                <button title="Editar insumo" wire:click="showUpdateModal({{ $supply->id }})" class="px-1 text-gray-100 bg-orange-400 rounded-md hover:bg-orange-300">
                                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                                </button>
                                                <button title="Eliminar insumo" wire:click="showDeleteModal({{ $supply->id }})" class="px-2 text-gray-100 bg-red-600 rounded-md hover:bg-red-500">
                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr wire:loading.class="opacity-50" wire:target="search">
                                            <td colspan="8">
                                                <div class="flex items-center justify-center">
                                                    <span class="py-6">No se encontraron insumos.</span> 
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        @else
                            <div>
                                <span class="py-6">No hay ningun insumo registrado en sistema.</span> 
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="px-3 mb-3">

        {{ $supplies->links() }}
    </div>
    
    {{-- Modal para crear/editar insumos --}}
    <x-jet-dialog-modal wire:model="modalForm">
        <x-slot name="title">
            @if($editing)
            Actualizar
            @else
            Crear
            @endif
            Insumo
        </x-slot>
        
        <x-slot name="content">
            <div class="mt-4">
                <x-jet-label for="title" value="Titulo" />
                <x-jet-input id="title" class="block w-full mt-1" type="text" name="title"
                wire:model.debounce.500ms="title" />
                @error('title')
                <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="trademark" value="Marca" />
                <x-jet-input id="trademark" class="block w-full mt-1" type="text" name="trademark"
                wire:model.debounce.500ms="trademark" />
                @error('trademark')
                <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="price" value="Precio" />
                <x-jet-input id="price" class="block w-full mt-1" type="number" name="price"
                wire:model.debounce.500ms="price" />
                @error('price')
                <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="mt-4">
                <div wire:ignore>
                    <select id="select-supply-provider" class="rounded-lg">
                        <option value="" selected>Seleccione Proveedor...</option>
                        @foreach ($providers as $provider)
                        <option value="{{ $provider->id }}">{{ $provider->company_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalForm')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-jet-secondary-button>
            
            @if ($modelId)
            <x-jet-danger-button class="ml-3" wire:click="update" wire:loading.attr="disabled">
                {{ __('Update') }}
            </x-jet-danger-button>
            @else
            <x-jet-danger-button class="ml-3" wire:click="store" wire:loading.attr="disabled">
                {{ __('Create') }}
            </x-jet-danger-button>
            @endif
        </x-slot>
    </x-jet-dialog-modal>

    {{-- Modal para eliminar insumo --}}
    <x-jet-dialog-modal wire:model="deleteConfirmation">
        <x-slot name="title">
            Eliminar insumo
        </x-slot>

        <x-slot name="content">
            <p>¿Esta seguro que desea eliminar insumo con ID: {{ $modelId }}?</p> 
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

    <script>
        // Capturando valor al cambiar el select. Asignando a la var del comp
        $('#select-supply-provider').on('change', function (e) {
            var providerId = $('#select-supply-provider').select2("val")
            @this.set('providerId', providerId)
        });
    </script>

</div>