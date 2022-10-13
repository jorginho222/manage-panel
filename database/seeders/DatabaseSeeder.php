<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Client;
use App\Models\Supply;
use App\Models\Product;
use App\Models\Provider;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $providers = Provider::factory(7)->create();
        $clients = Client::factory(20)->create();
        $products = Product::factory(100)->create();
        
        Supply::factory(300)
            ->make()
            ->each(function ($supply) use ($providers) {
                $supply->provider_id = $providers->random()->id;
                $supply->save();
            });
    }
}
