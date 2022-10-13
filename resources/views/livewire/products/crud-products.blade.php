<div>
    <div class="flex justify-end px-4 py-3 text-right items-centre sm:py-6">
        <x-jet-button wire:click="showModalForm">
            Nuevo Producto
        </x-jet-button>
    </div>

    {{-- Data Table --}}
    <div class="flex flex-col">
        <div class="my-2 overflow-x-auto sm:mx-6 lg:mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                    <div class="space-y-4">

                        <div class="w-80">
                            <x-jet-input placeholder="Buscar Productos..." class="block w-full mt-1" type="text"
                            wire:model.debounce.500ms="search" />
                        </div>
                        @if(isset($products))
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="">ID</th>
                                        <th class="">Titulo</th>
                                        <th class="">Marca</th>
                                        <th class="">Precio</th>
                                        <th class="">Stock</th>
                                        <th class="">Vendidos</th>
                                        <th class="">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    
                                    @forelse($products as $product)
                                        <tr wire:loading.class="opacity-50" wire:target="search">
                                            <td class="px-4 py-2">{{ $product->id }}</td>
                                            <td class="px-4 py-2">{{ $product->title }}</td>
                                            <td class="px-4 py-2">{{ $product->trademark }}</td>
                                            <td class="px-4 py-2">${{ $product->price }}</td>
                                            <td class="px-4 py-2">{{ $product->stock }}</td>
                                            <td class="px-4 py-2">{{ $product->sells }}</td>
                                            <td class="px-4 py-2">
                                                <button title="Ver detalles" wire:click="show({{ $product->id }})" class="px-2 text-gray-100 bg-blue-600 rounded-md hover:bg-blue-500">
                                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                </button>
                                                <button title="Editar producto" wire:click="showUpdateModal({{ $product->id }})" class="px-1 text-gray-100 bg-orange-400 rounded-md hover:bg-orange-300">
                                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                                </button>
                                                <button title="Eliminar producto" wire:click="showDeleteModal({{ $product->id }})" class="px-2 text-gray-100 bg-red-600 rounded-md hover:bg-red-500">
                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr wire:loading.class="opacity-50" wire:target="search">
                                            <td colspan="8">
                                                <div class="flex items-center justify-center">
                                                    <span class="py-6">No se encontraron productos.</span> 
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

        {{ $products->links() }}
    </div>
    
    {{-- Modal para crear/editar insumos --}}
    <x-jet-dialog-modal wire:model="modalForm">
        <x-slot name="title">
            @if($editing)
            Actualizar
            @else
            Crear
            @endif
            Producto
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
                <p>Agregar especificaciones (opcional)</p>
            </div>
            <div class="mt-2">
                <div class="flex gap-5">
                    <div>
                        <x-jet-label for="specification_key" value="Caracteristica" />
                        <x-jet-input id="specification_key" class="block w-full mt-1" type="text" name="specification_key"
                        wire:model.debounce.500ms="specificationKey" 
                        placeholder="Ej. Peso"/>
                        @error('specificationKey')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <x-jet-label for="specification_value" value="Valor" />
                        <x-jet-input id="specification_value" class="block w-full mt-1" type="text" name="specification_value"
                        wire:model.debounce.500ms="specificationValue" 
                        placeholder="Ej. 8 Kg."/>
                        @error('specificationValue')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="mt-3 flex gap-4">
                    <button wire:click="storeSpecification">Agregar</button>
                </div>
                <div class="flex gap-3 mt-5">
                    @foreach ($specifications as $key => $item)
                        <div class="flex gap-3 px-3 py-2 border-2 border-gray-400 rounded-lg bg-slate-200">
                            <div>
                                {{ $item }}
                            </div>
                            <div>
                                <button wire:click="deleteSpecification({{ $key }})" class="text-gray-700">X</button> 
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="mt-4">
                <x-jet-label for="price" value="Precio" />
                <x-jet-input id="price" class="block w-40 mt-1" type="number" name="price"
                wire:model.debounce.500ms="price" />
                @error('price')
                <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="stock" value="Stock" />
                <x-jet-input id="stock" class="block w-40 mt-1" type="number" name="stock"
                wire:model.debounce.500ms="stock" />
                @error('stock')
                <span class="error">{{ $message }}</span>
                @enderror
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

    {{-- Modal para ver info producto --}}

    <x-jet-dialog-modal wire:model="showAboutProduct">
        <x-slot name="title" class="flex justify-center">
            @if(isset($productInfo))
                <div class="">
                    <p class="text-2xl">
                        Titulo: {{ $productInfo->title }} 
                    </p>
                </div>
            @endif

        </x-slot>
                
        <x-slot name="content">
            @if(isset($productInfo))
                <p class="mt-2 text-lg">
                    Marca: {{ $productInfo->trademark }}
                </p>
                <p class="mt-2 text-lg">
                    Especificaciones: 
                </p>
                @foreach ($specifications as $item)
                    <p>
                        {{ $item }}
                    </p>
                @endforeach
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('showAboutProduct')" wire:loading.attr="disabled">
                {{ __('Cerrar') }}
            </x-jet-secondary-button>
        </x-slot>
    </x-jet-dialog-modal>

    {{-- Modal para eliminar producto --}}
    <x-jet-dialog-modal wire:model="deleteConfirmation">
        <x-slot name="title">
            Eliminar producto
        </x-slot>

        <x-slot name="content">
            <p>¿Esta seguro que desea eliminar producto con ID: {{ $modelId }}?</p> 
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