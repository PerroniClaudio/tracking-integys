<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContactFormRequest>
 */
class ContactFormRequestFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            //
            'name' => $this->faker->name() . ' ' . $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'business_name' => $this->faker->company(),
            'subject' => $this->faker->sentence(3),
            'message' => $this->faker->paragraph(5),
            'tracked_event_id' => \App\Models\TrackedEvents::factory([
                'event_code' => "DID_SUBMIT_FORM"
            ]),

        ];
    }
}
