<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function customer()
    {
        $data = Customer::with('profile')
            ->orderBy('id', 'desc')
            ->get();
        return response()->json($data);
    } // oneToOne

    // public function orders()
    // {
    //     $data = Customer::with('orders.order_items')
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
        $data = Customer::select('id','name','email','phone')
            ->with([
                'orders' => function ($query) {
                    $query->select('id','customer_id','order_no','status','grand_total')->orderBy('id','desc');
                },
                'orders.order_items' => function ($query) {
                    $query->select('order_id','product_id','qty','unit_price');
                }
            ])->orderBy('id','desc')
            ->get();

        return response()->json($data);
    }
}
