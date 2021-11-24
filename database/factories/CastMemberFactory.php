<?php

namespace Database\Factories;

use App\Models\CastMember;
use Illuminate\Database\Eloquent\Factories\Factory;

class CastMemberFactory extends Factory
{

    public function definition()
    {
        $types = [CastMember::TYPE_DIRECTOR, CastMember::TYPE_ACTOR];
        return [
            'name' => $this->faker->lastName,
            'type' => $types[array_rand($types)],
        ];
    }
}
