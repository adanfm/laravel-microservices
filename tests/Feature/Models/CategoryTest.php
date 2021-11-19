<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    public function testList()
    {
        Category::factory()->create();
        $categories = Category::all();
        $this->assertCount(1, $categories);
        $categoryKey = array_keys($categories->first()->getAttributes());
        $this->assertEqualsCanonicalizing([
            'id', 'name', 'description', 'created_at', 'updated_at', 'deleted_at', 'is_active'
        ], $categoryKey);
    }

    public function testCreate()
    {
        $category = Category::create([
            'name' => 'test1'
        ]);
        $category->refresh();

        $this->assertEquals(36, strlen($category->id));
        $this->assertEquals('test1', $category->name);
        $this->assertNull($category->description);
        $this->assertTrue($category->is_active);

        $category = Category::create([
            'name' => 'test1',
            'description' => null,
        ]);

        $this->assertNull($category->description);

        $category = Category::create([
            'name' => 'test1',
            'description' => 'teste_description',
        ]);

        $this->assertEquals('teste_description', $category->description);

        $category = Category::create([
            'name' => 'test1',
            'description' => 'teste_description',
            'is_active' => false,
        ]);

        $this->assertFalse($category->is_active);

        $category = Category::create([
            'name' => 'test1',
            'description' => 'teste_description',
            'is_active' => true,
        ]);

        $this->assertTrue($category->is_active);
    }

    public function testUpdate()
    {
        $category = Category::factory()->create([
            'description' => 'test_description',
            'is_active' => false,
        ]);

        $data = [
            'name' => 'test_name_updated',
            'description' => 'test_description_updated',
            'is_active' => true,
        ];

        $category->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $category->{$key});
        }
    }

    public function testDelete()
    {
        $category = Category::factory()->create();
        $category->delete();
        $this->assertNull(Category::find($category->id));

        $category->restore();
        $this->assertNotNull(Category::find($category->id));
    }
}
