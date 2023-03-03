<?php

namespace Database\Seeders;

use App\Models\Mentor;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MentorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::where('role_id','!=', 1)->first();
        Mentor::create([
            'account_id' => $admin->id,
            'school_id' => 1,
        ]);
        Mentor::create([
            'account_id' => $admin->id,
            'school_id' => 2,
        ]);
        Mentor::create([
            'account_id' => $admin->id,
            'group_id' => 1
        ]);
        Mentor::create([
            'account_id' => $admin->id,
            'group_id' => 2
        ]);
    }
}
