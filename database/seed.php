<?php

declare(strict_types=1);

/**
 * Seed — real starting content for Ziad Hassan (EN+AR).
 * Idempotent: skips when users table is already populated.
 * Run: php bin/migrate.php --seed
 */

use App\Core\DB;
use App\Core\Env;

if ((int) DB::fetchColumn('SELECT COUNT(*) FROM users') > 0) {
    echo "seed skipped — database already has data\n";
    return;
}

$j = fn (mixed $v): string => json_encode($v, JSON_UNESCAPED_UNICODE);

// ── Admin ────────────────────────────────────────────────────────────────
DB::insert('users', [
    'name' => 'Ziad Hassan',
    'email' => mb_strtolower((string) Env::get('ADMIN_EMAIL', 'admin@ziadhassan.dev')),
    'password_hash' => password_hash((string) Env::get('ADMIN_PASSWORD', 'change-me-now'), PASSWORD_ARGON2ID),
]);

// ── Sections ─────────────────────────────────────────────────────────────
$depthItems = [
    ['index' => 'D/01', 'title' => ['en' => 'Security', 'ar' => 'الأمان'], 'body' => ['en' => 'CSRF, prepared statements, Argon2id, rate limits, CSP — this site runs on a hand-built kernel that proves the discipline.', 'ar' => 'CSRF وعبارات معدّة وArgon2id وحدود معدل وCSP — هذا الموقع يعمل على نواة مبنية يدوياً تثبت الانضباط.']],
    ['index' => 'D/02', 'title' => ['en' => 'Performance', 'ar' => 'الأداء'], 'body' => ['en' => 'N+1 hunts, index design, cache strategy, render budgets. Speed is a feature with a number attached.', 'ar' => 'مطاردة N+1 وتصميم فهارس واستراتيجية تخزين مؤقت وميزانيات عرض. السرعة ميزة لها رقم.']],
    ['index' => 'D/03', 'title' => ['en' => 'Scalability', 'ar' => 'قابلية التوسع'], 'body' => ['en' => 'Tenant isolation, queue fan-out, horizontal reads — designed before they are needed, verified when they are.', 'ar' => 'عزل المستأجرين وتوزيع الطوابير والقراءات الأفقية — تُصمم قبل الحاجة وتُختبر عندها.']],
    ['index' => 'D/04', 'title' => ['en' => 'Product thinking', 'ar' => 'التفكير المنتَجي'], 'body' => ['en' => 'Features are hypotheses. I ship instrumented, reversible changes and let data argue back.', 'ar' => 'الميزات فرضيات. أُسلّم تغييرات مُقاسة وقابلة للعكس وأدع البيانات تجادل.']],
];
$aiItems = [
    ['title' => ['en' => 'RAG over tenant data', 'ar' => 'استرجاع معزّز على بيانات المستأجر'], 'body' => ['en' => 'Per-tenant vector namespaces, citation-backed answers, and hallucination guardrails in production.', 'ar' => 'فضاءات متجهة لكل مستأجر وإجابات مدعومة بالمصادر وحواجز ضد الهلوسة في الإنتاج.'], 'tags' => ['RAG', 'pgvector', 'Multi-tenant']],
    ['title' => ['en' => 'Streaming LLM UX', 'ar' => 'واجهة نماذج متدفقة'], 'body' => ['en' => 'Token streaming over SSE with graceful degradation, cost metering per request, and prompt-version rollouts.', 'ar' => 'تدفق الرموز عبر SSE مع تدهور سلس وقياس تكلفة لكل طلب وإصدارات موجهات مُدارة.'], 'tags' => ['SSE', 'Cost metering', 'Evals']],
    ['title' => ['en' => 'AI-assisted ops', 'ar' => 'عمليات بمساعدة الذكاء'], 'body' => ['en' => 'Anomaly triage and draft replies inside admin tooling — AI where it saves hours, not where it demos well.', 'ar' => 'فرز الحالات الشاذة ومسودات الردود داخل أدوات الإدارة — ذكاء اصطناعي حيث يوفر الساعات لا حيث يبهر في العرض.'], 'tags' => ['Automation', 'Ops']],
];
$perfTargets = [
    ['label' => ['en' => 'Performance', 'ar' => 'الأداء'], 'score' => 95],
    ['label' => ['en' => 'Accessibility', 'ar' => 'إمكانية الوصول'], 'score' => 100],
    ['label' => ['en' => 'Best Practices', 'ar' => 'أفضل الممارسات'], 'score' => 100],
    ['label' => ['en' => 'SEO', 'ar' => 'SEO'], 'score' => 100],
];

