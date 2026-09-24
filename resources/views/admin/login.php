<?php $siteAssets = vite('resources/js/site.ts'); ?>
<!doctype html>
<html lang="en" dir="ltr" data-mode="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>Admin — <?= e(setting('site.name', 'Ziad Hassan')) ?></title>
    <style nonce="<?= e(csp_nonce()) ?>"><?= theme_style() ?></style>
    <?php foreach ($siteAssets['css'] as $href): ?>
    <link rel="stylesheet" href="<?= e($href) ?>">
    <?php endforeach; ?>
</head>
<body class="grain" style="min-height:100svh;display:grid;place-items:center;background:var(--bg);color:var(--text)">
    <main style="width:min(92vw,26rem)">
        <div class="card" style="padding:2.5rem">
            <p class="mono-label" style="color:var(--accent);margin-block-end:.5rem">ZH.SYS / CONSOLE</p>
            <h1 class="display" style="font-size:2rem;margin-block-end:2rem">Authenticate</h1>

            <?php if (!empty($error)): ?>
            <p role="alert" style="color:var(--err);font-size:.875rem;margin-block-end:1.25rem"><?= e($error) ?></p>
            <?php endif; ?>

            <form method="post" action="/admin/login">
                <?= csrf_field() ?>
                <div class="field" style="margin-block-end:1.25rem">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" required autocomplete="username" autofocus>
                </div>
                <div class="field" style="margin-block-end:1.75rem">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password">
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">Enter</button>
            </form>
        </div>
        <p class="mono-label" style="text-align:center;margin-block-start:1.5rem">
            <a href="/" class="link-sweep">← back to site</a>
        </p>
    </main>
</body>
</html>
