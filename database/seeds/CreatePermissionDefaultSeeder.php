<?php

use App\Constants\App;
use App\Constants\UserRole;
use App\Constants\UserRoleDefault;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreatePermissionDefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companyEntities = DB::table('companies')->whereNull('deleted_at')->get();
        if ($companyEntities->isNotEmpty()) {
            foreach ($companyEntities as $companyEntity) {
                $roleAdminId = DB::table('user_roles')->insertGetId([
                    'company_id' => $companyEntity->id,
                    'name'  =>  'Admin',
                    'description' => '',
                    'permissions' => json_encode(json_encode(UserRoleDefault::ADMIN)),
                    'is_admin'  =>  App::FLAG_YES,
                    'is_default' => App::FLAG_YES,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                DB::table('users')
                    ->where('company_id', $companyEntity->id)
                    ->where('role', UserRole::ADMIN)
                    ->whereNull('deleted_at')
                    ->update(['role_id' => $roleAdminId]);

                $roleUserId = DB::table('user_roles')->insertGetId([
                    'company_id' => $companyEntity->id,
                    'name'  =>  'User',
                    'description' => '',
                    'permissions' => json_encode(json_encode(UserRoleDefault::USER)),
                    'is_admin'  =>  App::FLAG_NO,
                    'is_default' => App::FLAG_YES,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                DB::table('users')
                    ->where('company_id', $companyEntity->id)
                    ->where('role', UserRole::USER)
                    ->whereNull('deleted_at')
                    ->update(['role_id' => $roleUserId]);

                $roleSubUserId = DB::table('user_roles')->insertGetId([
                    'company_id' => $companyEntity->id,
                    'name'  =>  'Sub User',
                    'description' => '',
                    'permissions' => json_encode(json_encode(UserRoleDefault::SUB_USER)),
                    'is_admin'  =>  App::FLAG_NO,
                    'is_default' => App::FLAG_NO,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                DB::table('users')
                    ->where('company_id', $companyEntity->id)
                    ->where('role', UserRole::SUB_USER)
                    ->whereNull('deleted_at')
                    ->update(['role_id' => $roleSubUserId]);
            }
        }
    }
}
