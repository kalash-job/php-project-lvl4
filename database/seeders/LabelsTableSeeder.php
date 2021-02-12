<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class LabelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $labels = [
            'Important',
            'ASAP',
            'Not urgent',
        ];
        $user = User::first();
        foreach ($labels as $labelValue) {
            $label = $user->labels()->make(['name' => $labelValue]);
            $label->save();
        }
    }
}
