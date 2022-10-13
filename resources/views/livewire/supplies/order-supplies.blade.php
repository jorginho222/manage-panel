<div>
    @if($showNewSupplyButton)
        <button wire:click="newSupply" class="mt-2">Nuevo insumo</button>
    @endif
    @if($showSupplySelector)
        <div class="mt-4 flex">
            <div>
                <select id="select-supply" wire:model="supplyId" wire:change="getSupplyData" class="mt-1 rounded-lg">
                    <option value="" selected>Seleccione Insumo...</option>
                    @if(isset($supplies))
                        @foreach ($supplies as $supply)
                            <option value="{{ $supply->id }}">{{ $supply->title }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            @if($showQuantityPrice)
                <div class="flex ml-5">
                    <button wire:click="lessQuantity" @disabled($quantity < 2)>-</button>
                    <div class="mx-2 mt-3">
                        {{ $quantity }}
                    </div>
                    <button wire:click="moreQuantity" >+</button>
                </div>
                <div class="ml-5 mt-3">
                    <strong>${{ $partialPrice }}</strong>
                </div>
            @endif
            
        </div>
        @if($showOptions)
            <div class="mt-2">
                @if($showUpdateButton)
                    <button wire:click="updateSupply">Actualizar</button>
                @else
                    <button wire:click="addSupply">Agregar insumo</button>
                @endif
                <button wire:click="closeSupplyForm">Cancelar</button>
            </div>
        @endif
    @endif

    @if($showSupplyList)
        <div class="mt-2">
            <p>Lista:</p>
            @foreach ($supplyList as $key => $supply)
                <div class="mt-1 flex justify-between">
                    <div>
                        {{ $supply['title'] }} (x {{ $supply['quantity'] }} )
                    </div>
                    <div class="flex gap-3">
                        <strong>${{ $supply['partialPrice'] }}</strong>
                        <div>
                            @if($showActions)
                                <button title="Editar insumo" @disabled(!$enableActions) wire:click="editSupply({{ $key }})" class="px-1 bg-orange-400 text-gray-100 rounded-md hover:bg-orange-300">
                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                </button> 
                                <button title="Eliminar insumo" @disabled(!$enableActions) wire:click="deleteSupply({{ $key }})" class="ml-1 px-2 bg-red-600 text-gray-100 rounded-md hover:bg-red-500">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>    
            @endforeach
        </div>
        
        <div class="mt-3">
            <strong>Total insumos: ${{ $totalSuppliesPrice }}</strong> 
        </div>
    @endif

    @if($showFinalizeOrderButton)
        <button @disabled(!$enableFinalizeOrderButton) wire:click="finalizeOrder" class="ml-2 mt-2">Finalizar Pedido</button>
    @endif
</div>

