<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Group::create([
            'name' => 'Công ty A',
            'phone_number' => '0987654321',
            'group_type' => '1',
            'name_represent' => 'Nguyễn Trường Xuân',
            'email_in_charge' => 'xghost@gmail.com'
        ]);
        Group::create([
            'name' => 'Công ty B',
            'phone_number' => '0987654321',
            'group_type' => '2',
            'name_represent' => 'Nguyễn Trường Xuân',
            'email_in_charge' => 'xghost@gmail.com'
        ]);
    }
}
