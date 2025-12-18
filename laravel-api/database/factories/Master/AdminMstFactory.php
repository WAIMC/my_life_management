<?php

namespace Database\Factories\Master;

use App\Models\Master\AdminMst;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminMstFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = AdminMst::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'email' => $this->faker->unique()->safeEmail(),
      'user_name' => substr($this->faker->unique()->userName(), 0, 20) . Str::random(2),
      'password' => Hash::make('password'), // password
      'first_name' => $this->faker->firstName(),
      'last_name' => $this->faker->lastName(),
      'address' => $this->faker->address(),
      'phone_number' => $this->faker->phoneNumber(),
      'birth' => $this->faker->dateTimeBetween('-50 years', '-18 years'),
      'gender' => $this->faker->randomElement([1, 2]), // 1: male, 2: female, example
      'status' => 1,
      'is_active' => true,
      'avatar' => null,
      'email_verified_at' => now(),
      'is_delete' => false,
      'remember_token' => Str::random(10),
    ];
  }
}
