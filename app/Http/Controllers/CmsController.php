<?php

namespace App\Http\Controllers;

use App\Services\AdminApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CmsController extends Controller
{
    private array $builtInPages = [
        'home'         => ['name' => 'Home Page', 'icon' => '🏠', 'description' => 'Main landing page'],
        'faq'          => ['name' => 'FAQ', 'icon' => '❓', 'description' => 'Frequently asked questions'],
        'pricing'      => ['name' => 'Pricing', 'icon' => '💰', 'description' => 'Pricing plans'],
        'how-it-works' => ['name' => 'How It Works', 'icon' => '⚙️', 'description' => 'Process steps & features'],
        'about'        => ['name' => 'About', 'icon' => '👥', 'description' => 'About us page'],
    ];

    public function index(AdminApiService $api)
    {
        $dynamicPages = $api->getPages();

        return view('cms.index', [
            'builtInPages' => $this->builtInPages,
            'dynamicPages' => $dynamicPages,
        ]);
    }

    public function edit(AdminApiService $api, string $page)
    {
        $data = $api->getPageSections($page);
        $sections = $data['sections'] ?? [];
        $sectionTypes = $api->getSectionTypes();
        $meta = $api->getPageMeta($page);
        $revisions = $api->getPageRevisions($page);
        $pageName = $this->builtInPages[$page]['name'] ?? ucwords(str_replace('-', ' ', $page));

        return view('cms.edit', compact('page', 'pageName', 'sections', 'sectionTypes', 'meta', 'revisions'));
    }

    public function addSection(AdminApiService $api, Request $request, string $page)
    {
        $request->validate(['type' => 'required|string', 'label' => 'nullable|string']);
        $api->addSection($page, $request->type, $request->input('label', ''));
        return redirect()->route('cms.edit', $page)->with('success', 'Section added.');
    }

    public function updateSection(AdminApiService $api, Request $request, int $id)
    {
        $page = $request->input('page');
        $label = $request->input('label', '');

        // Collect data fields — everything under data[]
        $rawData = $request->input('data', []);

        // JSON-decode any string values (from Alpine hidden inputs)
        $data = [];
        foreach ($rawData as $key => $value) {
            if (is_string($value) && str_starts_with(trim($value), '[') || str_starts_with(trim($value ?? ''), '{')) {
                $decoded = json_decode($value, true);
                $data[$key] = $decoded !== null ? $decoded : $value;
            } else {
                $data[$key] = $value;
            }
        }

        $api->updateSection($id, $data, $label);
        return redirect()->route('cms.edit', $page)->with('success', 'Section saved.');
    }

    public function deleteSection(AdminApiService $api, Request $request, int $id)
    {
        $page = $request->input('page');
        $api->deleteSection($id);
        return redirect()->route('cms.edit', $page)->with('success', 'Section deleted.');
    }

    public function toggleSection(AdminApiService $api, Request $request, int $id)
    {
        $api->toggleSection($id);
        return response()->json(['ok' => true]);
    }

    public function reorderSections(AdminApiService $api, Request $request, string $page)
    {
        $ids = $request->input('ids', []);
        $api->bulkReorderSections($page, $ids);
        return response()->json(['ok' => true]);
    }

    public function updateMeta(AdminApiService $api, Request $request, string $page)
    {
        $api->updatePageMeta($page, $request->only([
            'meta_title', 'meta_description', 'og_title', 'og_description',
            'og_image', 'canonical_url', 'keywords', 'robots',
        ]));
        return redirect()->route('cms.edit', $page)->with('success', 'SEO meta saved.');
    }

    // Dynamic pages
    public function createPage(AdminApiService $api, Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string',
        ]);

        $slug = Str::slug($request->slug);
        $api->createPage($slug, $request->name);

        return redirect()->route('cms.edit', $slug)->with('success', 'Page created. Add sections now.');
    }

    public function togglePage(AdminApiService $api, Request $request, string $slug)
    {
        $api->updatePage($slug, ['name' => $request->name, 'is_active' => (bool) $request->input('is_active', false)]);
        return redirect()->route('cms.index')->with('success', 'Page updated.');
    }

    public function deletePage(AdminApiService $api, string $slug)
    {
        $api->deletePage($slug);
        return redirect()->route('cms.index')->with('success', 'Page deleted.');
    }
}
