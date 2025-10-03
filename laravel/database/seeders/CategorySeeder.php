<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Company;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $company1 = Company::first();

        Category::create([
            'category_id' => Str::uuid(),
            'company_id' => $company1->company_id,
            'category_name' => 'Electronics',
            'subcategory_name' => 'Mobile Phones',
            'retention_days' => 90,
            'is_restricted' => false,
        ]);

        Category::create([
            'category_id' => Str::uuid(),
            'company_id' => $company1->company_id,
            'category_name' => 'Personal Items',
            'subcategory_name' => 'Wallets',
            'retention_days' => 60,
            'is_restricted' => true,
        ]);

        Category::create([
            'category_id' => Str::uuid(),
            'company_id' => $company1->company_id,
            'category_name' => 'Clothing',
            'subcategory_name' => 'Bags',
            'retention_days' => 30,
            'is_restricted' => false,
        ]);

        Category::create([
            'category_id' => Str::uuid(),
            'company_id' => $company1->company_id,
            'category_name' => 'Documents',
            'subcategory_name' => 'ID Cards',
            'retention_days' => 180,
            'is_restricted' => true,
        ]);
    }
}
