<div>
    <div class="flex justify-end px-4 py-3 text-right items-centre sm:py-6">
        <x-jet-button wire:click="showCreateModal" id="create-order">
            Generar orden
        </x-jet-button>
    </div>

    {{-- Tabla de ordenes --}}
    <div class="flex flex-col">
        <div class="my-2 overflow-x-auto sm:mx-6 lg:mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="">Id</th>
                                <th class="">Proovedor</th>
                                <th class="">Precio Final</th>
                                <th class="">Creada</th>
                                <th class="">Estado orden</th>
                                <th class="">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white ">
                            @if (isset($buyOrders))
                                @foreach ($buyOrders as $buyOrder)
                                    <tr class="mt-6">
                                        <td class="px-3 py-2">
                                            {{ $buyOrder->id }}
                                        </td>
                                        <td class="px-3 py-2">
                                            {{ $buyOrder->provider->company_name }}
                                        </td>
                                        <td class="px-3 py-2">
                                            ${{ $buyOrder->total_price }}
                                        </td>
                                        <td class="px-3 py-2">
                                            {{ $buyOrder->created_at->format('d/m/Y h:i') }}
                                        </td>
                                        <td class="px-3 py-2">
                                            {{ $buyOrder->available_status[$buyOrder->status] }}
                                        </td>
                                        <td class="mt-2">
                                            <button title="Ver Insumos" wire:click="seeOrderSupplies({{ $buyOrder->id }})" class="px-1 text-gray-100 bg-blue-600 rounded-md hover:bg-blue-500">
                                                <i class="fa fa-list-ul" aria-hidden="true"></i>
                                            </button>
                                            <button title="Editar estado orden" wire:click="showEditOrderStatus({{ $buyOrder->status }}, {{ $buyOrder->id }})" class="px-1 text-gray-100 bg-orange-400 rounded-md hover:bg-orange-300">
                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                            </button>
                                            <a href="{{ route('buy.invoice.generate', ['buyOrder' => $buyOrder->id]) }}" title="Generar Factura" class="px-1 text-gray-100 bg-green-500 rounded-md hover:bg-green-400">
                                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                            </a>
                                            <button title="Eliminar orden" wire:click="showDeleteModal({{ $buyOrder->id }})" class="px-2 ml-1 text-gray-100 bg-red-600 rounded-md hover:bg-red-500">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            </button>
                                            
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="table-data" colspan="4">No hay ninguna orden generada</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    {{-- Modal para crear orden --}}
    <x-jet-dialog-modal wire:model="modalForm">
        <x-slot name="title" class="flex justify-center">
            <div class="flex justify-center">
                Nueva orden 
            </div>
        </x-slot>
        
        <x-slot name="content">
            <div class="mt-4">
                <div>
                    <div class="flex gap-3">
                        {{-- Lista de proovedores para elegir --}}
                        
                        <div wire:ignore>
                            <select id="select-provider" class="mt-1 rounded-lg">
                                <option value="" selected>Seleccione Proveedor...</option>
                                @foreach ($providers as $provider)
                                <option value="{{ $provider->id }}">{{ $provider->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if($showChangeProvider)
                            <div>
                                <button wire:click="changeProvider">Cambiar Proovedor</button>
                            </div>
                        @endif
                    </div>
            
                    {{-- Lista de insumos para elegir --}}
            
                    @if($showSupplyComponent)
                        @livewire('supplies.order-supplies', ['articles' => $providerSupplies])
                    @endif

                    {{-- Resto de las opciones  --}}
                    @if($showRestOfOptions)
                        {{-- Agregar costo de envio --}}
                        <div class="mt-3">
                            <x-jet-label for="shipping_cost" value="Costo de envio" />
                            <div class="flex gap-2 align-items-center">
                                <p class="mt-2">$</p>
                                <x-jet-input id="shipping_cost" class="block w-20 mt-1" type="number" name="shipping_cost"
                                    placeholder="0"
                                    wire:model.debounce.500ms="shippingCost" />
                            </div>
                        </div>

                        {{-- Estado de la orden --}}
                        <div class="mt-2">
                            <p>Seleccione estado de la orden</p>
                            @foreach ($availableStatus as $id => $statusName)
                                <div>
                                    <input type="radio" value="{{ $id }}" wire:model="statusId" > 
                                    <label for="">{{ $statusName }}</label>
                                </div>
                            @endforeach
                        </div>

                        {{-- Precio total --}}
                        <div class="flex justify-end mt-2">
                            <strong>Precio Final</strong>
                            <strong class="ml-5">${{ $totalPrice }}</strong>
                        </div>
                    @endif
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
                @if($enableCreateButton)
                    <x-jet-button class="ml-3" wire:click="store" wire:loading.attr="disabled">
                        Crear orden
                    </x-jet-button>
                @endif
            @endif
        </x-slot>
    </x-jet-dialog-modal>

    {{-- Modal para ver los insumos de la orden --}}
    <x-jet-dialog-modal wire:model="showOrderSupplies">
        <x-slot name="title" class="flex justify-center">
            <div class="flex justify-center">
                Insumos de la orden con ID: {{ $modelId }}
            </div>
        </x-slot>

        <x-slot name="content">
            @if(isset($orderSupplies))
                <ul>
                    @foreach ($orderSupplies as $supply)
                        <div class="flex justify-between mt-3">
                            @if(isset($supply->pivot->quantity))
                                <li>{{ $supply->title }} <span>(x {{ $supply->pivot->quantity }})</span></li>
                                <strong>${{ $supply->pivot->partial_price }}</strong>
                            @endif
                        </div>
                        @endforeach
                    </ul>
                    <div class="flex justify-between ">
                        <div>
                            <p class="mt-3">Costo de envio</p>
                            <p class="mt-3 uppercase"><strong>Total</strong> </p>
                        </div>
                        <div>
                            <p class="mt-3"><strong>${{ $selectedOrder->shipping_cost }}</strong></p>
                            <p class="mt-3"><strong>${{ $selectedOrder->total_price }}</strong></p>
                        </div>
                    </div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('showOrderSupplies')" wire:loading.attr="disabled">
                {{ __('Cerrar') }}
            </x-jet-secondary-button>
        </x-slot>
    </x-jet-dialog-modal>

    {{-- Modal para editar estado de la orden --}}
    <x-jet-dialog-modal wire:model="showEditStatus">
        <x-slot name="title" class="flex justify-center">
            <div class="flex justify-center">
                Estado de la orden con ID: {{ $modelId }}
            </div>
        </x-slot>

        <x-slot name="content">
            <div class="flex justify-center gap-2">
                <div>
                    @foreach ($availableStatus as $id => $statusName)
                        <div>
                            <input type="radio" name="status" value="{{ $id }}" wire:model="statusId" @checked($id == $statusId)> 
                            <label for="">{{ $statusName }}</label>
                        </div>
                    @endforeach
                    <div class="mt-2">
                        <input type="checkbox">
                        <label for="">Enviar mail al proovedor avisando que cambio el estado de la orden</label>
                    </div>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('showEditStatus')" wire:loading.attr="disabled">
                Cerrar
            </x-jet-secondary-button>
            <x-jet-danger-button class="ml-3" wire:click="updateOrderStatus" wire:loading.attr="disabled">
                Actualizar
            </x-jet-danger-button>
        </x-slot>

    </x-jet-dialog-modal>

    {{-- Delete Modal --}}
    <x-jet-dialog-modal wire:model="showDeleteModal">
        <x-slot name="title">
            {{ __('Eliminar proovedor') }}
        </x-slot>

        <x-slot name="content">
            <p>¿Esta seguro que desea eliminar la orden con Id: {{$modelId}}?</p> 
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('showDeleteModal')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-3" wire:click="destroyOrder" wire:loading.attr="disabled">
                {{ __('Delete Order') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        // Capturando valor al cambiar el select. Asignando a la var del comp
        $('#select-provider').on('change', function (e) {
            var providerId = $('#select-provider').select2("val")
            @this.set('providerId', providerId)
            $('#select-provider').attr('disabled', true)
            Livewire.emit('goToAddSupplies')
        });
    </script>
</div>