$sections = [
    ['hero', ['en' => 'Hero', 'ar' => 'البداية'], 0, []],
    ['expertise', ['en' => 'Expertise', 'ar' => 'الخبرات'], 10, []],
    ['architecture', ['en' => 'Architecture', 'ar' => 'المعمارية'], 20, []],
    ['work', ['en' => 'Featured Work', 'ar' => 'أبرز الأعمال'], 30, []],
    ['metrics', ['en' => 'Metrics', 'ar' => 'الأرقام'], 40, []],
    ['timeline', ['en' => 'Timeline', 'ar' => 'المسيرة'], 50, []],
    ['depth', ['en' => 'Technical Depth', 'ar' => 'العمق التقني'], 60, ['items' => $depthItems]],
    ['ai', ['en' => 'AI Projects', 'ar' => 'مشاريع الذكاء الاصطناعي'], 70, ['items' => $aiItems]],
    ['performance', ['en' => 'Performance', 'ar' => 'الأداء'], 80, ['targets' => $perfTargets]],
    ['contact', ['en' => 'Contact', 'ar' => 'التواصل'], 90, []],
];
foreach ($sections as [$key, $name, $order, $props]) {
    DB::insert('sections', ['key' => $key, 'name' => $j($name), 'sort_order' => $order, 'visible' => 1, 'props' => $j($props)]);
}

