<?php
$title = lf($project['title']);
$stack = (array) ($project['stack'] ?? []);
$metrics = (array) ($project['metrics'] ?? []);
$links = (array) ($project['links'] ?? []);
?>
<article class="container-x">
    <header class="case-hero">
        <a href="<?= e(lurl('/work')) ?>" class="link-sweep mono-label">← <?= e(t('work.back')) ?></a>
        <p class="mono-label" style="color:var(--accent);margin-block:1.5rem .75rem"><?= e(lf($project['domain'] ?? '')) ?></p>
        <h1 class="display" style="font-size:clamp(2.75rem,7vw,5.5rem)" data-reveal><?= e($title) ?></h1>
        <p class="lede" data-reveal style="--reveal-delay:90ms;margin-block-start:1.25rem"><?= e(lf($project['summary'] ?? '')) ?></p>

        <div style="display:flex;flex-wrap:wrap;gap:2.5rem;margin-block-start:2.5rem" data-reveal>
            <div>
                <p class="mono-label"><?= e(t('work.role')) ?></p>
                <p style="font-weight:500;margin-block-start:.25rem"><?= e(lf($project['role'] ?? '')) ?></p>
            </div>
            <div>
                <p class="mono-label"><?= e(t('work.stack')) ?></p>
                <div style="display:flex;flex-wrap:wrap;gap:.4rem;margin-block-start:.5rem">
                    <?php foreach ($stack as $tech): ?><span class="chip"><?= e($tech) ?></span><?php endforeach; ?>
                </div>
            </div>
            <?php foreach (['live' => 'Live', 'github' => 'GitHub'] as $k => $label): ?>
            <?php if (!empty($links[$k])): ?>
            <div>
                <p class="mono-label"><?= e($label) ?></p>
                <a class="link-sweep" href="<?= e($links[$k]) ?>" target="_blank" rel="noopener" style="display:inline-block;margin-block-start:.25rem"><?= e(parse_url((string) $links[$k], PHP_URL_HOST) ?: $links[$k]) ?> ↗</a>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <?php if ($metrics !== []): ?>
        <div style="display:flex;flex-wrap:wrap;gap:2.5rem;margin-block-start:2.5rem;border-block-start:1px solid var(--border);padding-block-start:2rem" data-reveal>
            <?php foreach ($metrics as $m): ?>
            <div>
                <p style="font-family:var(--font-display);font-size:2rem;font-weight:600;color:var(--accent)"><?= e($m['value'] ?? '') ?></p>
                <p class="mono-label" style="font-size:.68rem"><?= e(lf($m['label'] ?? '')) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </header>

    <div style="display:flex;flex-direction:column;gap:3.5rem;padding-block:4rem">
        <?php foreach ($blocks as $block):
            $type = $block['type'];
            $heading = t('work.blocks.' . $type);
            $content = (array) $block['content'];
            $text = lf($content);
        ?>
        <section class="case-block" data-reveal>
            <?php if ($heading !== ''): ?><h2><?= e($heading) ?></h2><?php endif; ?>
            <?php if ($type === 'architecture'): ?>
            <div class="card" style="padding:1.5rem;border-inline-start:2px solid var(--accent)">
                <p style="font-family:var(--font-mono);font-size:.95rem;line-height:1.9"><?= e($text) ?></p>
            </div>
            <?php else: ?>
            <p><?= e($text) ?></p>
            <?php endif; ?>
        </section>
        <?php endforeach; ?>

        <?php if (trim(lf($project['body'] ?? '')) !== ''): ?>
        <section class="case-block" data-reveal>
            <p><?= nl2br(e(lf($project['body']))) ?></p>
        </section>
        <?php endif; ?>
    </div>

    <div style="border-block-start:1px solid var(--border);padding-block:2.5rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem">
        <a href="<?= e(lurl('/work')) ?>" class="link-sweep mono-label">← <?= e(t('work.back')) ?></a>
        <a href="<?= e(lurl('/contact')) ?>" class="btn btn-primary" data-magnetic><?= e(t('hero.cta_contact')) ?></a>
    </div>
</article>
