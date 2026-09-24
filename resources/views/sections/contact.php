<section id="contact" data-field-cluster="ingress" class="section-pad" aria-labelledby="contact-title">
    <div class="container-x">
        <div class="sec-index" aria-hidden="true"><span class="mono-label"><span class="idx">SEC/<?= sprintf('%02d', $secIndex) ?></span> · <?= e(strtoupper((string) ($section['key'] ?? ''))) ?></span></div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,28rem),1fr));gap:clamp(2rem,5vw,5rem)">
            <div>
                <h2 id="contact-title" class="display" style="font-size:clamp(2.25rem,5.5vw,4.5rem)" data-reveal><?= e(lf($section['name'])) ?></h2>
                <p class="lede" data-reveal style="--reveal-delay:90ms;margin-block-start:1.25rem"><?= e(t('sections.contact_lede')) ?></p>
                <div data-reveal style="--reveal-delay:180ms;margin-block-start:2.5rem;display:flex;flex-direction:column;gap:1rem">
                    <p class="mono-label"><?= e(t('contact.direct')) ?></p>
                    <a href="mailto:<?= e(setting('site.email')) ?>" class="link-sweep" style="font-family:var(--font-display);font-size:clamp(1.25rem,3vw,1.9rem);font-weight:600;width:fit-content">
                        <?= e(setting('site.email')) ?>
                    </a>
                    <div style="display:flex;gap:1.25rem;flex-wrap:wrap;margin-block-start:1rem">
                        <?php foreach (($socials ?? []) as $link): ?>
                        <a class="link-sweep mono-label" href="<?= e($link['url']) ?>" rel="me noopener" target="_blank"><?= e($link['label']) ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div data-reveal style="--reveal-delay:150ms">
                <?= partial('partials/contact-form', ['errors' => $errors ?? [], 'sent' => $sent ?? false]) ?>
            </div>
        </div>
    </div>
</section>
