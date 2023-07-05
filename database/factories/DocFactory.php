<?php

namespace Database\Factories;

use App\Models\Doc;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doc>
 */
class DocFactory extends Factory
{
    protected $model = Doc::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'status' => 'Basic',
            'deadline' => $this->faker->dateTimeBetween('now', '+7 days'),
        ];
    }
}
