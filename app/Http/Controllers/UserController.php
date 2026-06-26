<?php

namespace App\Http\Controllers;

use App\Services\AdminApiService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private AdminApiService $api;

    public function __construct()
    {
        $this->api = new AdminApiService();
    }

    public function index(Request $request)
    {
        $filters = $request->only(['role', 'search', 'page']);
        $users   = $this->api->getUsers($filters);
        return view('users.index', compact('users', 'filters'));
    }

    public function show(int $id)
    {
        $user = $this->api->getUser($id);
        return view('users.show', compact('user'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate(['name' => 'required|string', 'email' => 'required|email']);
        $this->api->updateUser($id, $request->only('name', 'email'));
        return back()->with('success', 'User updated.');
    }

    public function updateRole(Request $request, int $id)
    {
        $request->validate(['role' => 'required|string']);
        $this->api->updateUserRole($id, $request->role);
        return back()->with('success', 'Role updated.');
    }
}
