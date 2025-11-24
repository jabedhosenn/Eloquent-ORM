<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function customer()
    {
        $data = Customer::with('profile')
            ->orderBy('id', 'desc')
            ->get();
        return response()->json($data);
    } // oneToOne
}
