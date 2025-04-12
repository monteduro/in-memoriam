<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Page;
use Faker\Factory as Faker;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {
            Page::create([
                'name' => $faker->name,
                'birth_date' => $faker->date(),
                'death_date' => $faker->date(),
                'location' => $faker->city . ', ' . $faker->state,
                'biography' => $faker->paragraph,
                'key_traits' => json_encode(array_map(function ($trait) {
                    return [
                        'key' => $trait['key'],
                        'icon' => $trait['icon'],
                    ];
                }, config('app.key_traits'))),
            ]);
        }
    }
}
