<?php

namespace Database\Factories;

use App\Models\pegawai;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<pegawai>
 */
class PegawaiFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nik' => fake()->unique()->numerify('##########'),
            'namaPegawai' => fake()->name(),
            'tanggalLahir' => fake()->date(),
            'usia' => fake()->numberBetween(20, 60),
            'jenisKelamin' => fake()->randomElement(['Laki-laki', 'Perempuan']),
            'alamat' => fake()->address(),
            'agama' => fake()->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha']),
            'statusPernikahan' => fake()->randomElement(['Belum Menikah', 'Menikah', 'Cerai']),
            'kewarganegaraan' => fake()->randomElement(['WNI', 'WNA']),
            'bidangPenempatan' => fake()->randomElement(['IT', 'HRD', 'Finance', 'Marketing']),
            'lamaBekerja' => fake()->numberBetween(1, 30),
            'gaji' => fake()->numberBetween(3000000, 20000000),
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
