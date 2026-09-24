<section id="expertise" data-field-cluster="services" class="section-pad" aria-labelledby="expertise-title">
    <div class="container-x">
        <div class="sec-index" aria-hidden="true"><span class="mono-label"><span class="idx">SEC/<?= sprintf('%02d', $secIndex) ?></span> · <?= e(strtoupper((string) ($section['key'] ?? ''))) ?></span></div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,28rem),1fr));gap:clamp(1.5rem,4vw,4rem);align-items:end;margin-block-end:clamp(2.5rem,6vw,5rem)">
            <h2 id="expertise-title" class="display" style="font-size:clamp(2.25rem,5.5vw,4.5rem)" data-reveal><?= e(lf($section['name'])) ?></h2>
            <p class="lede" data-reveal style="--reveal-delay:90ms"><?= e(t('sections.expertise_lede')) ?></p>
        </div>

        <div data-reveal-group style="display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,19rem),1fr));gap:1.25rem">
            <?php foreach ($skills as $group => $items): ?>
            <article class="card" style="padding:1.75rem">
                <span class="sheen" aria-hidden="true"></span>
                <h3 class="mono-label" style="color:var(--accent);margin-block-end:1.25rem"><?= e(is_array($items[0]['grp']) ? lf($items[0]['grp']) : $group) ?></h3>
                <ul style="display:flex;flex-direction:column;gap:1.125rem;list-style:none;padding:0;margin:0">
                    <?php foreach ($items as $skill): ?>
                    <li>
                        <div style="display:flex;justify-content:space-between;align-items:baseline;gap:.75rem">
                            <span style="font-weight:500"><?= e($skill['name']) ?></span>
                            <span class="mono-label" style="font-size:.68rem"><?= (int) $skill['level'] ?></span>
                        </div>
                        <div role="img" aria-label="<?= e($skill['name']) ?> — <?= (int) $skill['level'] ?>%"
                             style="margin-block-start:.45rem;height:2px;background:var(--border);position:relative;overflow:hidden">
                            <span data-level="<?= (int) $skill['level'] ?>" style="position:absolute;inset-block:0;inset-inline-start:0;width:0;background:linear-gradient(90deg,var(--accent-ember),var(--accent));transition:width 1.1s var(--ease-out-expo) .2s"></span>
                        </div>
                        <?php if (!empty($skill['note'])): ?>
                        <p style="font-size:.8125rem;color:var(--text-dim);margin-block-start:.4rem"><?= e(lf($skill['note'])) ?></p>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
