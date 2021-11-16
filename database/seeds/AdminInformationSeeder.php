<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class AdminInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('admin_informations')->insert([
            'company_name' => 'Công ty TNHH SC Soft Việt Nam',
            'address' => '187 Nguyễn Tuân, Thanh Xuân, Hà Nội',
            'phone' => '0965484497',
        ]);
    }
}
