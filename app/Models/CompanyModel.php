<?php

namespace App\Models;

use App\Constants\App;
use App\Constants\Disk;
use App\Constants\UserRoleDefault;
use App\Services\WebService\TemplateService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class CompanyModel extends AbstractModel
{
	use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'companies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['service_id', 'service_id', 'extend_id', 'color', 'logo_path', 'company_name', 'company_code', 'address', 'representative', 'tax_code'];

	/**
     * @return HasMany
     */
    public function albumProperties()
    {
        return $this->hasMany('App\Models\AlbumPropertyModel', 'company_id');
    }

    public function albumTypes()
    {
        return $this->hasMany('App\Models\AlbumTypeModel', 'company_id');
    }

    public function locationTypes()
    {
        return $this->hasMany('App\Models\LocationTypeModel', 'company_id');
    }

    public function users()
    {
        return $this->hasMany('App\Models\UserModel', 'company_id');
    }

    public function locationProperties()
    {
        return $this->hasMany('App\Models\LocationPropertyModel', 'company_id');
    }

    public function mediaProperties()
    {
        return $this->hasMany('App\Models\MediaPropertyModel', 'company_id');
    }

    public function guidelines()
    {
        return $this->hasMany('App\Models\GuidelineModel', 'company_id');
    }

    public function userRoles()
    {
        return $this->hasMany('App\Models\UserRoleModel', 'company_id');
    }
    public function contracts()
    {
        return $this->hasMany('App\Models\ContractModel', 'company_id');
    }

    /**
     * @return HasOne
     */
    public function companyUsage()
    {
        return $this->hasOne('App\Models\CompanyUsageModel', 'company_id');
    }

	/**
     * @return BelongsTo
     */
    public function servicePackage()
    {
        return $this->belongsTo('App\Models\ServicePackageModel', 'service_id');
    }

    public function extendPackage()
    {
        return $this->belongsTo('App\Models\ExtendPackageModel', 'extend_id');
    }

    public function getLogoUrlAttribute()
    {
        return $this->logo_path ? Storage::disk(Disk::COMPANY)->url($this->logo_path) : null;
    }

    public function templateEmail()
    {
        return $this->hasMany(TemplateEmailModel::class, 'company_id', 'id');
    }

    public function config()
    {
        return $this->hasOne(CompanyStampConfigModel::class, 'company_id', 'id');
    }

    public static function boot()
    {
        parent::boot();
        static::created(function ($companyModel) {
            $companyModel->companyUsage()->create([
                'count_user'   =>  0,
                'count_extend_data'    =>  0,
                'count_data'   =>  0
            ]);
            $companyModel->userRoles()->createMany([
                [
                    'name'  =>  'Admin',
                    'description' => '',
                    'permissions' => json_encode(UserRoleDefault::ADMIN),
                    'is_admin'  =>  App::FLAG_YES,
                    'is_default' => App::FLAG_YES,
                ],
                [
                    'name'  =>  'User',
                    'description' => '',
                    'permissions' => json_encode(UserRoleDefault::USER),
                    'is_admin'  =>  App::FLAG_NO,
                    'is_default' => App::FLAG_YES,
                ],
                [
                    'name'  =>  'Sub User',
                    'description' => '',
                    'permissions' => json_encode(UserRoleDefault::SUB_USER),
                    'is_admin'  =>  App::FLAG_NO,
                    'is_default' => App::FLAG_NO,
                ]
            ]);
            $entity = $companyModel->templateEmail()->create([
                'company_id' => $companyModel->id,
                'title' => 'Default email template',
                'subject' => 'SCSOFT VIET NAM JSCより画像アルバム共有のお知らせ（アルバムID： 109)',
                'default' => App::FLAG_YES,
                'content' => "<span>shared.guest.name</span>様<br>,
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
            app(TemplateService::class)->createTemplateEmailContent($entity->id, $entity->content);
        });
        static::deleting(function ($companyModel) {
            foreach ($companyModel->users as $user) {
                $user->delete();
            }
            $companyModel->albumProperties()->delete();
            $companyModel->locationProperties()->delete();
            $companyModel->mediaProperties()->delete();
            $companyModel->albumTypes()->delete();
            $companyModel->locationTypes()->delete();
            $companyModel->companyUsage()->delete();
            $companyModel->userRoles()->delete();
        });
        static::deleted(function ($companyModel) {
            Storage::disk(Disk::COMPANY)->delete($companyModel->logo_path);
        });
    }
}
