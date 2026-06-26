<?php

namespace App\Http\Controllers;

use App\Services\AdminApiService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private AdminApiService $api;

    public function __construct()
    {
        $this->api = new AdminApiService();
    }

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'payment_status', 'search', 'page']);
        $orders  = $this->api->getOrders($filters);
        return view('orders.index', compact('orders', 'filters'));
    }

    public function show(int $id)
    {
        $order   = $this->api->getOrder($id);
        $writers = $this->api->getWriters();
        return view('orders.show', compact('order', 'writers'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate(['status' => 'required|string']);
        $this->api->updateOrderStatus($id, $request->status);
        return back()->with('success', 'Status updated.');
    }

    public function assignWriter(Request $request, int $id)
    {
        $request->validate(['tutor_id' => 'required|integer']);
        $this->api->assignWriter($id, $request->tutor_id, $request->tutor_deadline);
        return back()->with('success', 'Writer assigned.');
    }

    public function updatePayment(Request $request, int $id)
    {
        $request->validate(['payment_status' => 'required|string']);
        $this->api->updatePayment($id, $request->payment_status, $request->amount_paid);
        return back()->with('success', 'Payment updated.');
    }
}
