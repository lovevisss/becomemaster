<?php

namespace Tests\Feature;

use App\Services\CasService;
use Tests\TestCase;

class CasLoginFlowTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['cas.enabled' => true]);
    }

    public function test_login_with_invalid_ticket_renders_error_page_instead_of_redirect_loop(): void
    {
        $mock = \Mockery::mock(CasService::class);
        $mock->shouldReceive('serviceUrl')->once()->andReturn('http://becomemaster.test/login');
        $mock->shouldReceive('serviceUrlForReturn')->twice()->with('/projects')->andReturn('http://becomemaster.test/login?returnUrl=%2Fprojects');
        $mock->shouldReceive('validateTicket')->once()->with('http://becomemaster.test/login?returnUrl=%2Fprojects', 'invalid-ticket')->andReturn(null);
        $mock->shouldReceive('validateTicket')->once()->with('http://becomemaster.test/login', 'invalid-ticket')->andReturn(null);
        $mock->shouldReceive('buildLoginUrl')->once()->with('http://becomemaster.test/login?returnUrl=%2Fprojects')->andReturn('https://cas.example.edu/cas/login?service=http%3A%2F%2Fbecomemaster.test%2Flogin%3FreturnUrl%3D%252Fprojects');

        $this->app->instance(CasService::class, $mock);

        $response = $this->get('/login?returnUrl=%2Fprojects&ticket=invalid-ticket');

        $response->assertStatus(401);
        $response->assertSee('CAS 登录失败');
        $response->assertSee('重新发起 CAS 登录');
    }

    public function test_login_screen_uses_native_form_when_cas_disabled(): void
    {
        config(['cas.enabled' => false]);

        $response = $this->get('/login');

        $response->assertOk();
        $response->assertSee('Log in');
    }

    public function test_login_with_ticket_and_no_return_url_uses_base_service_url(): void
    {
        $mock = \Mockery::mock(CasService::class);
        $mock->shouldReceive('serviceUrl')->twice()->andReturn('http://becomemaster.test/login');
        $mock->shouldReceive('serviceUrlForReturn')->once()->with('/projects')->andReturn('http://becomemaster.test/login?returnUrl=%2Fprojects');
        $mock->shouldReceive('validateTicket')->once()->with('http://becomemaster.test/login', 'invalid-ticket')->andReturn(null);
        $mock->shouldReceive('validateTicket')->once()->with('http://becomemaster.test/login?returnUrl=%2Fprojects', 'invalid-ticket')->andReturn(null);
        $mock->shouldReceive('buildLoginUrl')->once()->with('http://becomemaster.test/login')->andReturn('https://cas.example.edu/cas/login?service=http%3A%2F%2Fbecomemaster.test%2Flogin');

        $this->app->instance(CasService::class, $mock);

        $response = $this->get('/login?ticket=invalid-ticket');

        $response->assertStatus(401);
        $response->assertSee('CAS 登录失败');
    }
}

