<?php

namespace App\Http\Controllers;

use App\Services\AdminApiService;
use Illuminate\Http\Request;

class ServiceEditorController extends Controller
{
    public function index(AdminApiService $api)
    {
        $services = $api->getAssignmentServices();
        return view('services-editor.index', compact('services'));
    }

    public function create()
    {
        return view('services-editor.create');
    }

    public function store(AdminApiService $api, Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string',
        ]);

        $data = $this->buildServiceData($request);
        $api->createAssignmentService($data);

        return redirect()->route('services-editor.index')->with('success', 'Service created.');
    }

    public function edit(AdminApiService $api, int $id)
    {
        $service = $api->getAssignmentService($id);
        if (empty($service)) {
            abort(404);
        }
        return view('services-editor.edit', compact('service', 'id'));
    }

    public function update(AdminApiService $api, Request $request, int $id)
    {
        $request->validate(['name' => 'required|string|max:255']);

        // Update main service fields
        $serviceData = $this->buildServiceData($request);
        $api->updateAssignmentService($id, $serviceData);

        // Update details
        $detailsData = $this->buildDetailsData($request);
        $api->updateAssignmentServiceDetails($id, $detailsData);

        return redirect()->route('services-editor.edit', $id)->with('success', 'Service updated.');
    }

    private function buildServiceData(Request $request): array
    {
        $data = $request->only([
            'name', 'slug', 'icon', 'short_description', 'long_description',
            'base_price_per_page', 'turnaround_min_hours', 'turnaround_max_hours',
            'rating', 'orders_completed', 'is_active', 'display_order',
            'meta_title', 'meta_description',
        ]);

        foreach (['features', 'academic_levels', 'subjects'] as $field) {
            $raw = $request->input($field);
            if (is_string($raw)) {
                $data[$field] = json_decode($raw, true) ?? [];
            } elseif (is_array($raw)) {
                $data[$field] = $raw;
            }
        }

        $data['is_active'] = $request->boolean('is_active');
        return array_filter($data, fn ($v) => $v !== null && $v !== '');
    }

    private function buildDetailsData(Request $request): array
    {
        $data = $request->only(['hero_title', 'hero_subtitle', 'hero_description']);

        foreach (['what_we_offer', 'pricing_tiers', 'process_steps', 'sample_topics', 'testimonials', 'faqs', 'citation_styles', 'deliverables', 'guarantees'] as $field) {
            $raw = $request->input("details_{$field}");
            if ($raw !== null) {
                $data[$field] = is_string($raw) ? (json_decode($raw, true) ?? []) : $raw;
            }
        }

        return $data;
    }
}
