<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create();
        $tasks = [
            'тестирование',
            'разработка',
            'деплой',
        ];
        $id = 1;
        foreach ($tasks as $taskValue) {
            $worker = User::find($id);
            $id++;
            $creator = User::find($id);
            $id++;
            $task = $worker->tasksAssignedToMe()->make(['name' => $taskValue]);
            $task->creator()->associate($creator);
            $task->save();
        }
    }
}
