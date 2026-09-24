<section class="container-x" style="padding-block:10rem 4rem">
    <div class="sec-index" aria-hidden="true"><span class="mono-label"><span class="idx">PG</span> · CONTACT</span></div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,28rem),1fr));gap:clamp(2rem,5vw,5rem)">
        <div>
            <h1 class="display" style="font-size:clamp(2.75rem,7vw,5.5rem)" data-reveal><?= e(t('nav.contact')) ?></h1>
            <p class="lede" data-reveal style="--reveal-delay:90ms;margin-block-start:1.25rem"><?= e(t('sections.contact_lede')) ?></p>
            <div data-reveal style="--reveal-delay:180ms;margin-block-start:2.5rem">
                <p class="mono-label"><?= e(t('contact.direct')) ?></p>
                <a href="mailto:<?= e(setting('site.email')) ?>" class="link-sweep" style="font-family:var(--font-display);font-size:clamp(1.25rem,3vw,1.9rem);font-weight:600">
                    <?= e(setting('site.email')) ?>
                </a>
                <div style="display:flex;gap:1.25rem;flex-wrap:wrap;margin-block-start:1.5rem">
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
</section>
