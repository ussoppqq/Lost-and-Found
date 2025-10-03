<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Item;
use App\Models\Post;
use App\Models\Category;
use App\Models\Report;
use App\Models\Company;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $company1 = Company::first();
        $post1 = Post::where('post_name', 'Main Gate Post')->first();
        $post2 = Post::where('post_name', 'Information Center')->first();

        $category1 = Category::where('category_name', 'Electronics')->first();
        $category2 = Category::where('category_name', 'Personal Items')->first();
        $category3 = Category::where('category_name', 'Clothing')->first();

        // Ambil report yang dibuat di ReportSeeder
        $report1 = Report::where('report_description', 'like', '%HP Samsung%')->first();
        $report3 = Report::where('report_description', 'like', '%Tas ransel%')->first();
        $report5 = Report::where('report_description', 'like', '%Dompet ditemukan%')->first();

        // Item 1: Samsung Galaxy S21
        $item1 = Item::create([
            'item_id' => Str::uuid(),
            'company_id' => $company1->company_id,
            'post_id' => $post1->post_id,
            'category_id' => $category1->category_id,
            'item_name' => 'Samsung Galaxy S21',
            'brand' => 'Samsung',
            'color' => 'Black',
            'item_description' => 'HP Samsung warna hitam dengan casing biru',
            'storage' => 'Locker A-12',
            'item_status' => 'STORED',
            'retention_until' => now()->addDays(90),
            'sensitivity_level' => 'NORMAL',
        ]);
        $report1->update(['item_id' => $item1->item_id]);

        // Item 2: Backpack
        $item3 = Item::create([
            'item_id' => Str::uuid(),
            'company_id' => $company1->company_id,
            'post_id' => $post2->post_id,
            'category_id' => $category3->category_id,
            'item_name' => 'Backpack',
            'brand' => 'Eiger',
            'color' => 'Blue',
            'item_description' => 'Tas ransel biru merk Eiger, berisi buku catatan',
            'storage' => 'Shelf B-5',
            'item_status' => 'STORED',
            'retention_until' => now()->addDays(30),
            'sensitivity_level' => 'NORMAL',
        ]);
        $report3->update(['item_id' => $item3->item_id]);

        // Item 3: Wallet
        $item5 = Item::create([
            'item_id' => Str::uuid(),
            'company_id' => $company1->company_id,
            'post_id' => $post1->post_id,
            'category_id' => $category2->category_id,
            'item_name' => 'Leather Wallet',
            'brand' => 'Fossil',
            'color' => 'Brown',
            'item_description' => 'Dompet kulit coklat merk Fossil, berisi KTP',
            'storage' => 'Safe Box #3',
            'item_status' => 'CLAIMED',
            'retention_until' => now()->addDays(60),
            'sensitivity_level' => 'RESTRICTED',
        ]);
        $report5->update(['item_id' => $item5->item_id]);
    }
}
