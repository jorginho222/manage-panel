<div>
    @if($showNewArticleButton)
        <button wire:click="newArticle" class="mt-2">Nuevo producto</button>
    @endif
    @if($showArticleSelector)
        <div class="flex mt-4">
            <div>
                <select id="select-supply" wire:model="articleId" wire:change="getArticleData('{{ $orderType }}')" class="mt-1 rounded-lg">
                    <option value="" selected>Seleccione producto...</option>
                    @if(isset($articles))
                        @foreach ($articles as $article)
                            <option value="{{ $article->id }}">{{ $article->title }}</option>
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
                <div class="mt-3 ml-5">
                    <strong>${{ $partialPrice }}</strong>
                </div>
            @endif
            
        </div>
        @if($showOptions)
            <div class="mt-2">
                @if($showUpdateButton)
                    <button wire:click="updateArticle">Actualizar</button>
                @else
                    <button wire:click="addArticle">Agregar producto</button>
                @endif
                <button wire:click="closeArticleForm">Cancelar</button>
            </div>
        @endif
    @endif

    @if($showArticleList)
        <div class="mt-2">
            <p>Lista:</p>
            @foreach ($articleList as $key => $article)
                <div class="flex justify-between mt-1">
                    <div>
                        {{ $article['title'] }} (x {{ $article['quantity'] }} )
                    </div>
                    <div class="flex gap-3">
                        <strong>${{ $article['partialPrice'] }}</strong>
                        <div>
                            @if($showActions)
                                <button title="Editar producto" @disabled(!$enableActions) wire:click="editArticle({{ $key }})" class="px-1 text-gray-100 bg-orange-400 rounded-md hover:bg-orange-300">
                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                </button> 
                                <button title="Eliminar producto" @disabled(!$enableActions) wire:click="deleteArticle({{ $key }})" class="px-2 ml-1 text-gray-100 bg-red-600 rounded-md hover:bg-red-500">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>    
            @endforeach
        </div>
        
        <div class="mt-3">
            <strong>Total productos: ${{ $totalArticlesPrice }}</strong> 
        </div>
    @endif

    @if($showFinalizeOrderButton)
        <button @disabled(!$enableFinalizeOrderButton) wire:click="finalizeOrder" class="mt-2 ml-2">Finalizar Pedido</button>
    @endif
</div>

