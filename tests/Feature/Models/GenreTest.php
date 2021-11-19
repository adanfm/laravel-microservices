<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use App\Models\Genre;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class GenreTest extends TestCase
{
    use DatabaseMigrations;

    public function testList()
    {
        Genre::factory()->create();
        $genres = Genre::all();
        $this->assertCount(1, $genres);
        $genreKey = array_keys($genres->first()->getAttributes());
        $this->assertEqualsCanonicalizing([
            'id', 'name', 'created_at', 'updated_at', 'deleted_at', 'is_active'
        ], $genreKey);
    }

    public function testCreate()
    {
        $genre = Genre::create([
            'name' => 'test1'
        ]);
        $genre->refresh();

        $this->assertEquals(36, strlen($genre->id));
        $this->assertEquals('test1', $genre->name);
        $this->assertTrue($genre->is_active);

        $genre = Genre::create([
            'name' => 'test1',
            'is_active' => false,
        ]);

        $this->assertFalse($genre->is_active);

        $genre = Genre::create([
            'name' => 'test1',
            'is_active' => true,
        ]);

        $this->assertTrue($genre->is_active);
    }

    public function testUpdate()
    {
        $category = Genre::factory()->create([
            'is_active' => false,
        ]);

        $data = [
            'name' => 'test_name_updated',
            'is_active' => true,
        ];

        $category->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $category->{$key});
        }
    }

    public function testDelete()
    {
        $genre = Genre::factory()->create();
        $genre->delete();
        $this->assertNull(Genre::find($genre->id));

        $genre->restore();
        $this->assertNotNull(Genre::find($genre->id));
    }
}
