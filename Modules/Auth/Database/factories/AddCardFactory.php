<?php

namespace Modules\Auth\Database\factories;

use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Auth\Entities\Company;

class AddCardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Auth\Entities\AddCard::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $date1 = $this->faker->date('2023-05-19T00:00:00.000Z', '2023-05-21T00:00:00.000Z');
        $date2 = $this->faker->date('2020-05-19T00:00:00.000Z','2023-05-21T00:00:00.000Z');

        return [
            'dept_station' =>  $this->faker->unique()->company(). "Train Lines",
            'dept_time' => new DateTime($date1),
            'train_num' => $this->faker->numberBetween(10000000, 99999999),
            'arr_station'=> $this->faker->unique()->company(). "Train Lines",
            'arr_time' => new DateTime($date2) ,
            'company_id' => Company::all()->random()->id
        ];
    }
}

