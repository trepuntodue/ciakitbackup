<?php

namespace PSW\Cinema\Customer\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Webkul\Customer\Models\Customer;
use PSW\Cinema\Customer\Models\CustomerRelease;

class CustomerReleaseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomerRelease::class;

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
            'customer_id' => Customer::factory(),
            'original_title' => $this->faker->original_title,
            'other_title' => $this->faker->other_title,
            'release_year' => $this->faker->release_year,
            'release_distribution' => $this->faker->release_distribution,
            'releasetype' => $this->faker->releasetype,
            'country' => $this->faker->countryCode,
            'language' => $this->faker->language,
            'release_status' => $this->faker->release_status	,
        ];
    }
}
