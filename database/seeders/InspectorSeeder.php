<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InspectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_accesses')
            ->insert([
                'user_id' => 2,
                'level' => 'inspector'
            ]);

        DB::table('user_accesses')
            ->insert([
                'user_id' => 3,
                'level' => 'inspector'
            ]);
    }
}
