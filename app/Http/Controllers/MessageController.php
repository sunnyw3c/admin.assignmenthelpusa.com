<?php

namespace App\Http\Controllers;

use App\Services\AdminApiService;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    private AdminApiService $api;

    public function __construct()
    {
        $this->api = new AdminApiService();
    }

    public function index(Request $request)
    {
        $filters  = $request->only(['search', 'unread', 'page']);
        $threads  = $this->api->getMessages($filters);
        return view('messages.index', compact('threads', 'filters'));
    }

    public function show(int $id)
    {
        $thread = $this->api->getMessage($id);
        return view('messages.show', compact('thread'));
    }

    public function reply(Request $request, int $id)
    {
        $request->validate(['body' => 'required|string|max:5000']);
        $this->api->replyMessage($id, $request->body);
        return back()->with('success', 'Reply sent.');
    }
}
