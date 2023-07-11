<?php

namespace Modules\Auth\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Auth\Entities\Company;
use Modules\Auth\Entities\User;
use Spatie\Permission\Models\Role;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Auth\Entities\User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {   
        $usersCollection = Company::all();
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('12345'),
            'remember_token' => Str::random(18),
            'phone' => $this->faker->unique()->phoneNumber(),
            'birthday' => $this->faker->dateTimeBetween('-80 years', '-18 years'),
            'address' => $this->faker->unique()->address(),
            'company_id' => $usersCollection->random()->id,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            $role = Role::all()->random(); // Get a random role
            $user->assignRole($role); // Assign the role to the user
        });
    }
}

