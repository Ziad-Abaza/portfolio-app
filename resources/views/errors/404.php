<section class="container-x" style="min-height:70svh;display:grid;place-items:center;text-align:center;padding-block:8rem">
    <div>
        <p class="mono-label" style="color:var(--accent);margin-block-end:1rem">ERR/404 — NODE NOT FOUND</p>
        <h1 class="display" style="font-size:clamp(3rem,9vw,7rem)"><?= e(locale() === 'ar' ? 'المسار غير موجود' : 'Route not found') ?></h1>
        <p class="lede" style="margin:1.5rem auto 2.5rem"><?= e(locale() === 'ar' ? 'العقدة التي تبحث عنها خارج هذه المنظومة.' : 'The node you are looking for is outside this system.') ?></p>
        <a href="<?= e(lurl('/')) ?>" class="btn btn-primary" data-magnetic><?= e(locale() === 'ar' ? 'عودة للمنظومة' : 'Back to the system') ?></a>
    </div>
</section>
