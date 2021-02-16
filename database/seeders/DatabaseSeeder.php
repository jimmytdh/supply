<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_accesses')
            ->insert([
                'user_id' => 1,
                'level' => 'admin'
            ]);

        DB::table('suppliers')
            ->insert([
                'company' => 'JL Photographia',
                'name' => 'Jimmy Parker',
                'contact' => '0916 207 2427',
                'email' => 'info.jlphotographia@gmail.com',
                'tin' => '308-546-368-0000',
                'address' => 'Guadalupe, Cebu City, Cebu',
            ]);

        DB::table('units')
            ->insert([
                'code' => 'm',
                'description' => 'meter'
            ]);

        DB::table('units')
            ->insert([
                'code' => 'unit',
                'description' => 'unit'
            ]);
    }
}