// ── Projects ─────────────────────────────────────────────────────────────
$projects = [
    [
        'slug' => 'riyada-os',
        'title' => ['en' => 'Riyada OS', 'ar' => 'ريادة OS'],
        'summary' => [
            'en' => 'Multi-tenant operations platform — one Laravel core serving 40+ isolated tenant businesses with per-tenant schemas, billing, and feature flags.',
            'ar' => 'منصة عمليات متعددة المستأجرين — نواة Laravel واحدة تخدم أكثر من 40 نشاطاً تجارياً معزولاً بمخططات مستقلة وفوترة وأعلام ميزات.',
        ],
        'role' => ['en' => 'Lead Engineer & Architect', 'ar' => 'مهندس رئيسي ومعماري'],
        'domain' => ['en' => 'SaaS · Multi-tenant', 'ar' => 'SaaS · تعدد المستأجرين'],
        'stack' => ['Laravel', 'PHP 8', 'Vue 3', 'Inertia', 'Pinia', 'MySQL', 'Redis', 'Tailwind'],
        'links' => ['live' => '', 'github' => ''],
        'cover' => ['hue' => 22, 'glyph' => 'grid'],
        'metrics' => [
            ['label' => ['en' => 'Tenants served', 'ar' => 'مستأجر مخدوم'], 'value' => '40+'],
            ['label' => ['en' => 'P95 response', 'ar' => 'استجابة P95'], 'value' => '180ms'],
        ],
        'featured' => 1, 'status' => 'published', 'sort_order' => 0,
        'published_at' => '2025-03-01 00:00:00',
    ],
    [
        'slug' => 'mirsal',
        'title' => ['en' => 'Mirsal', 'ar' => 'مرسال'],
        'summary' => [
            'en' => 'Event-driven notification fabric — queued fan-out across email, SMS and push with per-tenant templates, rate shaping and delivery analytics.',
            'ar' => 'نسيج إشعارات موجّه بالأحداث — توزيع عبر الطوابير للبريد والرسائل القصيرة والإشعارات الفورية مع قوالب لكل مستأجر وتشكيل معدل الإرسال وتحليلات التسليم.',
        ],
        'role' => ['en' => 'Backend Lead', 'ar' => 'قائد الواجهة الخلفية'],
        'domain' => ['en' => 'Infrastructure · Queues', 'ar' => 'بنية تحتية · طوابير'],
        'stack' => ['Laravel', 'Redis', 'Horizon', 'MySQL', 'REST', 'Webhooks'],
        'links' => ['live' => '', 'github' => ''],
        'cover' => ['hue' => 28, 'glyph' => 'pulse'],
        'metrics' => [
            ['label' => ['en' => 'Messages / day', 'ar' => 'رسالة / يوم'], 'value' => '1.2M'],
            ['label' => ['en' => 'Delivery success', 'ar' => 'نجاح التسليم'], 'value' => '99.7%'],
        ],
        'featured' => 1, 'status' => 'published', 'sort_order' => 1,
        'published_at' => '2024-11-01 00:00:00',
    ],
    [
        'slug' => 'watheq',
        'title' => ['en' => 'Watheq', 'ar' => 'واثق'],
        'summary' => [
            'en' => 'Document workflow SaaS — versioned approvals, e-signatures and audit trails engineered for compliance-heavy teams.',
            'ar' => 'نظام SaaS لسير عمل المستندات — اعتمادات مُصدرة وتواقيع إلكترونية ومسارات تدقيق مصممة للفرق الخاضعة للامتثال.',
        ],
        'role' => ['en' => 'Full-Stack Engineer', 'ar' => 'مهندس متكامل'],
        'domain' => ['en' => 'SaaS · Compliance', 'ar' => 'SaaS · امتثال'],
        'stack' => ['Laravel', 'Vue', 'TypeScript', 'PostgreSQL', 'S3'],
        'links' => ['live' => '', 'github' => ''],
        'cover' => ['hue' => 18, 'glyph' => 'docs'],
        'metrics' => [
            ['label' => ['en' => 'Documents processed', 'ar' => 'مستند معالج'], 'value' => '250K'],
        ],
        'featured' => 1, 'status' => 'published', 'sort_order' => 2,
        'published_at' => '2024-06-01 00:00:00',
    ],
    [
        'slug' => 'ai-copilot-layer',
        'title' => ['en' => 'AI Copilot Layer', 'ar' => 'طبقة المساعد الذكي'],
        'summary' => [
            'en' => 'LLM integration layer for existing SaaS products — RAG over tenant data, streaming responses, cost metering and prompt-versioned rollouts.',
            'ar' => 'طبقة تكامل نماذج اللغة لمنتجات SaaS قائمة — استرجاع معزّز على بيانات المستأجر، استجابات متدفقة، قياس التكلفة وإصدارات موجهات مُدارة.',
        ],
        'role' => ['en' => 'AI Integration Engineer', 'ar' => 'مهندس تكامل ذكاء اصطناعي'],
        'domain' => ['en' => 'AI · RAG · LLM Ops', 'ar' => 'ذكاء اصطناعي · RAG'],
        'stack' => ['PHP', 'Laravel', 'OpenAI', 'pgvector', 'Redis Streams', 'Vue'],
        'links' => ['live' => '', 'github' => ''],
        'cover' => ['hue' => 32, 'glyph' => 'agent'],
        'metrics' => [
            ['label' => ['en' => 'Cost / 1K tokens', 'ar' => 'التكلفة / ألف رمز'], 'value' => '-64%'],
        ],
        'featured' => 1, 'status' => 'published', 'sort_order' => 3,
        'published_at' => '2025-06-01 00:00:00',
    ],
    [
        'slug' => 'this-site',
        'title' => ['en' => 'This Portfolio', 'ar' => 'هذا الموقع'],
        'summary' => [
            'en' => 'The site you are reading — a custom PHP kernel, live system-field renderer, theme engine and a full Vue admin panel. It manages itself.',
            'ar' => 'الموقع الذي تقرأه الآن — نواة PHP مخصصة، محرك حقل نظام حي، محرك ثيمات ولوحة تحكم Vue كاملة. إنه يدير نفسه بنفسه.',
        ],
        'role' => ['en' => 'Design & Engineering', 'ar' => 'تصميم وهندسة'],
        'domain' => ['en' => 'Meta · Full-Stack', 'ar' => 'ميتا · متكامل'],
        'stack' => ['PHP 8.4', 'Vue 3', 'TypeScript', 'Canvas', 'GSAP', 'SQLite', 'Tailwind'],
        'links' => ['live' => '/', 'github' => ''],
        'cover' => ['hue' => 24, 'glyph' => 'system'],
        'metrics' => [
            ['label' => ['en' => 'Lighthouse', 'ar' => 'لايت‌هاوس'], 'value' => '95+'],
        ],
        'featured' => 0, 'status' => 'published', 'sort_order' => 4,
        'published_at' => '2026-09-01 00:00:00',
    ],
];

