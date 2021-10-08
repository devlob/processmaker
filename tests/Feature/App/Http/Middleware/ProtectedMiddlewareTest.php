<?php

namespace Tests\Feature\App\Http\Middleware;

use App\Http\Middleware\ProtectedMiddleware;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class ProtectedMiddlewareTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_will_fail_with_unauthorized_if_user_is_not_authenticated()
    {
        $this->expectException(HttpException::class);
        $middleware = new ProtectedMiddleware();
        $middleware->handle(new Request(), function () {});
    }

    /** @test */
    public function it_will_continue_with_the_request_if_user_is_authenticated()
    {
        $user = $this->create(User::class);

        $middleware = new ProtectedMiddleware();
        $response = $middleware->handle(Request::create("/api/products?api_token=$user->api_token"), function () {
            return 'It works';
        });

        $this->assertEquals('It works', $response);
    }
}
