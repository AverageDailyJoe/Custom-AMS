<?php

namespace Tests\Feature;

use Tests\TestCase;

class LoginRouteTest extends TestCase
{
    public function test_login_redirects_to_gondowangi_login(): void
    {
        $response = $this->get('/login');
        $response->assertRedirect('/gondowangi/login');
    }

    public function test_gondowangi_login_page_renders(): void
    {
        $response = $this->get('/gondowangi/login');
        $response->assertStatus(200);
    }
}
