<?php

namespace Database\Seeders;

use App\Models\Priority;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrioritySeeder extends Seeder
{

    public function run(): void
    {
        $admin = User::where('type', 'admin')->first();
        $priorities = [
            [
                'name' => 'High',
                'color' => '#f10404',
            ],
            [
                'name' => 'Medium',
                'color' => '#eec419',
            ],
            [
                'name' => 'Low',
                'color' => '#20afed',
            ]
        ];

        foreach ($priorities as $priority) {
            $checkPriority = Priority::where('name', $priority['name'])->first();
            if (!$checkPriority) {
                $createpriority =  new Priority();
                $createpriority->name = $priority['name'];
                $createpriority->color = $priority['color'];
                $createpriority->created_by = $admin->id;
                $createpriority->save();
            }
        }
    }
}
