<?php

namespace Tests\Feature\Models;

use App\Models\CastMember;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CastMemberTest extends TestCase
{
    use DatabaseMigrations;

    public function testList()
    {
        CastMember::factory()->create();
        $castMember = CastMember::all();
        $this->assertCount(1, $castMember);
        $castMemberKey = array_keys($castMember->first()->getAttributes());
        $this->assertEqualsCanonicalizing([
            'id', 'name', 'type', 'created_at', 'updated_at', 'deleted_at'
        ], $castMemberKey);
    }

    public function testCreate()
    {
        $castMember = CastMember::create([
            'name' => 'test1',
            'type' => CastMember::TYPE_DIRECTOR,
        ]);
        $castMember->refresh();

        $this->assertEquals(36, strlen($castMember->id));
        $this->assertEquals('test1', $castMember->name);
        $this->assertEquals(CastMember::TYPE_DIRECTOR, $castMember->type);
    }

    public function testUpdate()
    {
        $castMember = CastMember::factory()->create([
            'name' => 'test1',
            'type' => CastMember::TYPE_DIRECTOR,
        ]);

        $data = [
            'name' => 'test_name_updated',
            'type' => CastMember::TYPE_ACTOR,
        ];

        $castMember->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $castMember->{$key});
        }
    }

    public function testDelete()
    {
        $castMember = CastMember::factory()->create();
        $castMember->delete();
        $this->assertNull(CastMember::find($castMember->id));

        $castMember->restore();
        $this->assertNotNull(CastMember::find($castMember->id));
    }
}
