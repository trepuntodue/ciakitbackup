<?php

namespace PSW\Cinema\Film\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use PSW\Cinema\Film\Models\CompositoriPage;


class CompositoriFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CompositoriPage::class;

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
            'compo_nome' => $this->faker->compo_nome,
            'compo_cognome' => $this->faker->compo_cognome,
            'compo_alias' =>$this->faker->compo_alias,
            'status' => $this->faker->status,
        ];
    }
}
