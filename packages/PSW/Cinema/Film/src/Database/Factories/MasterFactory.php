<?php

namespace PSW\Cinema\Film\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use PSW\Cinema\Film\Models\MasterPage;


class MasterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MasterPage::class;

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
            'customer_id' => 1,
            'master_maintitle' => $this->faker->master_maintitle,
            'master_othertitle' => $this->faker->master_othertitle,
            'master_genres' => $this->faker->master_genres,
            'master_subgenres' => $this->faker->master_subgenres,
            'master_year' => $this->faker->numberBetween(1000, 3000),
            'country' => $this->faker->countryCode,
            'master_actors' => $this->faker->master_actors,
            'master_scriptwriters' => $this->faker->master_scriptwriters,
            'master_musiccomposers' => $this->faker->master_musiccomposers,
            'master_studios' => $this->faker->master_studios,
            'master_language' => $this->faker->master_language,
            'master_type' => $this->faker->master_type,
            'master_vt18' => $this->faker->master_vt18,
            'master_bn' => $this->faker->master_bn, // PWS#13-bn
            'master_is_visible' => $this->faker->master_is_visible,
            'url_key' => strtolower($this->faker->url_key),
            'master_description' => $this->faker->master_description,
            'short_description' => $this->faker->short_description,
            'meta_keywords' => $this->faker->meta_keywords,
            'meta_title' => $this->faker->meta_title,
            'meta_description' => $this->faker->meta_description,
            'master_trama' => $this->faker->master_trama, // PWS#13-trama
            // 'language' => $this->faker->language,
            //'release_status' => $this->faker->release_status	,
        ];
    }
}