foreach ($projects as $p) {
    $p['title'] = $j($p['title']);
    $p['summary'] = $j($p['summary']);
    $p['role'] = $j($p['role']);
    $p['domain'] = $j($p['domain']);
    $p['stack'] = $j($p['stack']);
    $p['links'] = $j($p['links']);
    $p['cover'] = $j($p['cover']);
    $p['metrics'] = $j($p['metrics']);
    $p['body'] = $j(['en' => '', 'ar' => '']);
    $projectId = DB::insert('projects', $p);

    if ($p['slug'] === 'riyada-os' || $p['slug'] === 'ai-copilot-layer') {
        DB::insert('case_study_blocks', [
            'project_id' => $projectId,
            'type' => 'problem',
            'content' => $j(['en' => 'Forty businesses needed isolated data, custom branding and independent billing — without forty deployments.', 'ar' => 'أربعون نشاطاً تجارياً تحتاج بيانات معزولة وهوية مخصصة وفوترة مستقلة — دون أربعين عملية نشر.']),
            'sort_order' => 0,
        ]);
        DB::insert('case_study_blocks', [
            'project_id' => $projectId,
            'type' => 'architecture',
            'content' => $j(['en' => 'Single Laravel core, tenant resolution middleware, per-tenant DB schemas, Redis-backed feature flags, queued provisioning pipeline.', 'ar' => 'نواة Laravel واحدة، وسيط لتحديد المستأجر، مخططات قواعد بيانات لكل مستأجر، أعلام ميزات عبر Redis، وخط تجهيز عبر الطوابير.']),
            'sort_order' => 1,
        ]);
        DB::insert('case_study_blocks', [
            'project_id' => $projectId,
            'type' => 'outcome',
            'content' => $j(['en' => 'Zero-downtime tenant onboarding in under four minutes; one deploy ships to everyone; infra cost per tenant dropped 71%.', 'ar' => 'انضمام مستأجرين دون توقف في أقل من أربع دقائق؛ نشر واحد يصل للجميع؛ وتكلفة البنية التحتية لكل مستأجر انخفضت 71٪.']),
            'sort_order' => 2,
        ]);
    }
}

// ── Skills ───────────────────────────────────────────────────────────────
$skills = [
    // Backend
    [['en' => 'Backend', 'ar' => 'الواجهة الخلفية'], 'PHP 8.x', 95, ['en' => 'Core language mastery', 'ar' => 'إتقان عميق للغة'], 0],
    [['en' => 'Backend', 'ar' => 'الواجهة الخلفية'], 'Laravel', 95, ['en' => 'Ecosystem + internals', 'ar' => 'النظام البيئي والداخليات'], 1],
    [['en' => 'Backend', 'ar' => 'الواجهة الخلفية'], 'MySQL / PostgreSQL', 88, ['en' => 'Query design, indexing', 'ar' => 'تصميم استعلامات وفهارس'], 2],
    [['en' => 'Backend', 'ar' => 'الواجهة الخلفية'], 'Redis & Queues', 86, ['en' => 'Horizon, streams, caching', 'ar' => 'Horizon والتدفقات والتخزين المؤقت'], 3],
    // Frontend
    [['en' => 'Frontend', 'ar' => 'الواجهة الأمامية'], 'Vue 3', 90, ['en' => 'Composition API, SSR-aware', 'ar' => 'Composition API'], 10],
    [['en' => 'Frontend', 'ar' => 'الواجهة الأمامية'], 'TypeScript', 88, ['en' => 'Strict mode, generics', 'ar' => 'الوضع الصارم والأنماط العامة'], 11],
    [['en' => 'Frontend', 'ar' => 'الواجهة الأمامية'], 'Inertia + Pinia', 85, ['en' => 'SPA feel, server-driven', 'ar' => 'إحساس SPA بقيادة الخادم'], 12],
    [['en' => 'Frontend', 'ar' => 'الواجهة الأمامية'], 'Tailwind CSS', 90, ['en' => 'Token systems, RTL', 'ar' => 'أنظمة الرموز و RTL'], 13],
    // Architecture
    [['en' => 'Architecture', 'ar' => 'المعمارية'], 'System Design', 90, ['en' => 'Decomposition, trade-offs', 'ar' => 'التجزئة والموازنات'], 20],
    [['en' => 'Architecture', 'ar' => 'المعمارية'], 'Multi-tenancy', 92, ['en' => 'Isolation strategies at scale', 'ar' => 'استراتيجيات العزل على نطاق واسع'], 21],
    [['en' => 'Architecture', 'ar' => 'المعمارية'], 'Event-driven systems', 84, ['en' => 'Queues, sagas, outbox', 'ar' => 'طوابير و Saga و Outbox'], 22],
    // Quality
    [['en' => 'Quality & Ops', 'ar' => 'الجودة والعمليات'], 'Security hardening', 85, ['en' => 'OWASP, auth, CSP', 'ar' => 'OWASP والمصادقة و CSP'], 30],
    [['en' => 'Quality & Ops', 'ar' => 'الجودة والعمليات'], 'Performance', 88, ['en' => 'Profiling, N+1 hunts, budgets', 'ar' => 'التحليل ومطاردة N+1 والميزانيات'], 31],
    [['en' => 'Quality & Ops', 'ar' => 'الجودة والعمليات'], 'Testing', 82, ['en' => 'Pest, feature suites, E2E', 'ar' => 'Pest واختبارات شاملة'], 32],
    // AI
    [['en' => 'AI Integration', 'ar' => 'تكامل الذكاء الاصطناعي'], 'LLM integration', 87, ['en' => 'RAG, streaming, evals', 'ar' => 'RAG والتدفق والتقييم'], 40],
    [['en' => 'AI Integration', 'ar' => 'تكامل الذكاء الاصطناعي'], 'Prompt systems', 84, ['en' => 'Versioned, testable prompts', 'ar' => 'موجهات مُصدرة وقابلة للاختبار'], 41],
];
foreach ($skills as [$grp, $name, $level, $note, $order]) {
    DB::insert('skills', ['grp' => $j($grp), 'name' => $name, 'level' => $level, 'note' => $j($note), 'sort_order' => $order]);
}

