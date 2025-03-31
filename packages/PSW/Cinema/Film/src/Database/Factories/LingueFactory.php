<?php

namespace PSW\Cinema\Film\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use PSW\Cinema\Film\Models\LinguePage;


class LingueFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LinguePage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * 
     * @throws \Exception
     */
    public function definition(): array
    {
        $fakerIt = \Faker\Factory::create('it_IT');

        return [
            'name' => $this->faker->name,
            'name_en' => $this->faker->name_en,
            'code' => $this->faker->code,
            'status' => $this->faker->status,
        ];
    }
}
