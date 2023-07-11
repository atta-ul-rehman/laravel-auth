<?php

namespace Modules\Auth\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Auth\Entities\User;
use Spatie\Permission\Models\Role;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Auth\Entities\Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $usersWithRole = Role::where('name', 'Admin' ,)->first();
        $usersCollection = User::role($usersWithRole)->get();
        
        return [
            'name' => $this->faker->company(),
           
        ];
    }
}