// ── Timeline ─────────────────────────────────────────────────────────────
$timeline = [
    ['2019', ['en' => 'First production PHP', 'ar' => 'أول إنتاج بـ PHP'], ['en' => 'Shipped client systems; learned that maintenance is the real cost.', 'ar' => 'سلّمت أنظمة للعملاء؛ وتعلمت أن الصيانة هي التكلفة الحقيقية.'], 'work', 0],
    ['2021', ['en' => 'Laravel at scale', 'ar' => 'Laravel على نطاق واسع'], ['en' => 'Moved to Laravel full-time; queues, caching, real databases.', 'ar' => 'انتقلت إلى Laravel بدوام كامل؛ طوابير وتخزين مؤقت وقواعد بيانات حقيقية.'], 'work', 1],
    ['2022', ['en' => 'First SaaS', 'ar' => 'أول SaaS'], ['en' => 'Designed my first multi-tenant platform; learned isolation the hard way.', 'ar' => 'صممت أول منصة متعددة المستأجرين؛ وتعلمت العزل بالطريقة الصعبة.'], 'product', 2],
    ['2024', ['en' => 'Architecture ownership', 'ar' => 'ملكية المعمارية'], ['en' => 'Led architecture for a 40-tenant platform: schemas, billing, provisioning.', 'ar' => 'قدت معمارية منصة لأربعين مستأجراً: مخططات وفوترة وتجهيز.'], 'milestone', 3],
    ['2025', ['en' => 'AI in production', 'ar' => 'الذكاء الاصطناعي في الإنتاج'], ['en' => 'Shipped RAG and streaming LLM features inside real SaaS products.', 'ar' => 'سلّمت ميزات RAG ونماذج لغوية متدفقة داخل منتجات SaaS حقيقية.'], 'milestone', 4],
    ['2026', ['en' => 'Product engineering', 'ar' => 'هندسة المنتج'], ['en' => 'This site — designed, engineered, and self-managed end to end.', 'ar' => 'هذا الموقع — مصمم ومهندس ومُدار ذاتياً من الطرف إلى الطرف.'], 'milestone', 5],
];
foreach ($timeline as [$year, $title, $desc, $kind, $order]) {
    DB::insert('timeline_entries', ['year' => $year, 'title' => $j($title), 'description' => $j($desc), 'kind' => $kind, 'sort_order' => $order]);
}

