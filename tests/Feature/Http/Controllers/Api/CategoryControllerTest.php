<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testIndex()
    {
        $category = Category::factory()->create();
        $response = $this->get(route('api.categories.index'));
        $response
            ->assertStatus(200)
            ->assertJson([$category->toArray()]);
    }

    public function testShow()
    {
        $category = Category::factory()->create();
        $response = $this->get(route('api.categories.show', ['category' => $category->id]));
        $response
            ->assertOk()
            ->assertJson($category->toArray());
    }

    public function testInvalidationData()
    {
        $response = $this->json('POST', route('api.categories.store'), []);
        $this->assertInvalidationRequired($response);

        $response = $this->json('POST', route('api.categories.store'), [
            'name' => str_repeat('a', 256),
            'is_active' => 'a'
        ]);
        $this->assertInvalidationMax($response);
        $this->assertInvalidationBoolean($response);

        $category = Category::factory()->create();

        $response = $this->json('PUT', route('api.categories.update', ['category' => $category->id]), []);
        $this->assertInvalidationRequired($response);

        $response = $this->json('PUT', route('api.categories.update', ['category' => $category->id]), [
            'name' => str_repeat('a', 256),
            'is_active' => 'a'
        ]);
        $this->assertInvalidationMax($response);
        $this->assertInvalidationBoolean($response);
    }

    public function testStore()
    {
        $response = $this->json('POST', route('api.categories.store'), [
            'name' => 'test'
        ]);

        $id = $response->json('id');
        $category = Category::find($id);
        $response
            ->assertCreated()
            ->assertJson($category->toArray());

        $this->assertTrue($response->json('is_active'));
        $this->assertNull($response->json('description'));


        $response = $this->json('POST', route('api.categories.store'), [
            'name' => 'test',
            'description' => 'description',
            'is_active' => false,
        ]);

        $response->assertJsonFragment([
            'is_active' => false,
            'description' => 'description'
        ]);
    }

    public function testUpdate()
    {
        $category = Category::factory()->create([
            'description' => 'description',
            'is_active' => false
        ]);

        $response = $this->json('PUT', route('api.categories.update', ['category' => $category->id]), [
            'name' => 'test',
            'description' => 'test',
            'is_active' => true,
        ]);

        $id = $response->json('id');
        $category = Category::find($id);
        $response
            ->assertStatus(200)
            ->assertJson($category->toArray())
            ->assertJsonFragment([
                'is_active' => true,
                'description' => 'test',
            ]);

        $response = $this->json('PUT', route('api.categories.update', ['category' => $category->id]), [
            'name' => 'test',
            'description' => '',
        ]);

        $response->assertJsonFragment([
            'description' => null,
        ]);

        $category->description = 'teste';
        $category->save();

        $response = $this->json('PUT', route('api.categories.update', ['category' => $category->id]), [
            'name' => 'test',
            'description' => null,
        ]);

        $response->assertJsonFragment([
            'description' => null,
        ]);
    }

    public function testDestroy()
    {
        $category = Category::factory()->create();
        $response = $this->json('DELETE', route('api.categories.destroy', ['category' => $category->id]));
        $response->assertNoContent();
        $this->assertNull(Category::find($category->id));
        $this->assertNotNull(Category::withTrashed()->find($category->id));
    }

    protected function assertInvalidationRequired(TestResponse $response)
    {
        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name'])
            ->assertJsonMissingValidationErrors(['is_active'])
            ->assertJsonFragment([
                \Lang::get('validation.required', ['attribute' => 'name'])
            ]);
    }

    protected function assertInvalidationMax(TestResponse $response)
    {
        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name'])
            ->assertJsonFragment([
                \Lang::get('validation.max.string', ['attribute' => 'name', 'max' => 255]),
            ]);
    }

    protected function assertInvalidationBoolean(TestResponse $response)
    {
        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['is_active'])
            ->assertJsonFragment([
                \Lang::get('validation.boolean', ['attribute' => 'is active'])
            ]);
    }
}
