<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Type;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('types')->truncate();
        Schema::enableForeignKeyConstraints();

        $types = [
            ['nama_jenis' => 'Novel'],
            ['nama_jenis' => 'Cerpen'],
            ['nama_jenis' => 'Komik'],
            ['nama_jenis' => 'Buku Anak-anak'],
            ['nama_jenis' => 'Lainnya'],
        ];

        foreach ($types as $type) {
            Type::create($type);
        }
    }
}
