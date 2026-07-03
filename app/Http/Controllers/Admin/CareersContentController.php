<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Manages the admin-editable content of the public careers page: the stats
 * strip and the "Why Join" benefit cards. Both are stored as JSON blobs in the
 * settings table (group "careers") and consumed by resources/views/public/careers.
 */
class CareersContentController extends Controller
{
    public function edit(): View
    {
        return view('admin.careers.edit', [
            'stats' => Setting::json('careers_stats', $this->defaultStats()),
            'benefits' => Setting::json('careers_benefits', $this->defaultBenefits()),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'stats' => ['nullable', 'array'],
            'stats.*.icon' => ['nullable', 'string', 'max:100'],
            'stats.*.value' => ['nullable', 'string', 'max:60'],
            'stats.*.label' => ['nullable', 'string', 'max:120'],
            'benefits' => ['nullable', 'array'],
            'benefits.*.icon' => ['nullable', 'string', 'max:100'],
            'benefits.*.title' => ['nullable', 'string', 'max:120'],
            'benefits.*.text' => ['nullable', 'string', 'max:500'],
        ]);

        $stats = $this->cleanRows($data['stats'] ?? [], ['value', 'label']);
        $benefits = $this->cleanRows($data['benefits'] ?? [], ['title', 'text']);

        Setting::set('careers_stats', json_encode($stats), 'careers');
        Setting::set('careers_benefits', json_encode($benefits), 'careers');

        return back()->with('status', 'Careers content updated.');
    }

    /**
     * Keep only rows with at least one of the required keys filled, and reindex.
     *
     * @param  array<int, mixed>  $rows
     * @param  array<int, string>  $requiredAny
     * @return array<int, array<string, string>>
     */
    private function cleanRows(array $rows, array $requiredAny): array
    {
        $clean = [];

        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }

            foreach ($requiredAny as $key) {
                if (filled($row[$key] ?? null)) {
                    $clean[] = array_map(fn ($v) => is_string($v) ? trim($v) : $v, $row);
                    break;
                }
            }
        }

        return $clean;
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function defaultStats(): array
    {
        return [
            ['icon' => 'fas fa-users', 'value' => '200+', 'label' => 'Team Members'],
            ['icon' => 'fas fa-user-tie', 'value' => '50+', 'label' => 'Hiring Experts'],
            ['icon' => 'fas fa-award', 'value' => '10+', 'label' => 'Years of Excellence'],
            ['icon' => 'fas fa-smile', 'value' => '95%', 'label' => 'Employee Satisfaction'],
            ['icon' => 'fas fa-book-open', 'value' => 'Continuous', 'label' => 'Learning'],
            ['icon' => 'fas fa-balance-scale', 'value' => 'Work-Life', 'label' => 'Balance'],
        ];
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function defaultBenefits(): array
    {
        return [
            ['icon' => 'fas fa-chart-line', 'title' => 'Growth', 'text' => 'Continuous learning opportunities and career advancement.'],
            ['icon' => 'fas fa-lightbulb', 'title' => 'Innovation', 'text' => 'Work on cutting-edge technologies and solve real-world challenges.'],
            ['icon' => 'fas fa-handshake', 'title' => 'Culture', 'text' => 'Inclusive, collaborative, and transparent work environment.'],
            ['icon' => 'fas fa-clock', 'title' => 'Flexibility', 'text' => 'Flexible work arrangements and work-life balance.'],
            ['icon' => 'fas fa-heart', 'title' => 'Wellness', 'text' => 'Health & wellness programs for a happy you.'],
            ['icon' => 'fas fa-gift', 'title' => 'Rewards', 'text' => 'Competitive salary, performance bonuses, and recognition.'],
        ];
    }
}
