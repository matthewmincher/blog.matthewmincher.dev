<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $email = $this->faker->unique->safeEmail();

        return [
            'name' => $this->faker->name(),
            'email' => $email,
            'sub' => 'internal:'.$email,
            'picture' => 'https://picsum.photos/seed/'.$email.'/200/200',
            'remember_token' => Str::random(10)
        ];
    }
}