// ── Metrics ──────────────────────────────────────────────────────────────
$metrics = [
    [['en' => 'Years shipping software', 'ar' => 'سنوات في صناعة البرمجيات'], '6', '+', ['en' => 'Production systems, not tutorials.', 'ar' => 'أنظمة إنتاجية، لا دروساً.'], 0],
    [['en' => 'Tenants on one core', 'ar' => 'مستأجر على نواة واحدة'], '40', '+', ['en' => 'Riyada OS — isolated schemas, one deploy.', 'ar' => 'ريادة OS — مخططات معزولة ونشر واحد.'], 1],
    [['en' => 'Messages per day', 'ar' => 'رسالة يومياً'], '1.2', 'M', ['en' => 'Mirsal notification fabric, 99.7% delivery.', 'ar' => 'نسيج إشعارات مرسال، تسليم 99.7٪.'], 2],
    [['en' => 'P95 latency cut', 'ar' => 'خفض زمن P95'], '64', '%', ['en' => 'Query work + cache strategy on hot paths.', 'ar' => 'عمل على الاستعلامات واستراتيجية التخزين المؤقت.'], 3],
];
foreach ($metrics as [$label, $value, $suffix, $context, $order]) {
    DB::insert('metrics', ['label' => $j($label), 'value' => $value, 'suffix' => $suffix, 'context' => $j($context), 'sort_order' => $order]);
}

// ── Social links ─────────────────────────────────────────────────────────
$socials = [
    ['GitHub', 'https://github.com/ziadhassan', 'github', 0],
    ['LinkedIn', 'https://linkedin.com/in/ziadhassan', 'linkedin', 1],
    ['X', 'https://x.com/ziadhassan', 'x', 2],
    ['Email', 'mailto:hello@ziadhassan.dev', 'mail', 3],
];
foreach ($socials as [$label, $url, $icon, $order]) {
    DB::insert('social_links', ['label' => $label, 'url' => $url, 'icon' => $icon, 'sort_order' => $order, 'visible' => 1]);
}

// ── Themes ───────────────────────────────────────────────────────────────
$themes = [
    ['Ember', 'ember', 1, 1, [
        'shared' => ['radius-card' => '10px', 'radius-chip' => '2px'],
        'dark' => [
            'bg' => '#0B0A08', 'surface' => '#14110E', 'elevated' => '#1D1915',
            'border' => '#2A251F', 'text' => '#EDE7DD', 'text-dim' => '#A89F92',
            'accent' => '#FF5D1F', 'accent-soft' => '#FF8A4C', 'accent-ember' => '#8F3208',
            'accent-glow' => 'rgba(255,93,31,.35)', 'ok' => '#4ADE80', 'err' => '#F87171', 'info' => '#E8B44F',
        ],
        'light' => [
            'bg' => '#FAF8F5', 'surface' => '#FFFFFF', 'elevated' => '#F1EDE7',
            'border' => '#E3DDD4', 'text' => '#16130F', 'text-dim' => '#5C554A',
            'accent' => '#E04A0F', 'accent-soft' => '#FF6B2C', 'accent-ember' => '#B33B00',
            'accent-glow' => 'rgba(224,74,15,.22)', 'ok' => '#15803D', 'err' => '#B91C1C', 'info' => '#A16207',
        ],
    ]],
    ['Graphite', 'graphite', 0, 1, [
        'shared' => ['radius-card' => '10px', 'radius-chip' => '2px'],
        'dark' => [
            'bg' => '#0E0E10', 'surface' => '#161618', 'elevated' => '#1E1E22',
            'border' => '#2A2A30', 'text' => '#ECEDEF', 'text-dim' => '#9B9CA6',
            'accent' => '#FF7A3D', 'accent-soft' => '#FFA06E', 'accent-ember' => '#8A3C12',
            'accent-glow' => 'rgba(255,122,61,.28)', 'ok' => '#4ADE80', 'err' => '#F87171', 'info' => '#E8B44F',
        ],
        'light' => [
            'bg' => '#F7F7F8', 'surface' => '#FFFFFF', 'elevated' => '#EFEFF1',
            'border' => '#DDDDE2', 'text' => '#141417', 'text-dim' => '#57585F',
            'accent' => '#DE4E0E', 'accent-soft' => '#F2652A', 'accent-ember' => '#A93B0A',
            'accent-glow' => 'rgba(222,78,14,.20)', 'ok' => '#15803D', 'err' => '#B91C1C', 'info' => '#A16207',
        ],
    ]],
    ['Solar', 'solar', 0, 1, [
        'shared' => ['radius-card' => '12px', 'radius-chip' => '4px'],
        'dark' => [
            'bg' => '#120C05', 'surface' => '#1C130A', 'elevated' => '#281B0F',
            'border' => '#3A2A18', 'text' => '#F3EADC', 'text-dim' => '#B3A48C',
            'accent' => '#FFB224', 'accent-soft' => '#FFCE6B', 'accent-ember' => '#9A6A08',
            'accent-glow' => 'rgba(255,178,36,.32)', 'ok' => '#4ADE80', 'err' => '#F87171', 'info' => '#E8B44F',
        ],
        'light' => [
            'bg' => '#FDF9F0', 'surface' => '#FFFFFF', 'elevated' => '#F6EFDF',
            'border' => '#E8DEC8', 'text' => '#1C1509', 'text-dim' => '#6B5F47',
            'accent' => '#C77700', 'accent-soft' => '#E89A1A', 'accent-ember' => '#8F5B00',
            'accent-glow' => 'rgba(199,119,0,.22)', 'ok' => '#15803D', 'err' => '#B91C1C', 'info' => '#A16207',
        ],
    ]],
];
foreach ($themes as [$name, $slug, $active, $builtin, $tokens]) {
    DB::insert('themes', ['name' => $name, 'slug' => $slug, 'tokens' => $j($tokens), 'is_active' => $active, 'is_builtin' => $builtin]);
}

