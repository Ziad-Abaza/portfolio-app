<?php
$featured = array_values(array_filter($projects ?? [], fn ($p) => (int) ($p['featured'] ?? 0) === 1));
$featured = array_slice($featured, 0, 4);
?>
<section id="work" data-field-cluster="apps" class="section-pad" aria-labelledby="work-title">
    <div class="container-x">
        <div class="sec-index" aria-hidden="true"><span class="mono-label"><span class="idx">SEC/<?= sprintf('%02d', $secIndex) ?></span> · <?= e(strtoupper((string) ($section['key'] ?? ''))) ?></span></div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,28rem),1fr));gap:clamp(1.5rem,4vw,4rem);align-items:end;margin-block-end:clamp(2.5rem,6vw,5rem)">
            <h2 id="work-title" class="display" style="font-size:clamp(2.25rem,5.5vw,4.5rem)" data-reveal><?= e(lf($section['name'])) ?></h2>
            <div data-reveal style="--reveal-delay:90ms">
                <p class="lede"><?= e(t('sections.work_lede')) ?></p>
                <a class="link-sweep mono-label" href="<?= e(lurl('/work')) ?>" style="display:inline-block;margin-block-start:1rem"><?= e(t('work.view_all')) ?> →</a>
            </div>
        </div>

        <div data-reveal-group style="display:grid;grid-template-columns:repeat(12,1fr);gap:1.25rem">
            <?php foreach ($featured as $i => $project):
                $span = match ($i % 4) { 0 => '1 / 8', 1 => '8 / 13', 2 => '1 / 7', 3 => '7 / 13', default => '1 / 7' };
                $hue = (int) ($project['cover']['hue'] ?? 22);
            ?>
            <article class="card work-card" style="grid-column:<?= e($span) ?>;--h:<?= $hue ?>" data-domain="<?= e(lf($project['domain'] ?? '')) ?>">
                <a href="<?= e(lurl('/work/' . $project['slug'])) ?>" class="work-card-inner" aria-label="<?= e(lf($project['title'])) ?>">
                    <span class="sheen" aria-hidden="true"></span>
                    <div class="work-glyph" aria-hidden="true">
                        <span class="mono-label"><?= sprintf('%02d', $i + 1) ?></span>
                        <svg width="44" height="44" viewBox="0 0 44 44" fill="none" stroke="currentColor" stroke-width="1.5">
                            <rect x="4" y="4" width="36" height="36" rx="3"/>
                            <circle cx="14" cy="14" r="3.5"/>
                            <circle cx="30" cy="30" r="3.5"/>
                            <circle cx="30" cy="14" r="3.5"/>
                            <path d="M17 16l10 11M27 14 17 27"/>
                        </svg>
                    </div>
                    <div style="padding:clamp(1.5rem,3vw,2.25rem)">
                        <p class="mono-label" style="color:var(--accent);margin-block-end:.75rem"><?= e(lf($project['domain'] ?? '')) ?></p>
                        <h3 class="display" style="font-size:clamp(1.6rem,3vw,2.4rem);margin-block-end:.875rem"><?= e(lf($project['title'])) ?></h3>
                        <p style="color:var(--text-dim);font-size:.9375rem;max-width:52ch"><?= e(lf($project['summary'] ?? '')) ?></p>
                        <div style="display:flex;flex-wrap:wrap;gap:.5rem;margin-block-start:1.25rem">
                            <?php foreach (array_slice((array) ($project['stack'] ?? []), 0, 5) as $tech): ?>
                            <span class="chip"><?= e($tech) ?></span>
                            <?php endforeach; ?>
                        </div>
                        <?php if (!empty($project['metrics'])): ?>
                        <div style="display:flex;gap:1.5rem;margin-block-start:1.5rem;border-block-start:1px solid var(--border);padding-block-start:1.25rem">
                            <?php foreach (array_slice((array) $project['metrics'], 0, 2) as $m): ?>
                            <div>
                                <p class="mono-label" style="font-size:.66rem"><?= e(lf($m['label'] ?? '')) ?></p>
                                <p style="font-family:var(--font-display);font-size:1.5rem;font-weight:600;color:var(--accent)"><?= e($m['value'] ?? '') ?></p>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </a>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
