<section id="metrics" data-field-cluster="metrics" class="section-pad" aria-labelledby="metrics-title">
    <div class="container-x">
        <div class="sec-index" aria-hidden="true"><span class="mono-label"><span class="idx">SEC/<?= sprintf('%02d', $secIndex) ?></span> · <?= e(strtoupper((string) ($section['key'] ?? ''))) ?></span></div>
        <h2 id="metrics-title" class="display" style="font-size:clamp(2.25rem,5.5vw,4.5rem);margin-block-end:1rem" data-reveal><?= e(lf($section['name'])) ?></h2>
        <p class="lede" data-reveal style="--reveal-delay:90ms;margin-block-end:clamp(2.5rem,6vw,4.5rem)"><?= e(t('sections.metrics_lede')) ?></p>

        <div data-reveal-group style="display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,15rem),1fr));gap:1.25rem">
            <?php foreach ($metrics as $metric): ?>
            <div class="card" style="padding:1.75rem">
                <span class="sheen" aria-hidden="true"></span>
                <p class="metric-value">
                    <span data-count="<?= e($metric['value']) ?>"><?= e($metric['value']) ?></span><span class="suffix"><?= e($metric['suffix'] ?? '') ?></span>
                </p>
                <p style="font-weight:500;margin-block-start:.75rem"><?= e(lf($metric['label'])) ?></p>
                <p class="mono-label" style="font-size:.68rem;margin-block-start:.375rem"><?= e(lf($metric['context'] ?? '')) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
