<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::all();

        Category::factory(3)
            ->create()
            ->each(function (Category $category): void {
                Product::factory(4)
                    ->create(['category_id' => $category->id])
                    ->each(function (Product $product): void {
                        ProductImage::factory()->create(['product_id' => $product->id]);
                    });
            });

        $products = Product::all();

        $users->each(function (User $user) use ($products): void {
            $order = Order::factory()->create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total_amount' => 0,
            ]);

            $total = 0;
            $selectedProducts = $products->random(2);

            foreach ($selectedProducts as $product) {
                $quantity = rand(1, 3);
                $unitPrice = (float) $product->price;
                $subtotal = $quantity * $unitPrice;

                OrderItem::factory()->create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);

                $total += $subtotal;
            }

            $order->update([
                'total_amount' => $total,
            ]);
        });
    }
}
