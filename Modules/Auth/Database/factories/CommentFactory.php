<?php

namespace Modules\Auth\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Auth\Entities\Comment;
use Modules\Auth\Entities\User;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Auth\Entities\Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user_id = User::inRandomOrder()->first()->id;
        return [
            'sent_from' => $this->faker->unique()->name(),
            'sent_to'=> $this->faker->unique()->name(),
            'message' => $this->faker->sentence(),
            'user_id' => $user_id
        
        ];
    }

}

