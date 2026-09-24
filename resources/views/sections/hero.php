<?php
$name = (string) setting('site.name', 'Ziad Hassan');
$role = lf(setting('site.role', 'Software Engineer'));
$tagline = lf(setting('site.tagline', ''));
$location = lf(setting('site.location', ''));
$available = (bool) setting('site.availability', false);
?>
<section id="hero" data-field-cluster="core" style="position:relative;min-height:100svh;display:flex;align-items:center" aria-label="<?= e($name) ?>">
    <div class="container-x" style="padding-block:8rem 4rem">
        <p class="mono-label hero-word" style="margin-block-end:clamp(1.5rem,4vw,3rem)">
            <span style="--d:100ms;display:inline-flex;align-items:center;gap:.75rem">
                <?php if ($available): ?><span class="status-dot" aria-hidden="true"></span><?php endif; ?>
                <?= e($name) ?> — <?= e($role) ?>
            </span>
        </p>

        <h1 class="display" style="font-size:clamp(2.9rem,9.2vw,8.25rem)">
            <?php if (locale() === 'ar'): ?>
            <span class="hero-word"><span style="--d:220ms">هندسة برمجيات</span></span>
            <span class="hero-word"><span style="--d:320ms">كـ<span class="hero-accent">منظومة حيّة</span>.</span></span>
            <?php else: ?>
            <span class="hero-word"><span style="--d:220ms">Software engineered</span></span>
            <span class="hero-word"><span style="--d:320ms">as a <span class="hero-accent">living system</span>.</span></span>
            <?php endif; ?>
        </h1>

        <p class="lede hero-word" style="margin-block-start:clamp(1.75rem,4vw,3rem)"><span style="--d:460ms;display:block"><?= e($tagline) ?></span></p>

        <div class="hero-word" style="margin-block-start:clamp(2.25rem,5vw,3.5rem)">
            <span style="--d:600ms;display:flex;flex-wrap:wrap;gap:1rem">
                <a href="#work" class="btn btn-primary" data-magnetic><?= e(t('hero.cta_work')) ?></a>
                <a href="<?= e(lurl('/contact')) ?>" class="btn" data-magnetic><?= e(t('hero.cta_contact')) ?></a>
            </span>
        </div>

        <div class="hero-word" style="margin-block-start:clamp(3rem,8vh,6rem)">
            <span style="--d:760ms;display:flex;flex-wrap:wrap;gap:2rem;align-items:center;justify-content:space-between">
                <span class="mono-label"><?= e(t('hero.field_caption')) ?></span>
                <span class="mono-label" style="display:inline-flex;align-items:center;gap:.5rem">
                    <?= e($location) ?>
                    <span aria-hidden="true" style="color:var(--accent)">·</span>
                    <span data-scroll-hint><?= e(t('hero.scroll')) ?> ↓</span>
                </span>
            </span>
        </div>
    </div>
</section>
