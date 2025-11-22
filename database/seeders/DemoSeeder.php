<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // --- wipe (optional; comment out if you have data you want to keep)
            DB::table('payments')->delete();
            DB::table('order_items')->delete();
            DB::table('orders')->delete();
            DB::table('inventory_movements')->delete();
            DB::table('category_product')->delete();
            DB::table('products')->delete();
            DB::table('categories')->delete();
            DB::table('customer_profiles')->delete();
            DB::table('customers')->delete();

            $now = Carbon::now();

            // ----------------- Customers + Profiles (1:1) -----------------
            $customers = [];
            for ($i = 1; $i <= 5; $i++) {
                $id = DB::table('customers')->insertGetId([
                    'name'       => fake()->name(),
                    'email'      => fake()->unique()->safeEmail(),
                    'phone'      => fake()->phoneNumber(),
                    'created_at' => $now,
                    'updated_at' => $now,
                    'deleted_at' => null,
                ]);
                $customers[] = $id;

                DB::table('customer_profiles')->insert([
                    'customer_id'      => $id,
                    'dob'              => fake()->date(),
                    'gender'           => fake()->randomElement(['male','female']),
                    'billing_address'  => fake()->address(),
                    'shipping_address' => fake()->address(),
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ]);
            }

            // ----------------- Categories (self-referencing) -----------------
            $catElectronics = DB::table('categories')->insertGetId([
                'name'       => 'Electronics',
                'parent_id'  => null,
                'position'   => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $catPhones = DB::table('categories')->insertGetId([
                'name'       => 'Phones',
                'parent_id'  => $catElectronics,
                'position'   => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $catLaptops = DB::table('categories')->insertGetId([
                'name'       => 'Laptops',
                'parent_id'  => $catElectronics,
                'position'   => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $catAccessories = DB::table('categories')->insertGetId([
                'name'       => 'Accessories',
                'parent_id'  => $catElectronics,
                'position'   => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // ----------------- Products -----------------
            $products = [];
            for ($i = 1; $i <= 10; $i++) {
                $id = DB::table('products')->insertGetId([
                    'sku'         => 'SKU-'.strtoupper(Str::padLeft((string)$i, 4, '0')),
                    'name'        => fake()->words(2, true),
                    'description' => fake()->sentence(),
                    'price'       => fake()->randomFloat(2, 200, 5000),
                    'stock'       => fake()->numberBetween(5, 40),
                    'active'      => true,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                    'deleted_at'  => null,
                ]);
                $products[] = $id;

                // Attach to 1–2 categories (N:M)
                $attach = collect([$catPhones, $catLaptops, $catAccessories])->shuffle()->take(rand(1,2));
                foreach ($attach as $cid) {
                    DB::table('category_product')->insert([
                        'category_id' => $cid,
                        'product_id'  => $id,
                    ]);
                }

                // Inventory movements (aggregate + inc/dec)
                DB::table('inventory_movements')->insert([
                    [
                        'product_id' => $id,
                        'qty_change' => fake()->numberBetween(10, 30),
                        'reason'     => 'initial stock',
                        'created_at' => $now->copy()->subDays(rand(15, 30)),
                        'updated_at' => $now,
                    ],
                    [
                        'product_id' => $id,
                        'qty_change' => -fake()->numberBetween(1, 5),
                        'reason'     => 'sale',
                        'created_at' => $now->copy()->subDays(rand(1, 14)),
                        'updated_at' => $now,
                    ],
                ]);
            }

            // ----------------- Orders + Items (1:N) -----------------
            $statuses = ['pending','paid','shipped','completed','canceled'];
            $orders = [];
            for ($i = 1; $i <= 5; $i++) {
                $customerId = $customers[array_rand($customers)];
                $orderNo = 'ORD-'.date('Y').'-'.strtoupper(Str::random(6));

                // Build a couple of items
                $chosenProducts = collect($products)->shuffle()->take(rand(1,3))->values();
                $subTotal = 0;

                foreach ($chosenProducts as $pid) {
                    $price = (float) DB::table('products')->where('id', $pid)->value('price');
                    $qty   = rand(1, 3);
                    $line  = $price * $qty;
                    $subTotal += $line;
                }

                $discount = round($subTotal * 0.05, 2);
                $tax      = round(($subTotal - $discount) * 0.1, 2);
                $grand    = round($subTotal - $discount + $tax, 2);

                $orderId = DB::table('orders')->insertGetId([
                    'customer_id' => $customerId,
                    'order_no'    => $orderNo,
                    'status'      => $statuses[array_rand($statuses)],
                    'sub_total'   => $subTotal,
                    'discount'    => $discount,
                    'tax'         => $tax,
                    'grand_total' => $grand,
                    'created_at'  => $now->copy()->subDays(rand(1, 20)),
                    'updated_at'  => $now,
                ]);
                $orders[] = $orderId;

                // Insert items
                foreach ($chosenProducts as $pid) {
                    $price = (float) DB::table('products')->where('id', $pid)->value('price');
                    $qty   = rand(1, 3);
                    DB::table('order_items')->insert([
                        'order_id'   => $orderId,
                        'product_id' => $pid,
                        'qty'        => $qty,
                        'unit_price' => $price,
                        'line_total' => round($price * $qty, 2),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }

                // ----------------- Payments (only-one-to-many via latestOfMany) -----------------
                // two payments to demonstrate latest
                DB::table('payments')->insert([
                    'order_id'  => $orderId,
                    'gateway'   => 'Stripe',
                    'reference' => strtoupper(Str::random(10)),
                    'amount'    => round($grand * 0.5, 2),
                    'status'    => 'authorized',
                    'created_at'=> $now->copy()->subDays(2),
                    'updated_at'=> $now,
                ]);
                DB::table('payments')->insert([
                    'order_id'  => $orderId,
                    'gateway'   => 'Stripe',
                    'reference' => strtoupper(Str::random(10)),
                    'amount'    => $grand,
                    'status'    => 'captured',
                    'created_at'=> $now->copy()->subDay(), // later one → latestOfMany()
                    'updated_at'=> $now,
                ]);
            }
        });
    }
}
