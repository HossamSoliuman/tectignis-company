<?php

namespace Database\Factories;

use App\Enums\CandidateExperience;
use App\Enums\JobApplicationStatus;
use App\Models\Designation;
use App\Models\JobApplication;
use App\Models\JobOpening;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobApplication>
 */
class JobApplicationFactory extends Factory
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
            'phone' => fake()->numerify('98########'),
            'location' => fake()->city(),
            'experience' => fake()->randomElement(CandidateExperience::cases())->value,
            'cover_letter' => fake()->paragraph(),
            'status' => JobApplicationStatus::New,
            'is_read' => false,
            'consented_at' => now(),
        ];
    }

    /**
     * Applied against a vacancy, with the title snapshotted as on the form.
     */
    public function forJob(JobOpening $opening): static
    {
        return $this->state(fn (array $attributes): array => [
            'job_opening_id' => $opening->id,
            'job_title' => $opening->title,
        ]);
    }

    public function forDesignation(Designation $designation): static
    {
        return $this->state(fn (array $attributes): array => [
            'designation_id' => $designation->id,
            'designation_name' => $designation->name,
        ]);
    }

    public function status(JobApplicationStatus $status): static
    {
        return $this->state(fn (array $attributes): array => ['status' => $status]);
    }

    public function assignedTo(User $user): static
    {
        return $this->state(fn (array $attributes): array => ['assigned_to' => $user->id]);
    }

    public function archived(): static
    {
        return $this->state(fn (array $attributes): array => ['archived_at' => now()]);
    }

    /**
     * With a CV on the careers disk (fake the disk in tests first).
     */
    public function withResume(string $path = 'resumes/test/cv.pdf'): static
    {
        return $this->state(fn (array $attributes): array => [
            'resume_path' => $path,
            'resume_name' => 'cv.pdf',
            'resume_mime' => 'application/pdf',
            'resume_size' => 1024,
        ]);
    }
}
