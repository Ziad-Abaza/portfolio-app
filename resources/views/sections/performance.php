<?php $targets = (array) ($section['props']['targets'] ?? []); ?>
<section id="performance" data-field-cluster="perf" class="section-pad" aria-labelledby="perf-title">
    <div class="container-x">
        <div class="sec-index" aria-hidden="true"><span class="mono-label"><span class="idx">SEC/<?= sprintf('%02d', $secIndex) ?></span> · <?= e(strtoupper((string) ($section['key'] ?? ''))) ?></span></div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,28rem),1fr));gap:clamp(1.5rem,4vw,4rem);align-items:end;margin-block-end:clamp(2.5rem,6vw,5rem)">
            <h2 id="perf-title" class="display" style="font-size:clamp(2.25rem,5.5vw,4.5rem)" data-reveal><?= e(lf($section['name'])) ?></h2>
            <div data-reveal style="--reveal-delay:90ms">
                <p class="lede"><?= e(t('sections.performance_lede')) ?></p>
                <p class="mono-label" style="margin-block-start:1rem"><?= e(t('perf.caption')) ?></p>
            </div>
        </div>

        <div data-reveal-group style="display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,12rem),1fr));gap:1.25rem">
            <?php foreach ($targets as $target):
                $score = (int) ($target['score'] ?? 0);
            ?>
            <div class="card" style="padding:1.75rem;text-align:center">
                <span class="sheen" aria-hidden="true"></span>
                <div class="score-ring" style="--score:<?= $score ?>" role="img" aria-label="<?= e(lf($target['label'] ?? '')) ?>: <?= $score ?>">
                    <span class="score-num"><?= $score ?></span>
                </div>
                <p class="mono-label" style="margin-block-start:1rem"><?= e(lf($target['label'] ?? '')) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
