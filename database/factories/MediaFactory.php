<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use phpDocumentor\Reflection\Types\True_;

class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'title' => $this->faker->name(6, true),
            'texte' => $this->faker->paragraph(3, true),
            'url_video' => $this->faker->url(),
            'lien_instagram' => $this->faker->url(),
            'lien_facebook' => $this->faker->url(),
            'image' => $this->faker->image('public/storage/image', 300, 300, null, false)

        ];
    }
}
