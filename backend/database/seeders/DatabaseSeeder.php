<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Create admin user
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => Hash::make('password123'), 'is_admin' => true]
        );

        // Create regular users
        User::updateOrCreate(
            ['email' => 'user@example.com'],
            ['name' => 'John Doe', 'password' => Hash::make('password123'), 'is_admin' => false]
        );

        User::updateOrCreate(
            ['email' => 'jane@example.com'],
            ['name' => 'Jane Smith', 'password' => Hash::make('password123'), 'is_admin' => false]
        );

        // Create categories
        $cakes = Category::updateOrCreate(
            ['name' => 'Cakes'],
            ['slug' => 'cakes', 'description' => 'Delicious handmade cakes', 'status' => true]
        );

        $cupcakes = Category::updateOrCreate(
            ['name' => 'Cupcakes'],
            ['slug' => 'cupcakes', 'description' => 'Mini delights', 'status' => true]
        );

        $pastries = Category::updateOrCreate(
            ['name' => 'Pastries'],
            ['slug' => 'pastries', 'description' => 'Fresh pastries', 'status' => true]
        );

        // Create products
        Product::updateOrCreate(
            ['name' => 'Chocolate Cake'],
            [
                'slug' => 'chocolate-cake',
                'description' => 'Rich chocolate cake with chocolate frosting',
                'short_description' => 'Rich chocolate delight',
                'price' => 35.99,
                'compare_price' => 45.99,
                'stock_quantity' => 25,
                'sku' => 'CAKE-001',
                'category_id' => $cakes->id,
                'status' => true,
                'featured' => true
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Vanilla Birthday Cake'],
            [
                'slug' => 'vanilla-birthday-cake',
                'description' => 'Classic vanilla cake perfect for birthdays',
                'short_description' => 'Classic vanilla birthday cake',
                'price' => 32.99,
                'stock_quantity' => 30,
                'sku' => 'CAKE-002',
                'category_id' => $cakes->id,
                'status' => true,
                'featured' => false
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Red Velvet Cupcakes'],
            [
                'slug' => 'red-velvet-cupcakes',
                'description' => 'Set of 12 red velvet cupcakes with cream cheese frosting',
                'short_description' => '12 red velvet cupcakes',
                'price' => 24.99,
                'compare_price' => 29.99,
                'stock_quantity' => 50,
                'sku' => 'CUP-001',
                'category_id' => $cupcakes->id,
                'status' => true,
                'featured' => true
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Lemon Tart'],
            [
                'slug' => 'lemon-tart',
                'description' => 'Tangy lemon tart with buttery crust',
                'short_description' => 'Fresh lemon tart',
                'price' => 18.99,
                'stock_quantity' => 15,
                'sku' => 'PAST-001',
                'category_id' => $pastries->id,
                'status' => true,
                'featured' => false
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Strawberry Shortcake'],
            [
                'slug' => 'strawberry-shortcake',
                'description' => 'Fresh strawberries with whipped cream and sponge cake',
                'short_description' => 'Fresh strawberry shortcake',
                'price' => 28.99,
                'stock_quantity' => 8,
                'sku' => 'CAKE-003',
                'category_id' => $cakes->id,
                'status' => true,
                'featured' => true
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Chocolate Chip Cookies'],
            [
                'slug' => 'chocolate-chip-cookies',
                'description' => 'Dozen of freshly baked chocolate chip cookies',
                'short_description' => '12 chocolate chip cookies',
                'price' => 12.99,
                'stock_quantity' => 100,
                'sku' => 'COOK-001',
                'category_id' => $pastries->id,
                'status' => true,
                'featured' => false
            ]
        );
    }
}
