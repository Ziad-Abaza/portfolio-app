<?php
$otherLocales = array_filter($enabledLocales ?? ['en', 'ar'], fn ($l) => $l !== locale());
$switchLocale = $otherLocales ? reset($otherLocales) : null;
$switchPath = $switchLocale ? '/' . $switchLocale . (($barePath ?? '/') === '/' ? '' : $barePath) : null;
?>
<header class="site-header" id="site-header">
    <div class="container-x" style="display:flex;align-items:center;justify-content:space-between;height:4.25rem">
        <a href="<?= e(lurl('/')) ?>" class="mono-label" style="display:flex;align-items:center;gap:.625rem;color:var(--text)">
            <svg width="22" height="22" viewBox="0 0 22 22" aria-hidden="true" style="color:var(--accent)">
                <rect x="1" y="1" width="20" height="20" rx="2" fill="none" stroke="currentColor" stroke-width="1.5"/>
                <circle cx="7" cy="7" r="2" fill="currentColor"/>
                <circle cx="15" cy="15" r="2" fill="currentColor"/>
                <path d="M8.5 8.5 13.5 13.5" stroke="currentColor" stroke-width="1.5"/>
            </svg>
            <span>ZH<span style="color:var(--accent)">.</span>SYS</span>
        </a>

        <nav aria-label="Main" class="hidden md:flex" style="align-items:center;gap:.25rem">
            <a class="nav-pill" href="<?= e(lurl('/work')) ?>" <?= ($page ?? '') === 'work' ? 'aria-current="page"' : '' ?>><?= e(t('nav.work')) ?></a>
            <?php if (($page ?? '') === 'home'): ?>
            <a class="nav-pill" href="#expertise"><?= e(t('nav.expertise')) ?></a>
            <a class="nav-pill" href="#architecture"><?= e(t('nav.architecture')) ?></a>
            <?php endif; ?>
            <a class="nav-pill" href="<?= e(lurl('/contact')) ?>" <?= ($page ?? '') === 'contact' ? 'aria-current="page"' : '' ?>><?= e(t('nav.contact')) ?></a>
        </nav>

        <div class="hidden md:flex" style="align-items:center;gap:.75rem">
            <?php if ($switchPath): ?>
            <a class="nav-pill" href="<?= e($switchPath) ?>" hreflang="<?= e($switchLocale) ?>" lang="<?= e($switchLocale) ?>"><?= e(t('misc.locale_switch')) ?></a>
            <?php endif; ?>
            <button type="button" class="nav-pill" data-theme-toggle aria-label="<?= e(t('misc.theme_toggle')) ?>">
                <svg width="15" height="15" viewBox="0 0 16 16" aria-hidden="true"><circle cx="8" cy="8" r="6.25" fill="none" stroke="currentColor" stroke-width="1.5"/><path d="M8 1.75A6.25 6.25 0 0 0 8 14.25Z" fill="currentColor"/></svg>
            </button>
            <?php if (setting('site.availability')): ?>
            <span class="nav-pill" style="cursor:default"><span class="status-dot" aria-hidden="true"></span><?= e(t('hero.availability')) ?></span>
            <?php endif; ?>
        </div>

        <button type="button" class="nav-pill md:hidden" data-menu-open aria-label="<?= e(t('nav.menu')) ?>" aria-expanded="false">
            <svg width="20" height="20" viewBox="0 0 20 20" aria-hidden="true"><path d="M2 5h16M2 10h16M2 15h16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        </button>
    </div>
</header>

<div class="mobile-nav" data-mobile-nav role="dialog" aria-modal="true" aria-label="<?= e(t('nav.menu')) ?>">
    <button type="button" class="nav-pill" data-menu-close aria-label="<?= e(t('nav.close')) ?>" style="position:absolute;top:1.25rem;inset-inline-end:1.25rem">
        <svg width="22" height="22" viewBox="0 0 20 20" aria-hidden="true"><path d="M4 4l12 12M16 4L4 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
    </button>
    <nav aria-label="Mobile" style="display:flex;flex-direction:column;gap:1.5rem;text-align:center">
        <a class="nav-pill" style="font-size:1.5rem" href="<?= e(lurl('/')) ?>"><?= e(setting('site.name', 'Home')) ?></a>
        <a class="nav-pill" style="font-size:1.5rem" href="<?= e(lurl('/work')) ?>"><?= e(t('nav.work')) ?></a>
        <a class="nav-pill" style="font-size:1.5rem" href="<?= e(lurl('/contact')) ?>"><?= e(t('nav.contact')) ?></a>
        <?php if ($switchPath): ?>
        <a class="nav-pill" style="font-size:1.5rem" href="<?= e($switchPath) ?>"><?= e(t('misc.locale_switch')) ?></a>
        <?php endif; ?>
        <button type="button" class="nav-pill" data-theme-toggle style="font-size:1.5rem;justify-content:center"><?= e(t('misc.theme_toggle')) ?></button>
    </nav>
</div>
