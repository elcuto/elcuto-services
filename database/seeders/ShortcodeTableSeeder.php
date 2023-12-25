<?php

namespace Database\Seeders;

use App\Models\Shortcode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShortcodeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Shortcode::create([
            "name" => "4060",
            "network" => "ALL",
            "status" => 1
        ]);
        Shortcode::create([
            "name" => "4062",
            "network" => "AT",
            "status" => 1
        ]);
        Shortcode::create([
            "name" => "4063",
            "network" => "ALL",
            "status" => 1
        ]);
    }
}
