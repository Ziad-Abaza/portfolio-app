<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Core\DB;
use App\Core\Request;
use App\Core\Response;
use App\Core\Router;
use PHPUnit\Framework\TestCase;

/**
 * Feature tests — dispatch synthetic requests through the real route table,
 * real middleware, real (test) database.
 */
final class SiteTest extends TestCase
{
    private Router $router;

    protected function setUp(): void
    {
        $_SESSION = [];
        $this->router = new Router();
        $router = $this->router;
        require BASE_PATH . '/routes/middleware.php';
        require BASE_PATH . '/routes/web.php';
        require BASE_PATH . '/routes/api.php';
    }

    private function go(string $method, string $path, array $body = [], array $headers = []): Response
    {
        return $this->router->dispatch(Request::fake($method, $path, $body, $headers));
    }

    public function test_root_negotiates_locale(): void
    {
        $res = $this->go('GET', '/', [], ['accept-language' => 'ar,en;q=0.5']);
        $this->assertSame(302, $res->status);
        $this->assertSame('/ar', $res->headers['Location']);
    }

    public function test_home_renders_all_sections(): void
    {
        foreach (['/en', '/ar'] as $base) {
            $res = $this->go('GET', $base);
            $this->assertSame(200, $res->status, $base);
            foreach (['hero', 'expertise', 'architecture', 'work', 'metrics', 'timeline', 'depth', 'ai', 'performance', 'contact'] as $sec) {
                $this->assertStringContainsString('id="' . $sec . '"', $res->body, "$base missing $sec");
            }
            $this->assertStringContainsString('Ziad Hassan', $res->body);
        }
    }

    public function test_rtl_and_arabic_content(): void
    {
        $res = $this->go('GET', '/ar');
        $this->assertStringContainsString('dir="rtl"', $res->body);
        $this->assertStringContainsString('زياد حسن', $res->body);
    }

    public function test_unknown_locale_redirects_to_default(): void
    {
        $res = $this->go('GET', '/xx/work');
        $this->assertSame(301, $res->status);
        $this->assertSame('/en/work', $res->headers['Location']);
    }

    public function test_project_pages(): void
    {
        $this->assertSame(200, $this->go('GET', '/en/work')->status);
        $res = $this->go('GET', '/en/work/riyada-os');
        $this->assertSame(200, $res->status);
        $this->assertStringContainsString('Riyada OS', $res->body);
        $this->assertSame(404, $this->go('GET', '/en/work/does-not-exist')->status);
    }

    public function test_contact_flow(): void
    {
        $_SESSION['_csrf_token'] = 'test-token';
        $headers = ['x-csrf-token' => 'test-token'];

        // Honeypot → silent drop, nothing stored
        $before = (int) DB::fetchColumn('SELECT COUNT(*) FROM contact_messages');
        $res = $this->go('POST', '/en/contact', ['_token' => 'test-token', 'website' => 'spammy', 'name' => 'Bot', 'email' => 'b@b.co', 'message' => 'spam message body'], $headers);
        $this->assertSame(302, $res->status);
        $this->assertSame($before, (int) DB::fetchColumn('SELECT COUNT(*) FROM contact_messages'));

        // Validation failure → redirect back, nothing stored
        $res = $this->go('POST', '/en/contact', ['_token' => 'test-token', 'name' => 'X', 'email' => 'bad', 'message' => 'short'], $headers);
        $this->assertSame(302, $res->status);
        $this->assertSame($before, (int) DB::fetchColumn('SELECT COUNT(*) FROM contact_messages'));

        // Valid → stored
        $res = $this->go('POST', '/en/contact', [
            '_token' => 'test-token',
            'name' => 'Real Person', 'email' => 'real@example.com',
            'message' => 'A legitimate message with enough length.',
        ], $headers);
        $this->assertSame(302, $res->status);
        $this->assertSame($before + 1, (int) DB::fetchColumn('SELECT COUNT(*) FROM contact_messages'));
    }

    public function test_csrf_rejection(): void
    {
        $_SESSION['_csrf_token'] = 'real-token';
        $res = $this->go('POST', '/en/contact', ['_token' => 'forged', 'name' => 'X']);
        $this->assertSame(419, $res->status);
    }

    public function test_admin_gate_and_api_auth(): void
    {
        $this->assertSame(302, $this->go('GET', '/admin')->status);
        $this->assertSame(401, $this->go('GET', '/api/admin/overview')->status);
        $this->assertSame(200, $this->go('GET', '/admin/login')->status);
    }

    public function test_admin_login_flow(): void
    {
        $_SESSION['_csrf_token'] = 'tok';
        $res = $this->go('POST', '/admin/login', [
            '_token' => 'tok',
            'email' => 'admin@ziadhassan.dev',
            'password' => 'change-me-now',
        ]);
        $this->assertSame(302, $res->status);
        $this->assertSame('/admin', $res->headers['Location']);
        $this->assertArrayHasKey('user_id', $_SESSION);
    }

    public function test_sitemap_and_robots(): void
    {
        $map = $this->go('GET', '/sitemap.xml');
        $this->assertSame(200, $map->status);
        $this->assertStringContainsString('<urlset', $map->body);
        $this->assertStringContainsString('/en/work/riyada-os', $map->body);

        $robots = $this->go('GET', '/robots.txt');
        $this->assertStringContainsString('Disallow: /admin', $robots->body);
    }
}
