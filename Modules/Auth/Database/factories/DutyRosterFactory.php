<?php

namespace Modules\Auth\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Auth\Entities\jobCard;
use Modules\Auth\Entities\User;

class DutyRosterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Auth\Entities\DutyRoster::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user_id = User::inRandomOrder()->first()->id;
        $start_time = $this->faker->dateTimeBetween('now', '+1 day');
        $end_time = max($this->faker->dateTimeBetween('now', '+2 day'), $start_time);
        return [
            'start_time'=> $start_time,
            'end_time' => $end_time,
            'duration' => $end_time->diff($start_time)->format('%H:%I:%S'),
            'user_id' => $user_id,
        ];
    }
}

