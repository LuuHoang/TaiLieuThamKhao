<?php

namespace App\Models;

use App\Constants\CommentCreator;
use App\Constants\Disk;
use App\Constants\UserSettingDefault;
use App\Services\CommonService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class UserModel extends AbstractModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'user_created_id', 'staff_code', 'full_name', 'email', 'address', 'password', 'avatar_path', 'role_id','department', 'position'];

    /**
     * @return HasOne
     */
    public function userSetting()
    {
        return $this->hasOne('App\Models\UserSettingModel', 'user_id');
    }

    public function userForgotPassword()
    {
        return $this->hasOne('App\Models\UserForgotPasswordModel', 'user_id');
    }

    public function userUsage()
    {
        return $this->hasOne('App\Models\UserUsageModel', 'user_id');
    }

    /**
     * @return HasMany
     */
    public function albums()
    {
        return $this->hasMany('App\Models\AlbumModel', 'user_id');
    }

    public function userTokens()
    {
        return $this->hasMany('App\Models\UserTokenModel', 'user_id');
    }

    public function sharedAlbums()
    {
        return $this->hasMany('App\Models\SharedAlbumModel', 'user_id');
    }

    public function subUsers()
    {
        return $this->hasMany('App\Models\UserModel', 'user_created_id');
    }

    public function notifications()
    {
        return $this->hasMany('App\Models\NotificationModel', 'user_id');
    }

    public function albumPDFFormats()
    {
        return $this->hasMany('App\Models\AlbumPDFFormatModel', 'user_id');
    }

    public function albumLocationComments()
    {
        return $this->hasMany('App\Models\AlbumLocationCommentModel', 'creator_id')
            ->where('creator_type', '=', CommentCreator::USER);
    }

    public function albumLocationMediaComments()
    {
        return $this->hasMany('App\Models\AlbumLocationMediaCommentModel', 'creator_id')
            ->where('creator_type', '=', CommentCreator::USER);
    }

    /**
     * @return BelongsTo
     */

    public function company()
    {
        return $this->belongsTo('App\Models\CompanyModel', 'company_id');
    }

    public function userCreated()
    {
        return $this->belongsTo('App\Models\UserModel', 'user_created_id');
    }

    public function userRole()
    {
        return $this->belongsTo('App\Models\UserRoleModel', 'role_id');
    }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar_path ? Storage::disk(Disk::USER)->url($this->avatar_path) : null;
    }

    public static function boot()
    {
        parent::boot();
        static::created(function ($userModel) {
            $commonService = app(CommonService::class);
            $userModel->userUsage()->create([
                'count_album' => 0,
                'count_data' => 0,
                'package_data' => $userModel->company->servicePackage->max_user_data,
                'extend_data' => 0
            ]);
            $userModel->userSetting()->create([
                'image_size' => UserSettingDefault::IMAGE_SIZE,
                'language' => UserSettingDefault::LANGUAGE,
                'voice' => UserSettingDefault::VOICE,
                'comment_display' => UserSettingDefault::COMMENT_DISPLAY
            ]);
            if (!$commonService->isSubUser(json_decode($userModel->userRole->permissions ?? '[]', true))) {
                $companyUsage = $userModel->company->companyUsage;
                $companyUsage->update([
                    'count_user' => $companyUsage->count_user + 1
                ]);
            }
        });
        static::deleting(function ($userModel) {
            $userModel->userUsage()->delete();
            $userModel->userTokens()->delete();
            $userModel->userSetting()->delete();
            $userModel->userForgotPassword()->delete();
            $userModel->sharedAlbums()->delete();
            $userModel->notifications()->delete();
            $userModel->albumPDFFormats()->delete();
            $userModel->albumLocationComments()->delete();
            $userModel->albumLocationMediaComments()->delete();

            $subUsers = $userModel->subUsers()->get();
            foreach ($subUsers as $subUser) {
                $subUser->delete();
            }

            $albums = $userModel->albums()->get();
            foreach ($albums as $album) {
                $album->delete();
            }
        });
        static::deleted(function ($userModel) {
            Storage::disk(Disk::USER)->delete($userModel->avatar_path);
        });
    }
}
