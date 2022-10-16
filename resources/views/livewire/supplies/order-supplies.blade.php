<div>
    @if($showNewArticleButton)
        <button wire:click="newArticle" class="px-2 py-2 mt-2 bg-emerald-500">
            <div class="flex gap-3">
                <div class="font-medium text-white">Nuevo insumo</div>
                <svg wire:loading wire:target="newArticle" aria-hidden="true" class="w-5 h-5 mr-2 text-gray-200 animate-spin fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                </svg>
            </div>
        </button>
    @endif
    @if($showArticleSelector)
        <div class="flex mt-4">
            <div>
                <select id="select-supply" wire:model="articleId" wire:change="getArticleData('{{ $orderType }}')" class="mt-1 rounded-lg">
                    <option value="" selected>Seleccione Insumo...</option>
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
                    <button wire:click="updateArticle">
                        <div wire:loading.remove wire:target="updateArticle">Actualizar</div>
                        <div wire:loading wire:target="updateArticle">Actualizando</div>
                    </button>
                @else
                    <button wire:click="addArticle">
                        <div wire:loading.remove wire:target="addArticle">Agregar insumo</div>
                        <div wire:loading wire:target="addArticle">Agregando</div>
                    </button>
                @endif
                <button wire:loading.remove wire:target="addArticle, updateArticle" wire:click="closeArticleForm">Cancelar</button>
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
                                <button title="Editar insumo" @disabled(!$enableActions) wire:click="editArticle({{ $key }})" class="px-1 text-gray-100 bg-orange-400 rounded-md hover:bg-orange-300">
                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                </button> 
                                <button title="Eliminar insumo" @disabled(!$enableActions) wire:click="deleteArticle({{ $key }})" class="px-2 ml-1 text-gray-100 bg-red-600 rounded-md hover:bg-red-500">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>    
            @endforeach
        </div>
        
        <div class="mt-3">
            <strong>Total insumos: ${{ $totalArticlesPrice }}</strong> 
        </div>
    @endif

    @if($showFinalizeOrderButton)
        <button @disabled(!$enableFinalizeOrderButton) wire:click="finalizeOrder" class="mt-2 ml-2">
            <div wire:loading.remove wire:target="finalizeOrder">Finalizar</div>
            <div wire:loading wire:target="finalizeOrder">Finalizando</div>
        </button>
    @endif
</div>

