<?php

namespace Database\Factories;

use App\Models\Multiplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class MultiplierFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Multiplier::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'value' => rand(5, 20),
            'label' => 'Entries',
            'status' => 1,
            'giveaway_start_date' => '2021-06-06T16:00:00.000Z',
            'giveaway_end_date' => '2021-06-08T16:00:00.000Z'
        ];
    }
}