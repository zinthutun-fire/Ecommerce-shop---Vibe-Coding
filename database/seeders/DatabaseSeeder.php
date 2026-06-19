<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'phone' => '+1234567890',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $customer = User::create([
            'name' => 'John Doe',
            'email' => 'customer@example.com',
            'password' => bcrypt('password'),
            'phone' => '+0987654321',
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        Address::create([
            'user_id' => $customer->id,
            'type' => 'shipping',
            'name' => 'John Doe',
            'phone' => '+0987654321',
            'street' => '123 Main Street',
            'city' => 'New York',
            'state' => 'NY',
            'zip' => '10001',
            'country' => 'US',
            'is_default' => true,
        ]);

        $electronics = Category::create(['name' => 'Electronics', 'slug' => 'electronics', 'description' => 'Electronic gadgets and devices', 'is_active' => true, 'sort_order' => 1]);
        $clothing = Category::create(['name' => 'Clothing', 'slug' => 'clothing', 'description' => 'Fashion and apparel', 'is_active' => true, 'sort_order' => 2]);
        $home = Category::create(['name' => 'Home & Garden', 'slug' => 'home-garden', 'description' => 'Home improvement and garden', 'is_active' => true, 'sort_order' => 3]);
        $sports = Category::create(['name' => 'Sports & Outdoors', 'slug' => 'sports-outdoors', 'description' => 'Sports equipment and outdoor gear', 'is_active' => true, 'sort_order' => 4]);

        $phones = Category::create(['name' => 'Phones', 'slug' => 'phones', 'description' => 'Mobile phones and accessories', 'parent_id' => $electronics->id, 'is_active' => true, 'sort_order' => 1]);
        $laptops = Category::create(['name' => 'Laptops', 'slug' => 'laptops', 'description' => 'Laptops and notebooks', 'parent_id' => $electronics->id, 'is_active' => true, 'sort_order' => 2]);
        Category::create(['name' => 'Men', 'slug' => 'men', 'description' => 'Men\'s clothing', 'parent_id' => $clothing->id, 'is_active' => true, 'sort_order' => 1]);
        Category::create(['name' => 'Women', 'slug' => 'women', 'description' => 'Women\'s clothing', 'parent_id' => $clothing->id, 'is_active' => true, 'sort_order' => 2]);

        $products = [
            ['name' => 'Wireless Headphones', 'slug' => 'wireless-headphones', 'description' => 'Premium wireless headphones with noise cancellation and 30-hour battery life. Experience crystal-clear audio with deep bass.', 'short_description' => 'Premium wireless headphones with ANC', 'price' => 149.99, 'compare_price' => 199.99, 'sku' => 'ELEC-001', 'stock_quantity' => 50, 'category_id' => $electronics->id, 'featured' => true],
            ['name' => 'Smart Watch Pro', 'slug' => 'smart-watch-pro', 'description' => 'Advanced smartwatch with health monitoring, GPS, and water resistance. Track your fitness goals.', 'short_description' => 'Advanced smartwatch with health monitoring', 'price' => 249.99, 'compare_price' => 299.99, 'sku' => 'ELEC-002', 'stock_quantity' => 30, 'category_id' => $electronics->id, 'featured' => true],
            ['name' => 'iPhone 15 Pro Case', 'slug' => 'iphone-15-pro-case', 'description' => 'Premium protective case for iPhone 15 Pro with magsafe compatibility and drop protection.', 'short_description' => 'Premium protective case for iPhone 15 Pro', 'price' => 39.99, 'sku' => 'PHN-001', 'stock_quantity' => 100, 'category_id' => $phones->id],
            ['name' => 'USB-C Charging Cable', 'slug' => 'usb-c-cable', 'description' => 'Fast charging USB-C cable, 6ft length with braided nylon construction for durability.', 'short_description' => 'Fast charging USB-C cable, 6ft', 'price' => 14.99, 'sku' => 'ELEC-003', 'stock_quantity' => 200, 'category_id' => $electronics->id],
            ['name' => 'MacBook Air M3', 'slug' => 'macbook-air-m3', 'description' => 'Apple MacBook Air with M3 chip, 13.6-inch Liquid Retina display, 8GB RAM, 256GB SSD.', 'short_description' => 'Apple MacBook Air with M3 chip', 'price' => 1099.00, 'compare_price' => 1299.00, 'sku' => 'LAP-001', 'stock_quantity' => 15, 'category_id' => $laptops->id, 'featured' => true],
            ['name' => 'Cotton T-Shirt', 'slug' => 'cotton-t-shirt', 'description' => 'Soft, breathable 100% organic cotton t-shirt. Available in multiple colors.', 'short_description' => 'Soft organic cotton t-shirt', 'price' => 29.99, 'sku' => 'CLTH-001', 'stock_quantity' => 150, 'category_id' => $clothing->id],
            ['name' => 'Denim Jacket', 'slug' => 'denim-jacket', 'description' => 'Classic denim jacket with a modern fit. Features button closure and multiple pockets.', 'short_description' => 'Classic denim jacket modern fit', 'price' => 89.99, 'compare_price' => 119.99, 'sku' => 'CLTH-002', 'stock_quantity' => 40, 'category_id' => $clothing->id, 'featured' => true],
            ['name' => 'Running Shoes', 'slug' => 'running-shoes', 'description' => 'Lightweight running shoes with responsive cushioning and breathable mesh upper.', 'short_description' => 'Lightweight running shoes', 'price' => 119.99, 'sku' => 'SPRT-001', 'stock_quantity' => 60, 'category_id' => $sports->id, 'featured' => true],
            ['name' => 'Yoga Mat', 'slug' => 'yoga-mat', 'description' => 'Non-slip exercise yoga mat with alignment lines. 6mm thickness for comfort.', 'short_description' => 'Non-slip exercise yoga mat', 'price' => 34.99, 'sku' => 'SPRT-002', 'stock_quantity' => 80, 'category_id' => $sports->id],
            ['name' => 'Plant Pot Set', 'slug' => 'plant-pot-set', 'description' => 'Set of 3 ceramic plant pots with drainage holes. Modern minimalist design.', 'short_description' => 'Set of 3 ceramic plant pots', 'price' => 45.99, 'sku' => 'HOME-001', 'stock_quantity' => 35, 'category_id' => $home->id],
            ['name' => 'LED Desk Lamp', 'slug' => 'led-desk-lamp', 'description' => 'Adjustable LED desk lamp with touch control, 5 brightness levels, and USB charging port.', 'short_description' => 'Adjustable LED desk lamp with USB', 'price' => 59.99, 'compare_price' => 79.99, 'sku' => 'HOME-002', 'stock_quantity' => 45, 'category_id' => $home->id],
            ['name' => 'Bluetooth Speaker', 'slug' => 'bluetooth-speaker', 'description' => 'Portable waterproof Bluetooth speaker with 360-degree sound and 12-hour battery.', 'short_description' => 'Portable waterproof Bluetooth speaker', 'price' => 79.99, 'compare_price' => 99.99, 'sku' => 'ELEC-004', 'stock_quantity' => 55, 'category_id' => $electronics->id],
        ];

        foreach ($products as $i => $data) {
            $product = Product::create($data);
            $imgPath = public_path("images/products/product-" . ($i + 1) . ".jpg");
            if (file_exists($imgPath)) {
                $product->addMedia($imgPath)->preservingOriginal()->toMediaCollection('products');
            }
        }

        Coupon::create(['code' => 'WELCOME10', 'type' => 'percentage', 'value' => 10, 'min_order_amount' => 50, 'max_uses' => 100, 'is_active' => true, 'expires_at' => now()->addYear()]);
        Coupon::create(['code' => 'SAVE20', 'type' => 'fixed', 'value' => 20, 'min_order_amount' => 100, 'max_uses' => 50, 'is_active' => true, 'expires_at' => now()->addMonths(6)]);
        Coupon::create(['code' => 'FREESHIP', 'type' => 'fixed', 'value' => 9.99, 'min_order_amount' => 50, 'is_active' => true, 'expires_at' => now()->addMonths(3)]);
    }
}
