<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AdminApiService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('app.main_api_url'), '/') . '/api/admin';
    }

    private function client()
    {
        return Http::baseUrl($this->baseUrl)
            ->withToken(Session::get('admin_token', ''))
            ->withoutVerifying()
            ->acceptJson()
            ->timeout(15);
    }

    // Auth
    public function login(string $email, string $password): array
    {
        $response = Http::baseUrl($this->baseUrl)
            ->withoutVerifying()
            ->acceptJson()
            ->post('/login', compact('email', 'password'));

        return ['status' => $response->status(), 'data' => $response->json()];
    }

    public function logout(): void
    {
        $this->client()->post('/logout');
    }

    public function me(): ?array
    {
        $response = $this->client()->get('/me');
        return $response->ok() ? $response->json() : null;
    }

    // Stats
    public function getStats(): array
    {
        return $this->client()->get('/stats')->json() ?? [];
    }

    // Orders
    public function getOrders(array $filters = []): array
    {
        return $this->client()->get('/orders', $filters)->json() ?? [];
    }

    public function getOrder(int $id): array
    {
        return $this->client()->get("/orders/{$id}")->json() ?? [];
    }

    public function updateOrderStatus(int $id, string $status): array
    {
        return $this->client()->patch("/orders/{$id}/status", compact('status'))->json() ?? [];
    }

    public function assignWriter(int $id, int $tutorId, ?string $tutorDeadline = null): array
    {
        return $this->client()->patch("/orders/{$id}/assign", [
            'tutor_id'       => $tutorId,
            'tutor_deadline' => $tutorDeadline,
        ])->json() ?? [];
    }

    public function updatePayment(int $id, string $paymentStatus, ?float $amountPaid = null): array
    {
        $data = ['payment_status' => $paymentStatus];
        if ($amountPaid !== null) {
            $data['amount_paid'] = $amountPaid;
        }
        return $this->client()->patch("/orders/{$id}/payment", $data)->json() ?? [];
    }

    // Users
    public function getUsers(array $filters = []): array
    {
        return $this->client()->get('/users', $filters)->json() ?? [];
    }

    public function getUser(int $id): array
    {
        return $this->client()->get("/users/{$id}")->json() ?? [];
    }

    public function updateUser(int $id, array $data): array
    {
        return $this->client()->patch("/users/{$id}", $data)->json() ?? [];
    }

    public function updateUserRole(int $id, string $role): array
    {
        return $this->client()->patch("/users/{$id}/role", compact('role'))->json() ?? [];
    }

    // Writers
    public function getWriters(): array
    {
        return $this->client()->get('/writers')->json() ?? [];
    }

    public function getWriter(int $id): array
    {
        return $this->client()->get("/writers/{$id}")->json() ?? [];
    }

    public function createWriter(array $data): array
    {
        $request = $this->client()->asMultipart();

        foreach ($data as $key => $value) {
            if ($key === 'photo' && $value instanceof \Illuminate\Http\UploadedFile) {
                $request = $request->attach('photo', file_get_contents($value->getRealPath()), $value->getClientOriginalName());
            } elseif (is_array($value)) {
                foreach ($value as $item) {
                    $request = $request->attach("{$key}[]", $item);
                }
            } else {
                $request = $request->attach($key, (string) $value);
            }
        }

        $response = $request->post('/writers');
        return ['status' => $response->status(), 'data' => $response->json()];
    }

    public function updateWriter(int $id, array $data): array
    {
        $request = $this->client()->asMultipart();

        foreach ($data as $key => $value) {
            if ($key === 'photo' && $value instanceof \Illuminate\Http\UploadedFile) {
                $request = $request->attach('photo', file_get_contents($value->getRealPath()), $value->getClientOriginalName());
            } elseif (is_array($value)) {
                foreach ($value as $item) {
                    $request = $request->attach("{$key}[]", $item);
                }
            } elseif ($value !== null) {
                $request = $request->attach($key, (string) $value);
            }
        }

        $response = $request->post("/writers/{$id}");
        return ['status' => $response->status(), 'data' => $response->json()];
    }

    // Messages
    public function getMessages(array $filters = []): array
    {
        return $this->client()->get('/messages', $filters)->json() ?? [];
    }

    public function getMessage(int $assignmentId): array
    {
        return $this->client()->get("/messages/{$assignmentId}")->json() ?? [];
    }

    public function replyMessage(int $assignmentId, string $body): array
    {
        return $this->client()->post("/messages/{$assignmentId}", compact('body'))->json() ?? [];
    }

    public function getUnreadCount(): int
    {
        return $this->client()->get('/messages/unread-count')->json('count', 0);
    }

    // ─── CMS: Section Types ───────────────────────────────────────────────────

    public function getSectionTypes(): array
    {
        return $this->client()->get('/cms/section-types')->json() ?? [];
    }

    // ─── CMS: Page Sections ───────────────────────────────────────────────────

    public function getPageSections(string $page): array
    {
        return $this->client()->get("/cms/{$page}/sections")->json() ?? [];
    }

    public function addSection(string $page, string $type, string $label, array $data = []): array
    {
        return $this->client()->post("/cms/{$page}/sections", compact('type', 'label', 'data'))->json() ?? [];
    }

    public function updateSection(int $id, array $data, string $label): array
    {
        return $this->client()->put("/cms/sections/{$id}", compact('data', 'label'))->json() ?? [];
    }

    public function toggleSection(int $id): array
    {
        return $this->client()->patch("/cms/sections/{$id}/toggle")->json() ?? [];
    }

    public function deleteSection(int $id): array
    {
        return $this->client()->delete("/cms/sections/{$id}")->json() ?? [];
    }

    public function bulkReorderSections(string $page, array $ids): array
    {
        return $this->client()->post("/cms/sections/reorder/{$page}", compact('ids'))->json() ?? [];
    }

    // ─── CMS: SEO Meta ────────────────────────────────────────────────────────

    public function getPageMeta(string $page): array
    {
        return $this->client()->get("/cms/{$page}/meta")->json('meta') ?? [];
    }

    public function updatePageMeta(string $page, array $meta): array
    {
        return $this->client()->put("/cms/{$page}/meta", $meta)->json() ?? [];
    }

    // ─── CMS: Revision History ────────────────────────────────────────────────

    public function getPageRevisions(string $page): array
    {
        return $this->client()->get("/cms/{$page}/revisions")->json('revisions') ?? [];
    }

    // ─── CMS: Dynamic Pages ───────────────────────────────────────────────────

    public function getPages(): array
    {
        return $this->client()->get('/cms/pages')->json('pages') ?? [];
    }

    public function createPage(string $slug, string $name): array
    {
        return $this->client()->post('/cms/pages', compact('slug', 'name'))->json() ?? [];
    }

    public function updatePage(string $slug, array $data): array
    {
        return $this->client()->put("/cms/pages/{$slug}", $data)->json() ?? [];
    }

    public function deletePage(string $slug): array
    {
        return $this->client()->delete("/cms/pages/{$slug}")->json() ?? [];
    }

    // ─── Assignment Services ──────────────────────────────────────────────────

    public function getAssignmentServices(): array
    {
        return $this->client()->get('/services-admin')->json('services') ?? [];
    }

    public function getAssignmentService(int $id): array
    {
        return $this->client()->get("/services-admin/{$id}")->json('service') ?? [];
    }

    public function createAssignmentService(array $data): array
    {
        return $this->client()->post('/services-admin', $data)->json() ?? [];
    }

    public function updateAssignmentService(int $id, array $data): array
    {
        return $this->client()->put("/services-admin/{$id}", $data)->json() ?? [];
    }

    public function updateAssignmentServiceDetails(int $id, array $data): array
    {
        return $this->client()->put("/services-admin/{$id}/details", $data)->json() ?? [];
    }

    // Mail
    public function sendPromotionalEmail(string $email): array
    {
        $response = $this->client()->post('/mail/send', compact('email'));
        return ['status' => $response->status(), 'data' => $response->json()];
    }
}
