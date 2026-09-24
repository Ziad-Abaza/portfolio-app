<section class="container-x" style="min-height:70svh;display:grid;place-items:center;text-align:center;padding-block:8rem">
    <div>
        <p class="mono-label" style="color:var(--err);margin-block-end:1rem">ERR/500 — SYSTEM FAULT</p>
        <h1 class="display" style="font-size:clamp(3rem,9vw,7rem)"><?= e(locale() === 'ar' ? 'خطأ في المنظومة' : 'System fault') ?></h1>
        <p class="lede" style="margin:1.5rem auto 2.5rem"><?= e(locale() === 'ar' ? 'حدث خطأ داخلي. تم تسجيله.' : 'An internal fault occurred. It has been logged.') ?></p>
        <a href="<?= e(lurl('/')) ?>" class="btn" data-magnetic><?= e(locale() === 'ar' ? 'عودة' : 'Return home') ?></a>
    </div>
</section>
