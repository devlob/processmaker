<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class WebRoutesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_will_redirect_to_login_page_if_user_is_not_authenticated_when_accessing_products_index_page()
    {
        $response = $this->get(route('products.index'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function it_will_access_products_index_page()
    {
        $response = $this->actingAs($this->create(User::class))
            ->get(route('products.index'));

        $response->assertOk();
    }
}
