<?php

namespace Modules\Auth\Database\factories;

use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Modules\Auth\Entities\Company;
use Modules\Auth\Entities\dutyRoster;
use Modules\Auth\Entities\jobCard;
use Modules\Auth\Entities\User;

class JobCardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Auth\Entities\JobCard::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $status =['Un-assigned','Assigned','In-progress','Completed','Expired'];
       
        $date2 = $this->faker->dateTimeBetween('now', '+1 day');
        
        return [
            'dept_station' =>  $this->faker->unique()->company(). " Train Lines",
            'dept_time' => $date2,    
            'train_num' => $this->faker->numberBetween(100, 999),
            'arr_station'=> $this->faker->unique()->company(). " Train Lines",
            'arr_time' => $date2,
            'status' =>  $this->faker->randomElement($status),
            'user_id' => User::all()->random()->id,
            'company_id' => Company::all()->random()->id,
            'dutyRoster_id' => dutyRoster::all()->random()->id
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (jobCard $card) {
            // Generate a ne date for each iteration
            $card->update([
                'dept_time' =>  $this->faker->dateTimeBetween('now', '+1 day'),
                'arr_time' => $this->faker->dateTimeBetween('now', '+2 day'),
            ]);
        });
    }
}

