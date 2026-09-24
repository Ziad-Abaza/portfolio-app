<?php
// Sections render in admin-controlled order; unknown keys are skipped safely.
$renderers = [
    'hero' => 'sections/hero',
    'expertise' => 'sections/expertise',
    'architecture' => 'sections/architecture',
    'work' => 'sections/work',
    'metrics' => 'sections/metrics',
    'timeline' => 'sections/timeline',
    'depth' => 'sections/depth',
    'ai' => 'sections/ai',
    'performance' => 'sections/performance',
    'contact' => 'sections/contact',
];

$shared = [
    'projects' => $projects ?? [],
    'skills' => $skills ?? [],
    'timeline' => $timeline ?? [],
    'metrics' => $metrics ?? [],
    'socials' => $socials ?? [],
];

$index = 0;
foreach ($sections ?? [] as $key => $section) {
    $tpl = $renderers[$key] ?? null;
    if ($tpl === null) {
        continue;
    }
    $index++;
    echo partial($tpl, $shared + ['section' => $section, 'secIndex' => $index]);
}
