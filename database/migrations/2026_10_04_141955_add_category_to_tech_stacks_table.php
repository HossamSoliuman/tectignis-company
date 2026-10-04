<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Lets the homepage group the technology stack by frontend / backend /
 * cloud / database / devops (spec §4.3).
 */
return new class extends Migration
{
    /**
     * @var array<string, list<string>>
     */
    private const CATEGORY_NAMES = [
        'frontend' => ['React', 'Vue.js', 'Angular', 'Next.js'],
        'backend' => ['Node.js', 'PHP', 'Laravel', 'Python', 'Django', 'Java', '.NET'],
        'mobile' => ['Flutter', 'React Native', 'Kotlin', 'Swift'],
        'ecommerce' => ['WordPress', 'Shopify', 'Magento', 'WooCommerce', 'OpenCart'],
        'database' => ['MySQL', 'PostgreSQL', 'MongoDB', 'Redis'],
        'cloud_devops' => ['AWS', 'Azure', 'Google Cloud', 'Docker', 'Kubernetes'],
        'ai_ml' => ['TensorFlow', 'PyTorch', 'OpenAI', 'LangChain'],
    ];

    public function up(): void
    {
        Schema::table('tech_stacks', function (Blueprint $table) {
            $table->string('category')->default('infrastructure')->after('name');
        });

        foreach (self::CATEGORY_NAMES as $category => $names) {
            DB::table('tech_stacks')->whereIn('name', $names)->update(['category' => $category]);
        }
    }

    public function down(): void
    {
        Schema::table('tech_stacks', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
