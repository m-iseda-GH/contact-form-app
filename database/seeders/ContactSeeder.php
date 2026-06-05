<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        $tags = Tag::all();

        for ($i = 0; $i < 20; $i++) {
            $contact = Contact::create([
                'category_id' => $categories->random()->id,
                'first_name' => fake('ja_JP')->lastName(),
                'last_name' => fake('ja_JP')->firstName(),
                'gender' => fake()->numberBetween(1, 3),
                'email' => fake()->unique()->safeEmail(),
                'tel' => fake()->numerify('0##########'),
                'address' => fake('ja_JP')->address(),
                'building' => fake('ja_JP')->optional()->secondaryAddress(),
                // バリデーションで120字以内にするため、仮的に80字とする。
                'detail' => fake('ja_JP')->realText(80),
            ]);

            $contact->tags()->attach(
                $tags->random(fake()->numberBetween(1, 3))->pluck('id')->toArray()
            );
        }
    }
}
