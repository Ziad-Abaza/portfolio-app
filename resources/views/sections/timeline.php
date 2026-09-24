<section id="timeline" data-field-cluster="edge" class="section-pad" aria-labelledby="timeline-title">
    <div class="container-x">
        <div class="sec-index" aria-hidden="true"><span class="mono-label"><span class="idx">SEC/<?= sprintf('%02d', $secIndex) ?></span> · <?= e(strtoupper((string) ($section['key'] ?? ''))) ?></span></div>
        <h2 id="timeline-title" class="display" style="font-size:clamp(2.25rem,5.5vw,4.5rem);margin-block-end:1rem" data-reveal><?= e(lf($section['name'])) ?></h2>
        <p class="lede" data-reveal style="--reveal-delay:90ms;margin-block-end:clamp(2.5rem,6vw,4.5rem)"><?= e(t('sections.timeline_lede')) ?></p>

        <div class="timeline-rail" data-reveal-group style="max-width:44rem;display:flex;flex-direction:column;gap:2.5rem">
            <?php foreach ($timeline as $entry): ?>
            <article class="timeline-entry">
                <p class="mono-label" style="color:var(--accent)"><?= e($entry['year']) ?> <span style="color:var(--text-dim)">· <?= e($entry['kind']) ?></span></p>
                <h3 style="font-family:var(--font-display);font-size:1.35rem;font-weight:600;margin-block:.35rem"><?= e(lf($entry['title'])) ?></h3>
                <p style="color:var(--text-dim);font-size:.9375rem"><?= e(lf($entry['description'] ?? '')) ?></p>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
