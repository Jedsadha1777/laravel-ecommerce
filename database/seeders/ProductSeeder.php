<?php

namespace Database\Seeders;

use App\Domains\Admin\Product\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'iPhone 15 Pro',
                'slug' => 'iphone-15-pro',
                'description' => 'Latest iPhone with titanium design',
                'price' => 42900,
                'stock' => 50,
                'sku' => 'IPH15PRO',
                'images' => json_encode(['iphone15pro-1.jpg', 'iphone15pro-2.jpg']),
            ],
            [
                'name' => 'MacBook Pro M3',
                'slug' => 'macbook-pro-m3',
                'description' => 'Powerful laptop for professionals',
                'price' => 89900,
                'stock' => 20,
                'sku' => 'MBP-M3',
                'images' => json_encode(['macbook-m3-1.jpg']),
            ],
            [
                'name' => 'AirPods Pro 2',
                'slug' => 'airpods-pro-2',
                'description' => 'Wireless earbuds with ANC',
                'price' => 8990,
                'stock' => 100,
                'sku' => 'APP2',
                'images' => json_encode(['airpods-pro-2.jpg']),
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}