<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Company;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::all();
        
        if ($companies->isEmpty()) {
            $this->command->warn('No companies found. Please run CompanySeeder first.');
            return;
        }

        $categories = [
            ['name' => 'Wallet', 'icon' => '👜', 'retention' => 90],
            ['name' => 'Phone', 'icon' => '📱', 'retention' => 90],
            ['name' => 'Laptop', 'icon' => '💻', 'retention' => 120],
            ['name' => 'Watch', 'icon' => '⌚', 'retention' => 90],
            ['name' => 'Keys', 'icon' => '🔑', 'retention' => 60],
            ['name' => 'Glasses', 'icon' => '👓', 'retention' => 60],
            ['name' => 'Documents', 'icon' => '📄', 'retention' => 180, 'restricted' => true],
            ['name' => 'Cards', 'icon' => '💳', 'retention' => 180, 'restricted' => true],
            ['name' => 'Bag', 'icon' => '🎒', 'retention' => 90],
            ['name' => 'Jewelry', 'icon' => '💍', 'retention' => 120],
            ['name' => 'Headphones', 'icon' => '🎧', 'retention' => 60],
            ['name' => 'Camera', 'icon' => '📷', 'retention' => 90],
            ['name' => 'Luggage', 'icon' => '🧳', 'retention' => 120],
            ['name' => 'Books', 'icon' => '📚', 'retention' => 60],
            ['name' => 'Others', 'icon' => '📦', 'retention' => 30],
        ];

        // Loop untuk setiap company
        foreach ($companies as $company) {
            // Loop untuk setiap category
            foreach ($categories as $cat) {
                Category::create([
                    'category_id' => (string) Str::uuid(),
                    'company_id' => $company->company_id,
                    'category_name' => $cat['name'],
                    'category_icon' => $cat['icon'],
                    'retention_days' => $cat['retention'],
                    'is_restricted' => $cat['restricted'] ?? false,
                ]);
            }
            $this->command->info("Created categories for company: {$company->company_name}");
        }
    }
}