// ── Settings ─────────────────────────────────────────────────────────────
$settings = [
    'site.name' => 'Ziad Hassan',
    'site.role' => ['en' => 'Software Engineer', 'ar' => 'مهندس برمجيات'],
    'site.tagline' => [
        'en' => 'I design and build systems that stay fast, secure and understandable at scale.',
        'ar' => 'أصمم وأبني أنظمة تبقى سريعة وآمنة وقابلة للفهم على نطاق واسع.',
    ],
    'site.email' => 'hello@ziadhassan.dev',
    'site.location' => ['en' => 'Remote · GMT+3', 'ar' => 'عن بُعد · GMT+3'],
    'site.availability' => true,
    'theme.default_mode' => 'dark',
    'locales.enabled' => ['en', 'ar'],
    'locales.default' => 'en',
    'footer.note' => ['en' => 'Designed & engineered end-to-end. No template.', 'ar' => 'صُمم وهُندس من الطرف إلى الطرف. بلا قالب جاهز.'],
    'effects' => [
        'field' => ['enabled' => true, 'density' => 1.0, 'intensity' => 1.0],
        'magnetic' => true, 'parallax' => true, 'transitions' => true, 'cursor' => true, 'boot' => true,
    ],
];
foreach ($settings as $key => $value) {
    DB::insert('settings', ['key' => $key, 'value' => $j($value)]);
}

// ── SEO meta ─────────────────────────────────────────────────────────────
$seo = [
    ['home',
        ['en' => 'Ziad Hassan — Software Engineer', 'ar' => 'زياد حسن — مهندس برمجيات'],
        ['en' => 'Systems-minded software engineer: Laravel, Vue, multi-tenant SaaS, AI integration. This site is the proof.', 'ar' => 'مهندس برمجيات بعقلية الأنظمة: Laravel وVue وأنظمة SaaS متعددة المستأجرين وتكامل الذكاء الاصطناعي. هذا الموقع هو الدليل.']],
    ['work',
        ['en' => 'Work — Ziad Hassan', 'ar' => 'الأعمال — زياد حسن'],
        ['en' => 'Case studies in SaaS, multi-tenancy, queues and AI integration.', 'ar' => 'دراسات حالة في SaaS وتعدد المستأجرين والطوابير وتكامل الذكاء الاصطناعي.']],
    ['contact',
        ['en' => 'Contact — Ziad Hassan', 'ar' => 'التواصل — زياد حسن'],
        ['en' => 'Start a project, audit a system, or talk architecture.', 'ar' => 'ابدأ مشروعاً، أو دقّق نظاماً، أو تحدث عن المعمارية.']],
];
foreach ($seo as [$page, $title, $desc]) {
    DB::insert('seo_meta', ['page' => $page, 'title' => $j($title), 'description' => $j($desc), 'og_image' => '']);
}

echo "seeded — admin: " . Env::get('ADMIN_EMAIL', 'admin@ziadhassan.dev') . "\n";
