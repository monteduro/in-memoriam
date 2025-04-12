<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\FlowerDonation;
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

        foreach (range(1, 1) as $index) {
            $page = Page::create([
                'name' => $faker->name,
                'birth_date' => $faker->date(),
                'death_date' => $faker->date(),
                'location' => $faker->city . ', ' . $faker->state,
                'biography' => $faker->paragraph,
                'key_traits' => array_map(function ($trait) use($faker) {
                    return [
                        'type' => $trait['key'],
                        'data' => [
                            'value' => $faker->name,
                        ]
                    ];
                }, array_slice(config('app.key_traits'), 0, 4)),
            ]);

            // Aggiungi commenti per ogni pagina
            foreach (range(1, 3) as $commentIndex) {
                Comment::create([
                    'page_id' => $page->id,
                    'author' => $faker->name,
                    'content' => $faker->sentence(),
                ]);
            }

            // Aggiungi donazioni di fiori per ogni pagina
            foreach (range(1, 2) as $flowerIndex) {
                FlowerDonation::create([
                    'page_id' => $page->id,
                    'name' => $faker->name,
                    'flower_type' => $faker->randomElement(array_column(config('app.flowers'), 'key')),
                    'approved' => $faker->boolean,
                ]);
            }
        }
    }
}
