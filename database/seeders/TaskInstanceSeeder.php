<?php

declare(strict_types=1);

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Rimba\Tree\Work\Models\Task;
use Rimba\Tree\Work\Models\TaskInstance;
use Rimba\Tree\Work\Models\ChecklistInstance;
use App\Trees\Organization\Models\Staff;

class TaskInstanceSeeder extends Seeder
{
    public function run(): void
    {
        $staff = Staff::all();

        $descriptions = [
            'Verify machine safety guards are installed.',
            'Inspect production line cleanliness.',
            'Check compressed air pressure readings.',
            'Validate raw material availability.',
            'Perform equipment visual inspection.',
            'Review maintenance records.',
            'Inspect conveyor belt condition.',
            'Verify calibration status.',
            'Record production startup parameters.',
            'Check emergency stop functionality.',
            'Review operator handover notes.',
            'Inspect work area housekeeping.',
            'Verify packaging materials readiness.',
            'Check label printer operation.',
            'Confirm process control limits.',
            'Review quality inspection reports.',
            'Perform tool inventory check.',
            'Verify PPE compliance.',
            'Inspect electrical cabinet condition.',
            'Ensure production documentation is complete.',
        ];

        ChecklistInstance::query()
            ->with('checklist.tasks')
            ->get()
            ->each(function (ChecklistInstance $checklistInstance) use ($staff, $descriptions): void {

                foreach ($checklistInstance->checklist->tasks as $task) {

                    $assignedStaff = fake()->boolean(60)
                        ? $staff->random()
                        : null;

                    $isCompleted = fake()->boolean(30);

                    $dueDate = fake()->randomElement([
                        Carbon::now()->subDays(rand(1, 10)),  // overdue
                        Carbon::now(),                        // today
                        Carbon::now()->addDays(rand(1, 14)),  // future
                    ]);

                    $completedAt = $isCompleted
                        ? Carbon::parse($dueDate)->subHours(rand(1, 24))
                        : null;

                    TaskInstance::create([
                        'work_package_instance_id' => $checklistInstance->work_package_instance_id,
                        'checklist_instance_id'    => $checklistInstance->id,
                        'task_id'                  => $task->id,

                        'assigned_to_id'           => $assignedStaff?->id,
                        'completed_by_id'          => $isCompleted
                            ? $assignedStaff?->id
                            : null,

                        'is_completed'             => $isCompleted,
                        'completed_at'             => $completedAt,

                        // optional columns if available
                        'description'             => fake()
                            ->randomElement($descriptions),

                        'due_at'                  => $dueDate,

                        'created_at'              => Carbon::now()
                            ->subDays(rand(1, 30)),

                        'updated_at'              => Carbon::now(),
                    ]);
                }
            });
    }
}