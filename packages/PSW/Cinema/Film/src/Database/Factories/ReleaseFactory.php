<?php

namespace PSW\Cinema\Film\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use PSW\Cinema\Film\Models\ReleasesPage;


class ReleaseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ReleasesPage::class;

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
            'customer_id' => $this->faker->customer_id,
            'original_title' => $this->faker->original_title,
            'other_title' => $this->faker->other_title,
            'master_id' => $this->faker->master_id,
            'release_year' => $this->faker->numberBetween(1000, 3000),
            'country' => $this->faker->countryCode,
            'release_distribution' => $this->faker->release_distribution,
            'releasetype' => $this->faker->releasetype,
            'language' => $this->faker->language,
            'release_status' => $this->faker->release_status,
            'default_release'=> $this->faker->default_release,
            'release_featured'=> $this->faker->release_featured,
            'release_vt18' => $this->faker->release_vt18,
            'url_key' => strtolower($this->faker->url_key),
            'release_description' => $this->faker->release_description,
            'short_description' => $this->faker->short_description,
            'meta_keywords' => $this->faker->meta_keywords,
            'meta_title' => $this->faker->meta_title,
            'meta_description' => $this->faker->meta_description,
            'release_note' => $this->faker->release_note,
            // 'language' => $this->faker->language,
            //'release_status' => $this->faker->release_status	,
        ];
    }
}
