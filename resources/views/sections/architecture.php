<?php
$lanes = [
    ['edge', ['en' => 'Edge', 'ar' => 'الحافة'], ['Router', 'Middleware', 'Locale', 'Auth']],
    ['core', ['en' => 'Domain Core', 'ar' => 'النواة'], ['Services', 'Validators', 'Events', 'Policies']],
    ['data', ['en' => 'Data Plane', 'ar' => 'مستوى البيانات'], ['MySQL', 'Redis', 'Queue', 'Storage']],
    ['out', ['en' => 'Egress', 'ar' => 'المخرجات'], ['Views / API', 'Webhooks', 'AI Provider']],
];
?>
<section id="architecture" data-field-cluster="data" class="section-pad" aria-labelledby="arch-title">
    <div class="container-x">
        <div class="sec-index" aria-hidden="true"><span class="mono-label"><span class="idx">SEC/<?= sprintf('%02d', $secIndex) ?></span> · <?= e(strtoupper((string) ($section['key'] ?? ''))) ?></span></div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,28rem),1fr));gap:clamp(1.5rem,4vw,4rem);align-items:end;margin-block-end:clamp(2.5rem,6vw,5rem)">
            <h2 id="arch-title" class="display" style="font-size:clamp(2.25rem,5.5vw,4.5rem)" data-reveal><?= e(lf($section['name'])) ?></h2>
            <p class="lede" data-reveal style="--reveal-delay:90ms"><?= e(t('sections.architecture_lede')) ?></p>
        </div>

        <div class="arch-board card" data-reveal role="img"
             aria-label="<?= e(locale() === 'ar' ? 'مخطط معماري: طلب يمر من الحافة إلى النواة إلى مستوى البيانات ثم المخرجات' : 'Architecture diagram: a request flows from the edge through the domain core to the data plane and out') ?>">
            <div class="arch-flow">
                <span class="mono-label arch-req" aria-hidden="true">→ REQUEST</span>
                <?php foreach ($lanes as $i => [$key, $label, $nodes]): ?>
                    <div class="arch-lane" data-lane="<?= e($key) ?>">
                        <span class="mono-label arch-lane-label"><?= e(lf($label)) ?></span>
                        <div class="arch-nodes">
                            <?php foreach ($nodes as $node): ?>
                            <span class="arch-node" data-type="<?= e($key) ?>"><?= e($node) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php if ($i < count($lanes) - 1): ?>
                    <div class="arch-link" aria-hidden="true"><span class="pkt"></span><span class="pkt" style="animation-delay:1.1s"></span></div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <p class="mono-label" style="padding:1rem 1.5rem;border-block-start:1px solid var(--border)">
                <?= e(locale() === 'ar' ? 'مخطط حي — النبضات تمثل طلبات حقيقية تعبر النظام' : 'Live diagram — pulses are requests crossing the system') ?>
            </p>
        </div>
    </div>
</section>
