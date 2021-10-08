<?php

namespace Tests\Feature\App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_will_fail_with_unauthorized_if_user_is_not_authenticated_when_accessing_products_index_page()
    {
        $response = $this->get(route('api.products.index'));

        $response->assertUnauthorized();
    }

    /** @test */
    public function it_will_access_products_index_page()
    {
        $user = $this->create(User::class);

        $response = $this->get(route('api.products.index') . "?api_token=$user->api_token");

        $response->assertOk()
            ->assertJsonCount(0, 'data');
    }

    /** @test */
    public function it_will_return_a_collection_of_products()
    {
        $user = $this->create(User::class);
        $this->create(Product::class, [], 10);

        $response = $this->get(route('api.products.index') . "?api_token=$user->api_token");

        $response->assertOk()
            ->assertJsonCount(5, 'data');
    }

    /** @test */
    public function it_will_paginate_collection_results()
    {
        $user = $this->create(User::class);
        $this->create(Product::class, [], 10);

        $response = $this->get(route('api.products.index') . "?api_token=$user->api_token");

        $response->assertOk()
            ->assertJson([
                'meta' => [
                    'current_page' => 1,
                    'per_page' => 5,
                    'total' => 10
                ]
            ]);
    }

    /** @test */
    public function it_will_filter_products_by_name()
    {
        $user = $this->create(User::class);
        $include = $this->create(Product::class, [
            'name' => 'Include'
        ]);
        $exclude = $this->create(Product::class, [
            'name' => 'Exclude'
        ]);

        $response = $this->get(route('api.products.index') . "?api_token=$user->api_token&name=$include->name");

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJson([
                'data' => [
                    [
                        'name' => $include->name
                    ]
                ]
            ])
            ->assertJsonMissing([
                'name' => $exclude->name
            ]);
    }

    /** @test */
    public function it_will_filter_products_by_status()
    {
        $user = $this->create(User::class);
        $include = $this->create(Product::class, [
            'status' => true
        ]);
        $exclude = $this->create(Product::class, [
            'status' => false
        ]);

        $response = $this->get(route('api.products.index') . "?api_token=$user->api_token&status=$include->status");

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJson([
                'data' => [
                    [
                        'name' => $include->name
                    ]
                ]
            ])
            ->assertJsonMissing([
                'name' => $exclude->name
            ]);
    }

    /** @test */
    public function it_will_fail_with_unauthorized_if_user_is_not_authenticated_when_updating_a_product()
    {
        $response = $this->put(route('api.products.update', ['product' => $this->create(Product::class)]));

        $response->assertUnauthorized();
    }

    /** @test */
    public function it_will_fail_with_not_found_if_product_does_not_exist_when_updating_a_product()
    {
        $response = $this->put(route('api.products.update', ['product' => 1]));

        $response->assertNotFound();
    }

    /** @test */
    public function it_will_fail_with_validation_errors_when_updating_a_product()
    {
        $user = $this->create(User::class);
        $product = $this->create(Product::class, [
            'status' => false
        ]);

        $response = $this->put(route('api.products.update', ['product' => $product]) . "?api_token=$user->api_token");

        $response->assertStatus(302)
            ->assertSessionHasErrors(['name', 'description', 'code', 'status']);

        $response = $this->put(route('api.products.update', ['product' => $product]) . "?api_token=$user->api_token", [
            'name' => 'New name'
        ]);

        $response->assertStatus(302)
            ->assertSessionHasErrors(['description', 'code', 'status']);

        $response = $this->put(route('api.products.update', ['product' => $product]) . "?api_token=$user->api_token", [
            'name' => 'New name',
            'description' => 'New description',
        ]);

        $response->assertStatus(302)
            ->assertSessionHasErrors(['code', 'status']);

        $response = $this->put(route('api.products.update', ['product' => $product]) . "?api_token=$user->api_token", [
            'name' => 'New name',
            'description' => 'New description',
            'code' => 'New code',
        ]);

        $response->assertStatus(302)
            ->assertSessionHasErrors(['status']);

        $response = $this->put(route('api.products.update', ['product' => $product]) . "?api_token=$user->api_token", [
            'name' => 'New name',
            'description' => 'New description',
            'code' => 'New code',
            'status' => 'invalid',
        ]);

        $response->assertStatus(302)
            ->assertSessionHasErrors(['status']);

        $response = $this->put(route('api.products.update', ['product' => $product]) . "?api_token=$user->api_token", [
            'name' => 'New name',
            'description' => 'New description',
            'code' => 'New code',
            'status' => true,
        ]);

        $response->assertOk();
    }

    /** @test */
    public function it_will_update_product()
    {
        $user = $this->create(User::class);
        $product = $this->create(Product::class, [
            'status' => false
        ]);

        $response = $this->put(route('api.products.update', ['product' => $product]) . "?api_token=$user->api_token", [
            'name' => 'New name',
            'description' => 'New description',
            'code' => 'New code',
            'status' => true
        ]);

        $response->assertOk()
            ->assertJson([
                'data' => [
                    'name' => 'New name',
                    'description' => 'New description',
                    'code' => 'New code',
                    'status' => true
                ]
            ]);

        $this->assertDatabaseHas('products', [
            'name' => 'New name',
            'description' => 'New description',
            'code' => 'New code',
            'status' => true
        ]);
    }
}
