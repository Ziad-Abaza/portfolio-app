<?php
$domains = [];
foreach ($projects ?? [] as $p) {
    $d = lf($p['domain'] ?? '');
    if ($d !== '' && !in_array($d, $domains, true)) {
        $domains[] = $d;
    }
}
?>
<section class="container-x" style="padding-block:10rem 4rem">
    <div class="sec-index" aria-hidden="true"><span class="mono-label"><span class="idx">IDX</span> · WORK</span></div>
    <h1 class="display" style="font-size:clamp(2.75rem,7vw,6rem);margin-block-end:1rem" data-reveal><?= e(t('nav.work')) ?></h1>
    <p class="lede" data-reveal style="--reveal-delay:90ms"><?= e(t('sections.work_lede')) ?></p>

    <?php if (count($domains) > 1): ?>
    <div style="display:flex;flex-wrap:wrap;gap:.5rem;margin-block:2.5rem" role="group" aria-label="Filter" data-reveal>
        <button type="button" class="chip is-on" data-filter="*">ALL</button>
        <?php foreach ($domains as $d): ?>
        <button type="button" class="chip" data-filter="<?= e($d) ?>"><?= e($d) ?></button>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div data-reveal-group style="display:grid;grid-template-columns:repeat(auto-fill,minmax(min(100%,24rem),1fr));gap:1.25rem;margin-block-start:1rem">
        <?php foreach ($projects as $i => $project): ?>
        <article class="card" data-domain="<?= e(lf($project['domain'] ?? '')) ?>" data-filterable>
            <a href="<?= e(lurl('/work/' . $project['slug'])) ?>" class="work-card-inner" style="padding:1.75rem">
                <span class="sheen" aria-hidden="true"></span>
                <div style="display:flex;justify-content:space-between;align-items:baseline">
                    <p class="mono-label" style="color:var(--accent)"><?= e(lf($project['domain'] ?? '')) ?></p>
                    <p class="mono-label"><?= sprintf('%02d', $i + 1) ?></p>
                </div>
                <h2 class="display" style="font-size:1.75rem;margin-block:.875rem"><?= e(lf($project['title'])) ?></h2>
                <p style="color:var(--text-dim);font-size:.9375rem"><?= e(lf($project['summary'] ?? '')) ?></p>
                <div style="display:flex;flex-wrap:wrap;gap:.4rem;margin-block-start:1.25rem">
                    <?php foreach (array_slice((array) ($project['stack'] ?? []), 0, 4) as $tech): ?>
                    <span class="chip" style="font-size:.66rem"><?= e($tech) ?></span>
                    <?php endforeach; ?>
                </div>
            </a>
        </article>
        <?php endforeach; ?>
    </div>
</section>
