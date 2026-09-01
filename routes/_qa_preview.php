<?php
// TEMPORARY - responsive QA harness. Delete before committing.

use Illuminate\Support\Facades\Route;

Route::middleware('admin.auth')->prefix('_qa')->group(function () {

    $user = fn (int $i) => [
        'id' => $i,
        'name' => ['Alexandra Kowalczyk-Ferreira', 'Bo Li', 'Priyanka Ramachandran'][$i % 3],
        'email' => ['alexandra.kowalczyk.ferreira@university-of-somewhere.edu', 'bo@x.io', 'p.ramachandran@example.org'][$i % 3],
        'role' => ['student', 'writer', 'manager'][$i % 3],
        'order_count' => 12 * $i,
        'total_spent' => 4821.50 * $i,
        'created_at' => '2025-03-0' . (($i % 8) + 1) . ' 10:00:00',
    ];

    $order = fn (int $i) => [
        'id' => $i,
        'order_number' => 'ORD-2026-' . str_pad((string) (1000 + $i), 5, '0', STR_PAD_LEFT),
        'user' => $user($i),
        'subject' => ['Comparative Constitutional Law', 'Thermodynamics', 'Nursing Ethics'][$i % 3],
        'title' => 'A Very Long Dissertation Title About Comparative Constitutional Law In Post-Colonial States',
        'deadline' => '2026-10-1' . ($i % 9) . ' 23:59:00',
        'tutor_deadline' => '2026-10-0' . ($i % 9) . ' 12:00:00',
        'status' => ['pending', 'in_progress', 'completed', 'revision'][$i % 4],
        'payment_status' => ['unpaid', 'partial', 'paid'][$i % 3],
        'budget' => 1249.99 * $i,
        'amount_paid' => 620.00 * $i,
        'amount_due' => 629.99 * $i,
        'academic_level' => 'Postgraduate',
        'assignment_type' => 'Dissertation',
        'pages' => 42,
        'word_count' => 11550,
        'description' => str_repeat('Detailed brief for the writer. ', 25),
        'files' => [['original_name' => 'an-extremely-long-attachment-filename-for-testing.docx', 'file_size_formatted' => '2.4 MB']],
        'messages' => [1, 2, 3],
        'tutor_id' => 2,
        'writer' => ['name' => 'Bo Li'],
    ];

    $paginate = fn (array $rows) => [
        'data' => $rows, 'total' => 137, 'current_page' => 2, 'last_page' => 12,
    ];

    Route::get('/orders', fn () => view('orders.index', [
        'orders' => $paginate(array_map($order, [1, 2, 3])),
        'filters' => ['search' => 'law', 'status' => 'pending', 'payment_status' => ''],
    ]));

    Route::get('/orders/show', fn () => view('orders.show', [
        'order' => $order(1),
        'writers' => [['id' => 2, 'name' => 'Bo Li', 'active_assignments' => 4]],
    ]));

    Route::get('/users', fn () => view('users.index', [
        'users' => $paginate(array_map($user, [1, 2, 3])),
        'filters' => ['search' => '', 'role' => 'student'],
    ]));

    Route::get('/users/show', fn () => view('users.show', [
        'user' => $user(1) + ['orders' => array_map($order, [1, 2, 3])],
    ]));

    Route::get('/messages', fn () => view('messages.index', [
        'threads' => $paginate(array_map(fn ($i) => [
            'id' => $i,
            'user' => $user($i),
            'order_number' => 'ORD-2026-0100' . $i,
            'title' => 'Comparative Constitutional Law dissertation, chapter three',
            'last_message' => ['body' => str_repeat('Could you please confirm the revised deadline? ', 3), 'created_at' => '2026-08-30 09:00:00'],
            'unread_count' => $i,
        ], [1, 2, 3])),
        'filters' => ['search' => '', 'unread' => 1],
    ]));

    Route::get('/messages/show', fn () => view('messages.show', [
        'thread' => [
            'assignment' => $order(1),
            'messages' => array_map(fn ($i) => [
                'sender' => ['name' => $i % 2 ? 'QA Responsive' : 'Alexandra Kowalczyk-Ferreira'],
                'body' => str_repeat('This is a fairly long chat message used to test bubble wrapping. ', 3),
                'created_at' => '2026-08-2' . $i . ' 11:00:00',
            ], [0, 1, 2]),
        ],
    ]));

    Route::get('/writers', fn () => view('writers.index', [
        'writers' => array_map(fn ($i) => [
            'id' => $i,
            'name' => 'Dr. Alexandra Kowalczyk-Ferreira',
            'email' => 'a.k.ferreira@example.edu',
            'title' => 'PhD in Comparative Constitutional Law',
            'rating' => 4.9,
            'bio' => str_repeat('Fifteen years of academic writing experience. ', 3),
            'expertise' => ['Law', 'Politics', 'History'],
            'active_assignments' => 7 * $i,
            'completed' => 214 * $i,
        ], [1, 2, 3]),
    ]));

    Route::get('/writers/edit', fn () => view('writers.edit', [
        'writer' => [
            'id' => 1,
            'name' => 'Dr. Alexandra Kowalczyk-Ferreira',
            'email' => 'a.k.ferreira@example.edu',
            'title' => 'PhD',
            'bio' => 'Bio',
            'expertise' => ['Law'],
            'rating' => 4.9,
            'experience_years' => 15,
            'completed_projects' => 214,
            'photo' => null,
        ],
    ]));

    Route::get('/services', fn () => view('services-editor.index', [
        'services' => array_map(fn ($i) => [
            'id' => $i,
            'name' => 'Nursing Assignment Help',
            'slug' => 'nursing-assignment-help',
            'icon' => "\u{1FA7A}",
            'short_description' => str_repeat('Expert nursing writers available. ', 2),
            'base_price_per_page' => '12.50',
            'rating' => 4.8,
            'is_active' => $i % 2 === 0,
        ], [1, 2, 3]),
    ]));

    Route::get('/services/edit', fn () => view('services-editor.edit', [
        'id' => 1,
        'service' => [
            'id' => 1,
            'name' => 'Nursing Assignment Help',
            'slug' => 'nursing-assignment-help',
            'icon' => "\u{1FA7A}",
            'short_description' => 'Expert nursing writers',
            'base_price_per_page' => '12.50',
            'rating' => 4.8,
            'is_active' => true,
            'details' => [],
        ],
    ]));

    Route::get('/cms/edit', fn () => view('cms.edit', [
        'page' => 'home',
        'pageName' => 'Home Page',
        'sectionTypes' => [
            'hero' => ['label' => 'Hero', 'description' => 'Big headline and call to action'],
            'faq' => ['label' => 'FAQ', 'description' => 'Question and answer list'],
            'stats' => ['label' => 'Stats', 'description' => 'Numbers row'],
        ],
        'sections' => [
            [
                'id' => 1, 'type' => 'hero', 'label' => 'Homepage hero banner', 'is_active' => true,
                'data' => ['heading' => 'Hi', 'subheading' => 'There', 'cta_text' => 'Go', 'cta_link' => '/x'],
            ],
            [
                'id' => 2, 'type' => 'faq', 'label' => 'Frequently asked questions', 'is_active' => false,
                'data' => ['items' => [['question' => 'Q?', 'answer' => 'A.']]],
            ],
        ],
        'meta' => ['meta_title' => 'Home', 'meta_description' => 'Desc', 'robots' => 'index, follow'],
        'revisions' => [
            [
                'user_name' => 'QA Responsive', 'created_at' => '2026-08-30 10:00:00', 'action' => 'update',
                'summary' => 'Updated the hero heading', 'new_data' => ['heading' => 'Hi'],
            ],
        ],
    ]));

    Route::get('/cms', fn () => view('cms.index', [
        'builtInPages' => [
            'home' => ['name' => 'Home Page', 'icon' => "\u{1F3E0}", 'description' => 'Main landing page'],
            'faq' => ['name' => 'FAQ', 'icon' => "\u{2753}", 'description' => 'Frequently asked questions'],
        ],
        'dynamicPages' => [
            ['name' => 'Nursing Assignment Help', 'slug' => 'nursing-assignment-help', 'is_active' => true],
            ['name' => 'Programming Homework Help', 'slug' => 'programming-homework-help', 'is_active' => false],
        ],
    ]));

    Route::get('/dashboard', fn () => view('dashboard.index', [
        'role' => 'admin',
        'stats' => [
            'total_orders' => 13742, 'total_revenue' => 1284932.55, 'open_tickets' => 42,
            'total_writers' => 128, 'pending_orders' => 76, 'unassigned_orders' => 19,
            'completed_orders' => 11204, 'total_users' => 8321,
        ],
    ]));
});
