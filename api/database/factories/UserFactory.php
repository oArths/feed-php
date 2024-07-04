<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Http\Controllers\jwtController;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        do {
            $email = $this->faker->unique()->safeEmail();
        } while (User::where('email', $email)->exists());

        $user = new \App\Models\User( [
            'username' => $this->faker->userName,
            'email' => $email,
            'password' => bcrypt('password'), // ou use Hash::make('password')
            'image' => $this->faker->imageUrl(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $user->save();

        $jwt = new jwtController;
        $token = $jwt->Token($user->email);

        return [
            'username' => $user->username,
            'email' => $user->email,
            'password' => $user->password,
            'image' => $user->image,
            'token' => $token,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];

    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
