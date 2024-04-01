<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define status data
        $statuses = [
            ['name' => 'Brand New'],
            ['name' => 'Assigned'],
            ['name' => 'Damaged'],
        ];

        // Insert data into the statuses table
        foreach ($statuses as $status) {
            Status::create($status);
        }
    }
}
