<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categories;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('categories')->truncate();
        Schema::enableForeignKeyConstraints();

        $categories = [
            ['nama_kategori' => 'Fiksi'],
            ['nama_kategori' => 'Non-Fiksi'],
        ];

        foreach ($categories as $category) {
            Categories::create($category);
        }
    }
}
