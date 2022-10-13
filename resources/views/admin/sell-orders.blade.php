@extends('layouts.app')
@section('content')

<h2 class="text-xl font-semibold leading-tight text-center text-gray-800">
    Ordenes de venta
</h2>

<div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
            @livewire('sell-orders.sell-orders')
        </div>
    </div>
</div>

@endsection