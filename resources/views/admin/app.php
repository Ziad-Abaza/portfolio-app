<?php $adminAssets = vite('resources/js/admin/main.ts'); ?>
<!doctype html>
<html lang="en" dir="ltr" data-mode="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>Console — <?= e(setting('site.name', 'Ziad Hassan')) ?></title>
    <style nonce="<?= e(csp_nonce()) ?>"><?= theme_style() ?></style>
    <script nonce="<?= e(csp_nonce()) ?>">
        window.__ADMIN__ = <?= json_encode($boot ?? [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;
    </script>
    <?php foreach ($adminAssets['css'] as $href): ?>
    <link rel="stylesheet" href="<?= e($href) ?>">
    <?php endforeach; ?>
</head>
<body style="background:var(--bg);color:var(--text);margin:0">
    <div id="admin-app"></div>
    <?php if ($adminAssets['js']): ?>
    <script type="module" src="<?= e($adminAssets['js']) ?>"></script>
    <?php endif; ?>
    <noscript>The admin console requires JavaScript.</noscript>
</body>
</html>
