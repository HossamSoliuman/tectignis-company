<?php

namespace Database\Factories;

use App\Enums\LeadBudget;
use App\Enums\LeadStatus;
use App\Enums\LeadTimeline;
use App\Models\Lead;
use App\Models\User;
use App\Support\Countries;
use App\Support\EnquiryServices;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lead>
 */
class LeadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'subject' => fake()->sentence(4),
            'message' => fake()->paragraph(),
            'source' => 'contact',
            'status' => LeadStatus::New,
            'is_read' => false,
        ];
    }

    /**
     * A full project enquiry as submitted through the new enquiry form.
     */
    public function enquiry(): static
    {
        return $this->state(fn (array $attributes): array => [
            'company' => fake()->company(),
            'country' => fake()->randomElement(Countries::PRIORITY),
            'service' => fake()->randomElement(EnquiryServices::all()),
            'budget' => fake()->randomElement(LeadBudget::cases())->value,
            'timeline' => fake()->randomElement(LeadTimeline::cases())->value,
            'page_url' => url('/contact'),
            'consented_at' => now(),
        ]);
    }

    public function status(LeadStatus $status): static
    {
        return $this->state(fn (array $attributes): array => ['status' => $status]);
    }

    public function assignedTo(User $user): static
    {
        return $this->state(fn (array $attributes): array => ['assigned_to' => $user->id]);
    }
}
