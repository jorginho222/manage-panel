<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">
        <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.3/dist/flowbite.min.css" />
        <link rel="stylesheet" href="{{ asset('font-awesome-4.7.0/css/font-awesome.min.css') }}">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

        <!-- Scripts -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-jet-banner />

        <div class="min-h-screen bg-gray-100">
            {{-- @livewire('navigation-menu') --}}

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <div class="flex">

                {{-- Side Bar --}}
    
                @include('components.sidebar')
                
                <!-- Page Content -->
                <main>
                    @yield('content')
                </main>
            </div>
        </div>

        @stack('modals')

        @livewireScripts

        <script src="https://unpkg.com/flowbite@1.5.3/dist/flowbite.js"></script>
    </body>

    <script>
        Livewire.on('enableProviderSelect2', () => {
            // Inicializando select2
            $('#select-provider').select2() 
        });
        Livewire.on('enableClientSelect2', () => {
            // Inicializando select2
            $('#select-client').select2() 
        });
        Livewire.on('enableSupplyProviderSelect2', providerId => {
            // Inicializando select2
            $('#select-supply-provider').select2() 
            $('#select-supply-provider').select2("val", "" + providerId + "") 
        });
        Livewire.on('enableProviderSelect', () => {
            // Habilitando selector de proovedor
            $('#select-provider').attr('disabled', false)
        });
        Livewire.on('enableClientSelect', () => {
            // Habilitando selector de proovedor
            $('#select-client').attr('disabled', false)
        });
        Livewire.on('disableCreateUpdateButton', () => {
            // Habilitando selector de proovedor
            $('.client').attr('disabled', true)
        });

    </script>
</html>
