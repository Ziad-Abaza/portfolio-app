<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Core\I18n;
use PHPUnit\Framework\TestCase;

final class HelpersTest extends TestCase
{
    public function test_escaping(): void
    {
        $this->assertSame('&lt;script&gt;', e('<script>'));
        $this->assertSame('&quot;x&quot;', e('"x"'));
        $this->assertSame('', e(null));
    }

    public function test_bilingual_field_resolution(): void
    {
        I18n::setLocale('en');
        $field = ['en' => 'Hello', 'ar' => 'مرحبا'];
        $this->assertSame('Hello', lf($field));
        $this->assertSame('مرحبا', lf($field, 'ar'));
        // Missing locale falls back to en
        $this->assertSame('Hello', lf(['en' => 'Hello'], 'ar'));
        // JSON string input decodes
        $this->assertSame('Hello', lf(json_encode($field), 'en'));
    }

    public function test_translation_lookup(): void
    {
        I18n::setLocale('en');
        $this->assertSame('Work', t('nav.work'));
        $this->assertSame('missing.key', t('missing.key'));
    }

    public function test_arabic_direction(): void
    {
        $this->assertSame('rtl', I18n::direction('ar'));
        $this->assertSame('ltr', I18n::direction('en'));
    }
}
