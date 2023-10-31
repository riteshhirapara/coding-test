<?php

namespace Database\Seeders;

use App\Models\Label;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Label::factory()
            ->count(3)
            ->sequence(
                ['name' => 'High priority', 'class'=> 'bg-red-700'],
                ['name' => 'Medium priority', 'class'=> 'bg-orange-400'],
                ['name' => 'Low priority', 'class'=> 'bg-green-700'])
            ->create();
    }
}
