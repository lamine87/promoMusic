<?php

namespace Database\Seeders;

use App\Models\Actualite;
use Illuminate\Database\Seeder;
use App\Models\Media;
use App\Models\Pays;
use App\Models\Role;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(20)->create();
        //\App\Models\Media::factory(20)->create();
       // \App\Models\Pays::factory(5)->create();
        //\App\Models\Role::factory(3)->create();
        \App\Models\Actualite::factory(20)->create();
    }
}
