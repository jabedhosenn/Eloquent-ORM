<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function summary()
    {
        $startDate = request()->query('start_date');
        $endDate = request()->query('end_date');

        if (! $startDate && ! $endDate) {
            // Logic to generate report summary between the given dates
            // This is a placeholder for actual report generation logic
            // $startDate = now()->subMonth()->toDateString();
            // $endDate = now()->toDateString();

            $startDate = now()->subDays(30)->toDateString();
            $endDate = now()->toDateString();
        }

        // Base Query
        $orderQuery = Order::query()
            ->betweenDates($startDate, $endDate);


        $totalCustomers =Customer::count();
        $totalOrders = (clone $orderQuery)->count();
        // $totalOrdersAgain = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalRevenue = (clone $orderQuery)->sum('grand_total');

        // $totalPaid = (clone $orderQuery)->get()->sum(function ($order) {
        //     return $order->totalPaidAmount();
        // });
        // $totalDue = (clone $orderQuery)->get()->sum(function ($order) {
        //     return $order->dueAmount();
        // });

        $totalPaid = Payment::whereHas('order', function ($query) use ($startDate, $endDate) {
            $query->betweenDates($startDate, $endDate);
        })->sum('amount');

        $totalDue = $totalRevenue - $totalPaid;

        // $totalDue = (clone $orderQuery)->get()->sum(function ($order) {
        //     return $order->dueAmount();
        // });

        $revenueByStatus = (clone $orderQuery)
            ->selectRaw('status, COUNT(*) as total_orders, SUM(grand_total) as total_revenue')
            ->groupBy('status')
            ->get();

        return response()->json([
            'total_customers' => $totalCustomers,
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'total_paid' => $totalPaid,
            'total_due' => $totalDue,
            'revenue_by_status' => $revenueByStatus,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }
}
