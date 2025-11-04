<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        // Clear existing projects
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Project::truncate();
        DB::table('project_teams')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $admin = User::where('role', 'admin')->first();
        $employees = User::where('role', 'user')->get();

        // Create sample projects
        $projects = [
            [
                'name' => 'Website Redesign',
                'description' => 'Complete redesign of company website with modern UI/UX',
                'start_date' => now(),
                'end_date' => now()->addDays(30),
                'status' => 'in_progress',
                'budget' => 5000.00,
            ],
            [
                'name' => 'Mobile App Development',
                'description' => 'Develop cross-platform mobile application for iOS and Android',
                'start_date' => now()->addDays(5),
                'end_date' => now()->addDays(60),
                'status' => 'not_started',
                'budget' => 15000.00,
            ],
            [
                'name' => 'Marketing Campaign',
                'description' => 'Q4 marketing campaign planning and execution',
                'start_date' => now()->subDays(10),
                'end_date' => now()->addDays(20),
                'status' => 'in_progress',
                'budget' => 8000.00,
            ],
        ];

        foreach ($projects as $projectData) {
            $project = $admin->projects()->create($projectData);
            
            // Assign random employees to each project
            $randomEmployees = $employees->random(rand(1, $employees->count()))->pluck('id')->toArray();
            $project->teamMembers()->sync($randomEmployees);
            
            $this->command->info("Created project: {$project->name} with " . count($randomEmployees) . " team members");
        }

        $this->command->info('Projects created successfully!');
    }
}