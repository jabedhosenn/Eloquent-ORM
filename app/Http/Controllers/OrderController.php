<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // public function orders()
    // {
    //     $data = Customer::with('orders.order_items')
    //     ->orderBy('id', 'desc')
    //     ->get();
    //     return response()->json($data);
    // } // oneToMany

    // public function orders()
    // {
    //     $data = Customer::with(['orders.order_items', 'orders.payments', 'profile'])
    //     ->orderBy('id', 'desc')
    //     ->get();

    //     return response()->json($data);
    // } // oneToMany

    // public function orders()
    // {
    //     $data = Customer::with(['orders.order_items' => function ($query) {
    //         $query->select('order_id', 'product_id', 'qty', 'unit_price');
    //     }])
    //         ->orderBy('id', 'desc')
    //         ->get();

    //     return response()->json($data);
    // } // oneToMany

    public function orders()
    {
        $data = Customer::select('id', 'name', 'email', 'phone')
            ->with([
                'orders' => function ($query) {
                    $query->select('id', 'customer_id', 'order_no', 'status', 'tax', 'grand_total')
                    ->orderBy('id', 'desc');
                },
                'orders.order_items' => function ($query) {
                    $query->select('order_id', 'product_id', 'qty', 'unit_price')
                    ->where('unit_price', '<', 2000);
                }
            ])
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($data);
    } // oneToMany
}
