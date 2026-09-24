<?php $errors = $errors ?? []; ?>
<div class="card" style="padding:clamp(1.5rem,3vw,2.5rem)">
    <?php if (!empty($sent)): ?>
    <div role="status" style="display:flex;gap:1rem;align-items:flex-start">
        <span class="status-dot" style="margin-block-start:.45rem" aria-hidden="true"></span>
        <div>
            <p style="font-weight:600;margin-block-end:.25rem"><?= e(t('contact.sent')) ?></p>
            <p class="mono-label"><?= e(setting('site.email')) ?></p>
        </div>
    </div>
    <?php else: ?>
    <form method="post" action="<?= e(lurl('/contact')) ?>" novalidate>
        <?= csrf_field() ?>
        <div class="hp" aria-hidden="true">
            <label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
        </div>

        <?php if (!empty($errors['form'])): ?>
        <p role="alert" style="color:var(--err);font-size:.875rem;margin-block-end:1rem"><?= e(t('contact.errors.' . ($errors['form'][0] ?? 'required'))) ?></p>
        <?php endif; ?>

        <div class="field" data-state="<?= isset($errors['name']) ? 'error' : '' ?>" style="margin-block-end:1.25rem">
            <label for="cf-name"><?= e(t('contact.name')) ?></label>
            <input id="cf-name" type="text" name="name" required maxlength="120" autocomplete="name"
                   value="<?= e(old('name')) ?>" <?= isset($errors['name']) ? 'aria-invalid="true"' : '' ?>>
            <?php if (isset($errors['name'])): ?><p class="error" role="alert"><?= e(t('contact.errors.' . ($errors['name'][0] === 'min' ? 'min' : 'required'))) ?></p><?php endif; ?>
        </div>

        <div class="field" data-state="<?= isset($errors['email']) ? 'error' : '' ?>" style="margin-block-end:1.25rem">
            <label for="cf-email"><?= e(t('contact.email')) ?></label>
            <input id="cf-email" type="email" name="email" required maxlength="190" autocomplete="email" dir="ltr"
                   value="<?= e(old('email')) ?>" <?= isset($errors['email']) ? 'aria-invalid="true"' : '' ?>>
            <?php if (isset($errors['email'])): ?><p class="error" role="alert"><?= e(t('contact.errors.email')) ?></p><?php endif; ?>
        </div>

        <div class="field" data-state="<?= isset($errors['message']) ? 'error' : '' ?>" style="margin-block-end:1.5rem">
            <label for="cf-message"><?= e(t('contact.message')) ?></label>
            <textarea id="cf-message" name="message" required minlength="10" maxlength="5000" rows="5"
                      <?= isset($errors['message']) ? 'aria-invalid="true"' : '' ?>><?= e(old('message')) ?></textarea>
            <p class="hint"><?= e(t('contact.message_hint')) ?></p>
            <?php if (isset($errors['message'])): ?><p class="error" role="alert"><?= e(t('contact.errors.' . (str_starts_with($errors['message'][0], 'min') ? 'min' : 'required'))) ?></p><?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary" data-magnetic style="width:100%;justify-content:center"><?= e(t('contact.send')) ?></button>
    </form>
    <?php endif; ?>
</div>
