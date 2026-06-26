<?php

namespace App\Http\Controllers;

use App\Services\AdminApiService;
use Illuminate\Http\Request;

class MailController extends Controller
{
    private AdminApiService $api;

    public function __construct()
    {
        $this->api = new AdminApiService();
    }

    public function index()
    {
        return view('mail.index');
    }

    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $result = $this->api->sendPromotionalEmail($request->email);

        if ($result['status'] === 200) {
            return back()->with('success', 'Promotional email sent to ' . $request->email);
        }

        $error = $result['data']['message'] ?? 'Failed to send email. Please try again.';
        return back()->withInput()->with('error', $error);
    }
}
