<?php

use Illuminate\Database\Seeder;
use App\Models\CompanyStampConfigModel;
use App\Models\CompanyModel;
use App\Constants\StampType;
use App\Services\WebService\CompanyConfigAlbumService;

class DefaultStampConfigSettingSeeder extends Seeder
{
    const DEFAULT_ICON_PATH = 'logo_default/ic_logo.png';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $companyConfigs = CompanyStampConfigModel::all()->pluck('company_id')->toArray();
        $companies = CompanyModel::whereNotIn('id', $companyConfigs)->get();
        foreach ($companies as $company) {
            (new CompanyStampConfigModel)->create([
                'company_id' => $company->id,
                'text' => '',
                'stamp_type' => StampType::ICON,
                'mounting_position' => StampType::BOTTOMRIGHT,
                'icon_path' => self::DEFAULT_ICON_PATH,
            ]);
        }
    }
}

