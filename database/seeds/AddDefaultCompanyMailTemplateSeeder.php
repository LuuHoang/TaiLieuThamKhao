<?php

use App\Constants\App;
use App\Models\CompanyModel;
use App\Models\TemplateEmailModel;
use App\Services\WebService\TemplateService;
use Illuminate\Database\Seeder;

class AddDefaultCompanyMailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companiesWithMailTemplate = TemplateEmailModel::all()->pluck('company_id')->toArray();
        $companies = CompanyModel::whereNotIn('id', $companiesWithMailTemplate)->get();
        $service = app(TemplateService::class);

        foreach ($companies as $company) {
            $entity = (new TemplateEmailModel)->create([
                'company_id' => $company->id,
                'title' => 'Default email template',
                'subject' => 'SCSOFT VIET NAM JSCより画像アルバム共有のお知らせ（アルバムID： 109)',
                'default' => App::FLAG_YES,
                'content' => "<span>shared.guest.name</span>様<br>
<br>
<span>company.company_name</span>より画像アルバムを共有されましたので、以下のリンクにクリックして頂き、
本メールに添付しているパスワードをご入力頂ければご確認頂けます。<br>
<br>
メッセージ: <span>shared.guest.content</span><br>
リンク：<div>shared.link</div><br>
パスワード：<b>shared.password</b>
",
                'cc' => [],
                'bcc' => [],
            ]);
            $service->createTemplateEmailContent($entity->id, $entity->content);
        }
    }
}
