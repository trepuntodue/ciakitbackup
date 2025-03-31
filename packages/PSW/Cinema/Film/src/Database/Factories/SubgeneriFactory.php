<?php

namespace PSW\Cinema\Film\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use PSW\Cinema\Film\Models\SubgeneriPage;


class SubgeneriFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SubgeneriPage::class;

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
            'subge_name' => $this->faker->subge_name,
            'subge_name_en' => $this->faker->subge_name_en,
            'subge_status' => $this->faker->subge_status,
            'genere_id' => $this->faker->genere_id,
        ];
    }
}
