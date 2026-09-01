<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            ['name' => 'Coca Cola', 'size' => '190 ml', 'is_returnable' => true],
            ['name' => 'Coca Cola', 'size' => '300 ml', 'is_returnable' => true],
            ['name' => 'Coca Cola', 'size' => '500 ml', 'is_returnable' => false],
            ['name' => 'Coca Cola', 'size' => '600 ml', 'is_returnable' => false],
            ['name' => 'Coca Cola', 'size' => '1000 ml', 'is_returnable' => true],
            ['name' => 'Coca Cola', 'size' => '2000 ml', 'is_returnable' => false],
            ['name' => 'Coca Cola', 'size' => '2500 ml', 'is_returnable' => false],
            ['name' => 'Coca Cola', 'size' => '3000 ml', 'is_returnable' => false],
            ['name' => 'Fanta', 'size' => '2000 ml', 'is_returnable' => false],
            ['name' => 'Sprite', 'size' => '2000 ml', 'is_returnable' => false],
            ['name' => 'Agua', 'size' => '600 ml', 'is_returnable' => false],
        ];

        foreach ($productos as $producto) {
            Product::create($producto);
        }
    }
}