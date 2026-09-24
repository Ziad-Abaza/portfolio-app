<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Core\Request;
use App\Core\Response;
use App\Core\Router;
use PHPUnit\Framework\TestCase;

final class RouterTest extends TestCase
{
    private function router(): Router
    {
        return new Router();
    }

    public function test_static_route_matches(): void
    {
        $r = $this->router();
        $r->get('/health', fn () => Response::html('ok'));
        $res = $r->dispatch(Request::fake('GET', '/health'));
        $this->assertSame(200, $res->status);
        $this->assertSame('ok', $res->body);
    }

    public function test_param_capture(): void
    {
        $r = $this->router();
        $r->get('/work/{slug}', fn (Request $req) => Response::html($req->param('slug') ?? ''));
        $res = $r->dispatch(Request::fake('GET', '/work/my-project'));
        $this->assertSame('my-project', $res->body);
    }

    public function test_constrained_param_with_braces(): void
    {
        $r = $this->router();
        $r->get('/{locale:[a-z]{2}}/x', fn () => Response::html('hit'));
        $this->assertSame('hit', $r->dispatch(Request::fake('GET', '/en/x'))->body);
        $this->assertSame(404, $r->dispatch(Request::fake('GET', '/en3/x'))->status);
    }

    public function test_wildcard_param(): void
    {
        $r = $this->router();
        $r->get('/admin/{path:.+}', fn (Request $req) => Response::html($req->param('path') ?? ''));
        $res = $r->dispatch(Request::fake('GET', '/admin/deep/nested/route'));
        $this->assertSame('deep/nested/route', $res->body);
    }

    public function test_method_mismatch_falls_through(): void
    {
        $r = $this->router();
        $r->post('/submit', fn () => Response::html('posted'));
        $r->fallback(fn () => Response::html('nf', 404));
        $this->assertSame(404, $r->dispatch(Request::fake('GET', '/submit'))->status);
    }

    public function test_group_prefix_and_middleware(): void
    {
        $r = $this->router();
        $r->middleware('tag', fn (Request $req, \Closure $next) => new Response($next($req)->body . '+mw', 200));
        $r->group(['prefix' => '/api', 'middleware' => ['tag']], function (Router $g): void {
            $g->get('/ping', fn () => Response::html('pong'));
        });
        $res = $r->dispatch(Request::fake('GET', '/api/ping'));
        $this->assertSame('pong+mw', $res->body);
    }

    public function test_head_falls_back_to_get(): void
    {
        $r = $this->router();
        $r->get('/health', fn () => Response::html('ok'));
        $this->assertSame(200, $r->dispatch(Request::fake('HEAD', '/health'))->status);
    }

    public function test_class_handler_resolution(): void
    {
        $r = $this->router();
        $r->get('/class', [FakeController::class, 'handle']);
        $this->assertSame('handled', $r->dispatch(Request::fake('GET', '/class'))->body);
    }
}

final class FakeController
{
    public function handle(Request $req): Response
    {
        return Response::html('handled');
    }
}
