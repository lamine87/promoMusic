<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ActualiteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->title(15, true),
            'texte' => $this->faker->paragraph(3, true),
            'url_video' => $this->faker->url(),
            'image' => $this->faker->image('public/storage/image', 800, 800, null, false)
        ];
    }
}
