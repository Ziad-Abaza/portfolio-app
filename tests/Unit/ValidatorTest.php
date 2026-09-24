<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Core\Validator;
use PHPUnit\Framework\TestCase;

final class ValidatorTest extends TestCase
{
    public function test_required_and_email(): void
    {
        $v = Validator::make(['email' => 'not-an-email'], ['email' => 'required|email']);
        $this->assertTrue($v->fails());
        $this->assertArrayHasKey('email', $v->errors());
    }

    public function test_nullable_skips_absent(): void
    {
        $v = Validator::make([], ['note' => 'nullable|string|max:10']);
        $this->assertFalse($v->fails());
    }

    public function test_slug_rule(): void
    {
        $this->assertFalse(Validator::make(['s' => 'hello-world-2'], ['s' => 'slug'])->fails());
        $this->assertTrue(Validator::make(['s' => 'Hello World!'], ['s' => 'slug'])->fails());
    }

    public function test_min_max_on_strings(): void
    {
        $this->assertTrue(Validator::make(['m' => 'short'], ['m' => 'min:10'])->fails());
        $this->assertTrue(Validator::make(['m' => str_repeat('x', 60)], ['m' => 'max:50'])->fails());
        $this->assertFalse(Validator::make(['m' => 'fine length'], ['m' => 'min:3|max:50'])->fails());
    }

    public function test_in_rule(): void
    {
        $this->assertFalse(Validator::make(['s' => 'draft'], ['s' => 'in:draft,published'])->fails());
        $this->assertTrue(Validator::make(['s' => 'live'], ['s' => 'in:draft,published'])->fails());
    }

    public function test_validated_returns_only_ruled_fields(): void
    {
        $v = Validator::make(['a' => '1', 'evil' => 'injected'], ['a' => 'required|string']);
        $this->assertSame(['a' => '1'], $v->validated());
    }
}
