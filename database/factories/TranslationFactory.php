<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\Translation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends Factory<Translation>
 */
class TranslationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'translatable_type' => (new Service)->getMorphClass(),
            'translatable_id' => Service::factory(),
            'field' => 'title',
            'locale' => 'ar',
            'value' => fake()->sentence(),
            'source_hash' => Translation::hash(fake()->sentence()),
        ];
    }

    /**
     * A current translation of one field (or `content.*` path) of a record,
     * fingerprinted against its English text.
     */
    public function of(Model $model, string $field, string $value, string $locale = 'ar'): static
    {
        $source = method_exists($model, 'translationSource')
            ? $model->translationSource($field)
            : $model->getRawOriginal($field);

        return $this->state(fn (): array => [
            'translatable_type' => $model->getMorphClass(),
            'translatable_id' => $model->getKey(),
            'field' => $field,
            'locale' => $locale,
            'value' => $value,
            'source_hash' => Translation::hash($source),
        ]);
    }
}
