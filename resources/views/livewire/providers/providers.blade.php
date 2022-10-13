<div>
    <div class="flex justify-end px-4 py-3 text-right items-centre sm:py-6">
        <x-jet-button wire:click="showModalForm">
            Nuevo Proovedor
        </x-jet-button>
    </div>

    
    {{-- Data Table --}}
    <div class="flex flex-col">
        <div class="my-2 overflow-x-auto sm:mx-6 lg:mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                    <div class="space-y-4">

                        <div class="w-80">
                            <x-jet-input placeholder="Buscar proovedores..." class="block w-full mt-1" type="text"
                            wire:model.debounce.500ms="search" />
                        </div>
                        @if (isset($providers))
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <div class="">
                                            <th class="px-5">ID</th>
                                            <th class="px-5">Empresa</th>
                                            <th class="px-5">e-mail</th>
                                            <th class="px-5">Telefono</th>
                                            <th class="px-5">Localidad</th>
                                            <th class="px-5">Direccion</th>
                                            <th class="px-5">Acciones</th>
                                        </div>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($providers as $provider)
                                            <tr wire:loading.class="opacity-50" wire:target="search">
                                                <td class="px-4">
                                                    {{ $provider->id }}
                                                </td>
                                                <td class="px-4">
                                                    {{ $provider->company_name }}
                                                </td>
                                                <td class="px-4">
                                                    {{ $provider->email }}
                                                </td>
                                                <td class="px-4">
                                                    {{ $provider->phone }}
                                                </td>
                                                <td class="px-4">
                                                    {{ $provider->location }}
                                                </td>
                                                <td class="px-4">
                                                    {{ $provider->address }}
                                                </td>
                                                <td class="flex justify-end gap-2 mt-3 table-data">
                                                    <button title="Acerca del proovedor" wire:click="showAboutProvider('{{ $provider->company_name }}', '{{ $provider->about }}')" class="px-2 text-gray-100 bg-blue-600 rounded-md hover:bg-blue-500">
                                                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                    </button>
                                                    <button title="Editar proovedor" wire:click="showUpdateModal({{ $provider->id }})" class="px-1 text-gray-100 bg-orange-400 rounded-md hover:bg-orange-300">
                                                        <i class="fa fa-edit" aria-hidden="true"></i>
                                                    </button>
                                                    <button title="Eliminar proovedor" wire:click="showDeleteModal({{ $provider->id }}, '{{  $provider->company_name }}')" class="px-2 text-gray-100 bg-red-600 rounded-md hover:bg-red-500">
                                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr wire:loading.class="opacity-50" wire:target="search">
                                                <td colspan="8">
                                                    <div class="flex items-center justify-center">
                                                        <span class="py-6">No se encontraron proovedores.</span> 
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse 
                                    </tbody>
                                </table>
                            @else
                                <div>
                                    <span class="py-6">No hay ningun proovedor registrado en sistema.</span> 
                                </div>
                            @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="px-3 mb-3">
        {{ $providers->links() }}
    </div>

    {{-- Modal para crear/editar proovedores --}}
    <x-jet-dialog-modal wire:model="modalForm">
        <x-slot name="title">
            @if($editing)
                Actualizar
            @else
                Crear
            @endif
                Proovedor
        </x-slot>

        <x-slot name="content">
            <div class="mt-4">
                <x-jet-label for="company_name" value="{{ __('Empresa') }}" />
                <x-jet-input id="company_name" class="block w-full mt-1" type="text" name="company_name"
                    wire:model.debounce.500ms="companyName" />
                @error('companyName')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="about" value="{{ __('Acerca de') }}" />
                <x-jet-input id="about" class="block w-full mt-1" type="text" name="about"
                    wire:model.debounce.500ms="about" />
                @error('about')
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
                <x-jet-label for="phone" value="{{ __('Telefono') }}" />
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
                        <x-jet-label for="phone_area" value="{{ __('Numero') }}" />
                        <x-jet-input id="phone_area" class="block w-full mt-1" type="number" name="phone"
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

    {{-- Modal acerca del proovedor --}}

    <x-jet-dialog-modal wire:model="showAboutProvider">
        <x-slot name="title" class="flex justify-center">
            <div class="flex justify-center">
                <p class="text-2xl">
                    Proovedor: {{ $companyName }}
                </p>
            </div>
        </x-slot>

        <x-slot name="content">
            <p class="text-lg">
                Acerca de:
            </p>
            <p>
                {{ $about }}
            </p>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('showAboutProvider')" wire:loading.attr="disabled">
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
            <p>¿Esta seguro que desea eliminar al proovedor "{{$deleteCompanyName}}"?</p> 
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
