<?php

namespace App\Http\Controllers;

use App\Services\AdminApiService;
use Illuminate\Http\Request;

class WriterController extends Controller
{
    private AdminApiService $api;

    public function __construct()
    {
        $this->api = new AdminApiService();
    }

    public function index()
    {
        $writers = $this->api->getWriters();
        return view('writers.index', compact('writers'));
    }

    public function create()
    {
        return view('writers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'required|email',
            'password'           => 'required|string|min:8',
            'title'              => 'nullable|string|max:255',
            'bio'                => 'nullable|string',
            'expertise'          => 'nullable|string',
            'rating'             => 'nullable|numeric|min:0|max:5',
            'experience_years'   => 'nullable|integer|min:0',
            'completed_projects' => 'nullable|integer|min:0',
            'photo'              => 'nullable|image|max:2048',
        ]);

        $data = $request->only('name', 'email', 'password', 'title', 'bio',
                               'rating', 'experience_years', 'completed_projects');

        // Convert comma-separated expertise string to array
        if ($request->filled('expertise')) {
            $data['expertise'] = array_map('trim', explode(',', $request->expertise));
        }

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo');
        }

        $result = $this->api->createWriter($data);

        if (($result['status'] ?? 500) === 201) {
            return redirect()->route('writers.index')->with('success', 'Writer created successfully.');
        }

        return back()->withInput()->with('error', $result['data']['message'] ?? 'Failed to create writer.');
    }

    public function edit(int $id)
    {
        $writer = $this->api->getWriter($id);
        if (empty($writer)) {
            return redirect()->route('writers.index')->with('error', 'Writer not found.');
        }
        return view('writers.edit', compact('writer'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'required|email',
            'password'           => 'nullable|string|min:8',
            'title'              => 'nullable|string|max:255',
            'bio'                => 'nullable|string',
            'expertise'          => 'nullable|string',
            'rating'             => 'nullable|numeric|min:0|max:5',
            'experience_years'   => 'nullable|integer|min:0',
            'completed_projects' => 'nullable|integer|min:0',
            'photo'              => 'nullable|image|max:2048',
        ]);

        $data = $request->only('name', 'email', 'title', 'bio',
                               'rating', 'experience_years', 'completed_projects');

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        if ($request->filled('expertise')) {
            $data['expertise'] = array_map('trim', explode(',', $request->expertise));
        }

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo');
        }

        $result = $this->api->updateWriter($id, $data);

        if (in_array($result['status'] ?? 500, [200, 201])) {
            return redirect()->route('writers.index')->with('success', 'Writer updated successfully.');
        }

        return back()->withInput()->with('error', $result['data']['message'] ?? 'Failed to update writer.');
    }
}
