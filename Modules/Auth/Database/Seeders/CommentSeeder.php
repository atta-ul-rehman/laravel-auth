<?php

namespace Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Entities\Comment;
use Illuminate\Support\Arr;
use Modules\Auth\Database\factories\CommentFactory;
use Modules\Auth\Entities\Company;
use Modules\Auth\Entities\jobCard;
use Modules\Auth\Entities\User;
use Faker\Factory as Faker;


class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        // $this->call("OthersTableSeeder");
        
        Company::all()->each(function ($company) {
            $company->created_by = User::inRandomOrder()->first()->id;
            $company->save();
        });
        $numCommentsPerPost = Arr::random([1, 2, 3, 4, 5]);

        $card = jobCard::all();
       
        $card->each(function ($post) use ($numCommentsPerPost) {
           $faker = \Faker\Factory::create();
           $factory = CommentFactory::new();
           $factory->count($numCommentsPerPost)->create([
                'sent_from' => $faker->unique()->name(),
                'sent_to'=> $faker->unique()->name(),
                'message' => $faker->sentence(),
                'commentable_id' => $post->id,
                'commentable_type' => jobCard::class,
            ]);

        });
    }
}
