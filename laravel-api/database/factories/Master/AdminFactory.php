<?php

namespace Database\Factories\Master;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Master\Admin>
 */
class AdminFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $email = fake()->email();
        $userName = fake()->userName();
        $password = fake()->password();
        $firstName = fake()->firstName();
        $lastName = fake()->lastName();
        $address = fake()->address();
        $phoneNumber = fake()->phoneNumber();
        $avatar = fake()->name();

        return [
            'email' => (strlen($email) > 30) ? substr($email, 0, 30) : $email,
            'user_name' => (strlen($userName) > 50) ? substr($userName, 0, 50) : $userName,
            'password' => (strlen($password) > 100) ? substr($password, 0, 100) : $password,
            'first_name' => (strlen($firstName) > 20) ? substr($firstName, 0, 20) : $firstName,
            'last_name' => (strlen($lastName) > 20) ? substr($lastName, 0, 20) : $lastName,
            'address' => (strlen($address) > 100) ? substr($address, 0, 100) : $address,
            'phone_number' => (strlen($phoneNumber) > 20) ? substr($phoneNumber, 0, 20) : $phoneNumber,
            'birth' => fake()->date(),
            'gender' => random_int(1, 5),
            'status' => random_int(1, 5),
            'is_active' => true,
            'avatar' => (strlen($avatar) > 20) ? substr($avatar, 0, 20) : $avatar,
        ];
    }

    /**    
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
