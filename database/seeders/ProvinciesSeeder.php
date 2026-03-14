<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('_t_provincies')->insert([
            ['id' => 1, 'nom' => 'BARCELONA'],
            ['id' => 2, 'nom' => 'LLEIDA'],
            ['id' => 3, 'nom' => 'GIRONA'],
            ['id' => 4, 'nom' => 'TARRAGONA'],
            ['id' => 5, 'nom' => 'SARAGOSSA'],
            ['id' => 6, 'nom' => 'HOSCA'],
            ['id' => 7, 'nom' => 'VALENCIA']
        ]);
    }
}
