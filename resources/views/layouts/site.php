<?php
use App\Core\I18n;
use App\Core\Settings;

$seoTitle = lf($seo['title'] ?? '') ?: (string) setting('site.name', 'Ziad Hassan');
$seoDesc = lf($seo['description'] ?? '') ?: lf(setting('site.tagline', ''));
$currentPath = (string) ($requestPath ?? '/');
$canonical = url(ltrim($currentPath, '/'));
$enabledLocales = I18n::enabledLocales();
$defaultMode = in_array(setting('theme.default_mode', 'dark'), ['dark', 'light'], true) ? setting('theme.default_mode', 'dark') : 'dark';
$siteAssets = vite('resources/js/site.ts');
// Path without the locale prefix, for building hreflang/locale-switch links.
$barePath = preg_replace('#^/' . preg_quote(locale(), '#') . '#', '', $currentPath) ?: '/';

$jsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'Person',
    'name' => setting('site.name', 'Ziad Hassan'),
    'jobTitle' => lf(setting('site.role', 'Software Engineer')),
    'url' => url(''),
    'email' => 'mailto:' . setting('site.email', ''),
];
?>
<!doctype html>
<html lang="<?= e(locale()) ?>" dir="<?= e(I18n::direction()) ?>" data-mode="<?= e($defaultMode) ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="dark light">
    <title><?= e($seoTitle) ?></title>
    <meta name="description" content="<?= e($seoDesc) ?>">
    <link rel="canonical" href="<?= e($canonical) ?>">
    <?php foreach ($enabledLocales as $loc): ?>
    <link rel="alternate" hreflang="<?= e($loc) ?>" href="<?= e(url($loc . ($barePath === '/' ? '' : $barePath))) ?>">
    <?php endforeach; ?>
    <link rel="alternate" hreflang="x-default" href="<?= e(url(I18n::defaultLocale() . ($barePath === '/' ? '' : $barePath))) ?>">

    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= e($seoTitle) ?>">
    <meta property="og:description" content="<?= e($seoDesc) ?>">
    <meta property="og:url" content="<?= e($canonical) ?>">
    <meta property="og:locale" content="<?= e(locale() === 'ar' ? 'ar_AR' : 'en_US') ?>">
    <meta name="twitter:card" content="summary_large_image">

    <script type="application/ld+json" nonce="<?= e(csp_nonce()) ?>"><?= json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>

    <script nonce="<?= e(csp_nonce()) ?>">
        /* Mode boot — before first paint, zero flash. */
        (function () {
            var stored = null;
            try { stored = localStorage.getItem('zh-mode'); } catch (e) {}
            var mode = stored === 'light' || stored === 'dark' ? stored : <?= json_encode($defaultMode) ?>;
            document.documentElement.dataset.mode = mode;
        })();
        window.__FX__ = <?= fx_config() ?>;
        window.__LOCALE__ = <?= json_encode(locale()) ?>;
        window.__DIR__ = <?= json_encode(I18n::direction()) ?>;
    </script>
    <style nonce="<?= e(csp_nonce()) ?>"><?= theme_style() ?></style>

    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <?php foreach ($siteAssets['css'] as $href): ?>
    <link rel="stylesheet" href="<?= e($href) ?>">
    <?php endforeach; ?>
</head>
<body class="grain">
    <a class="skip-link" href="#main"><?= e(t('nav.skip')) ?></a>

    <canvas id="system-field" aria-hidden="true"></canvas>
    <div class="blueprint" style="position:fixed;inset:0;z-index:0;pointer-events:none" aria-hidden="true"></div>

    <div class="sweep" aria-hidden="true"><span class="sweep-line"></span></div>
    <div class="cursor-dot" aria-hidden="true"></div>
    <div class="cursor-ring" aria-hidden="true"></div>

    <?= partial('partials/header', ['barePath' => $barePath, 'enabledLocales' => $enabledLocales]) ?>

    <main id="main" style="position:relative;z-index:2">
        <?= $content ?>
    </main>

    <?= partial('partials/footer', ['socials' => $socials ?? []]) ?>

    <?php if ($siteAssets['js']): ?>
    <script type="module" src="<?= e($siteAssets['js']) ?>"></script>
    <?php endif; ?>
</body>
</html>
