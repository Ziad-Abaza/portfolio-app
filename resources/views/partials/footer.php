<footer style="position:relative;z-index:2;border-block-start:1px solid var(--border);margin-block-start:4rem">
    <div class="container-x" style="padding-block:3rem;display:flex;flex-wrap:wrap;gap:2rem;align-items:center;justify-content:space-between">
        <div>
            <p class="mono-label" style="margin-block-end:.5rem"><?= e(setting('site.name', 'Ziad Hassan')) ?> — © <?= date('Y') ?></p>
            <p style="color:var(--text-dim);font-size:.875rem"><?= e(lf(setting('footer.note', ''))) ?></p>
        </div>
        <div style="display:flex;align-items:center;gap:1.25rem">
            <?php foreach (($socials ?? []) as $link): ?>
            <a class="link-sweep mono-label" href="<?= e($link['url']) ?>" rel="me noopener" target="_blank"><?= e($link['label']) ?></a>
            <?php endforeach; ?>
            <a class="mono-label" style="color:var(--text-dim)" href="/admin" rel="nofollow"><?= e(t('footer.admin')) ?></a>
        </div>
    </div>
    <p class="mono-label container-x" style="padding-block-end:2rem"><?= e(t('footer.built')) ?></p>
</footer>
