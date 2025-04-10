<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        // Retrieve all orders with their items and user details
        $orders = Order::with(['items.product', 'user'])->orderBy('created_at', 'desc')->get();

        return view('admin.orders.index', compact('orders'));
    }

    public function markAsCompleted(Order $order)
    {
        if ($order->status === 'pending') {
            $order->status = 'completed';
            $order->save();

            return redirect()->back()->with('success', 'Order marked as completed.');
        }

        return redirect()->back()->with('error', 'Only pending orders can be marked as completed.');
    }
}
