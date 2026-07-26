<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     * Disesuaikan dengan skema BioCinta Cell (NIS-based, bukan email).
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nis'                 => fake()->unique()->numerify('##########'), // 10-digit angka
            'name'                => fake()->name(),
            'role'                => 'student',
            'password'            => static::$password ??= Hash::make('password'),
            'is_password_changed' => false,
            'remember_token'      => Str::random(10),
        ];
    }

    /**
     * State: Siswa yang sudah mengubah password (akses penuh).
     */
    public function passwordChanged(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_password_changed' => true,
        ]);
    }

    /**
     * State: Pengguna dengan role admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role'                => 'admin',
            'is_password_changed' => true,
        ]);
    }
}
