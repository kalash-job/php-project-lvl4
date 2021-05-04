<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create();

        $statuses = [
            'новый',
            'в работе',
            'на тестировании',
            'завершен',
        ];
        foreach ($statuses as $statusValue) {
            $status = $user->statuses()->make(['name' => $statusValue]);
            $status->save();
        }
    }
}
