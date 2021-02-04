<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;
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
        $statuses = [
            'новый',
            'в работе',
            'на тестировании',
            'завершен',
        ];
        foreach ($statuses as $statusValue) {
            $status = new Status(['name' => $statusValue]);
            $status->save();
        }
    }
}
