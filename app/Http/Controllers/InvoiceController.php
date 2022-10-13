<?php

namespace App\Http\Controllers;

use App\Models\BuyOrder;
use Illuminate\Http\Request;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\Seller;
use LaravelDaily\Invoices\Classes\InvoiceItem;

class InvoiceController extends Controller
{
    public function generateInvoice(BuyOrder $buyOrder)
    {

        $provider = new Party([
            'name'          => $buyOrder->provider->company_name,
            'address'       => $buyOrder->provider->address,
            'phone'         => $buyOrder->provider->phone,
            'custom_fields' => [
                'email' => $buyOrder->provider->email,
            ],
        ]);

        $company = new Party([
            'name'          => 'Rulo S.A.',
            'address'       => 'Calle falsa 123',
            'custom_fields' => [
                'email' => 'test@company.com',
            ],
        ]);

        $orderSupplies = $buyOrder->supplies;

        foreach ($orderSupplies as $supply) {
            $items[] = (new InvoiceItem())
                ->title($supply->title)
                ->pricePerUnit($supply->price)
                ->quantity($supply->pivot->quantity);
        }

        $notes = '';

        $invoice = Invoice::make()
            ->buyer($company)
            ->seller($provider)
            ->date($buyOrder->created_at)
            ->dateFormat('d/m/Y')
            ->status($buyOrder->available_status[$buyOrder->status])
            ->sequence($buyOrder->id)
            ->discountByPercent(0)
            ->currencySymbol('$')
            ->currencyCode('ARS')
            ->taxRate(0)
            ->shipping(0)
            ->addItems($items)
            ->notes($notes);
            
        // dd($invoice);
        return $invoice->stream();
    }
}
