<?php

use Illuminate\Database\Seeder;
use App\Models\CompanyModel;
use App\Models\ContractModel;
class ContractDefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $companies = CompanyModel::all('id')->toArray();
        foreach ($companies as $company) {
            (new ContractModel())->create([
                'sample_contract_id' => 3,
                'name_company_rental' => 'Công ty TNHH SC Soft Việt Nam',
                'address_company_rental' => '187 Nguyễn Tuân, Thanh Xuân, Hà Nội',
                'represent_company_rental' => 'Nguyễn Văn Minh',
                'gender_rental' => 1,
                'phone_number_rental' => '09892265658',
                'represent_company_hire' => 'Trịnh Đình Nam',
                'gender_hire'       => 1,
                'phone_company_hire' => '0656515454',
                'service_package_id' => 1,
                'type_service_package' => 0,
                'extend_package_id' => 1,
                'effective_date'   => '2020-01-01',
                'end_date'   => '2025-01-01',
                'cancellation_notice_deadline' => 30,
                'tax'               => 10,
                'total_price'       => 555555555,
                'payment_status'    => 3,
                'deposit_money'     =>55555,
                'payment_term_all'  => 60,
                'employee_represent' => 1,
                'contract_status'  => 3,
                'date_signed'   => '2020-01-01',
                'company_id'    =>$company['id'],
            ]);
        }
    }
}
