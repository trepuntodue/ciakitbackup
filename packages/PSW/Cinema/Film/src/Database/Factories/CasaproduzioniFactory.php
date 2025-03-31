<?php

namespace PSW\Cinema\Film\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use PSW\Cinema\Film\Models\CasaproduzioniPage;


class CasaproduzioniFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CasaproduzioniPage::class;

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
            'casa_nome' => $this->faker->attori_nome,
            'status' => $this->faker->status,
        ];
    }
}
