<?php

use App\Constants\Permission;
use App\Constants\Platform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('api')->prefix('v1')->namespace('APIControllers')->group(function () {
    Route::get('companies/{companyCode}', 'CompanyController@getCompanyByCompanyCode');

    Route::post('user/password/forgot', 'AuthController@forgotPassword');
    Route::post('user/password/reset', 'AuthController@resetPassword');

    Route::middleware('VerifyToken')->group(function () {
        Route::get('setting', 'UserSettingController@getUserSetting');
        Route::post('setting', 'UserSettingController@updateUserSetting');

        //Notification
        Route::get('notifications', 'NotificationController@getListNotification');
        Route::get('notifications/unread', 'NotificationController@getNumberNotificationUnread');
        Route::patch('notifications/{notificationId}', 'NotificationController@updateStatusNotification');
        Route::patch('notifications', 'NotificationController@updateStatusAllNotifications');
        Route::delete('notifications/{notificationId}', 'NotificationController@deleteNotification');

        //albums
        Route::middleware('CheckAlbumBelongToUser')->group(function () {
            Route::get('albums/{albumId}/locations/{locationId}', 'AlbumsController@getAlbumLocationDetail');
            Route::delete('albums/{albumId}', 'AlbumsController@removeAlbum');
            Route::post('albums/{albumId}/modify', 'AlbumsController@checkModifyAlbum');
            Route::post('albums/{albumId}/locations/{locationId}/modify', 'AlbumLocationController@checkModifyLocation');
            Route::post('albums/{albumId}/locations/{locationId}/medias/{albumLocationMediaId}/modify', 'AlbumLocationController@checkModifyMedia');
        });
        Route::get('albums/{albumId}/locations', 'AlbumsController@getAlbumLocationTitle');

        //Album Location
        Route::put('albums/{albumId}/locations/{albumLocationId}', 'AlbumLocationController@editCommentLocation');
        Route::put('albums/{albumId}/locations/{albumLocationId}/medias/{albumLocationMediaId}', 'AlbumLocationController@editCommentLocationMedia');

        //files
        Route::post('user/avatar','UploadController@uploadAvatar');
        Route::post('albums/files','UploadController@uploadAlbumMedias');
        Route::post('albums/avatar','UploadController@uploadAlbumAvatar');
        Route::post('albums/pdf', 'UploadController@uploadAlbumPdf');
        Route::post('albums/{albumId}/locations/{albumLocationId}/files','UploadController@insertAlbumLocationMedias');
        Route::post('albums/{albumId}/locations/{albumLocationId}/medias/{albumLocationMediaId}','UploadController@updateAlbumLocationMedia');
        Route::post('albums/files/prepare-upload','UploadController@prepareFileUpload');
        Route::post('albums/{albumId}/locations/{albumLocationId}/medias/{albumLocationMediaId}/swap','UploadController@swapAfterBeforeImageAlbum');
        Route::post('albums/{albumId}/locations/{albumLocationId}/medias/{albumLocationMediaId}/image-before','UploadController@updateImageBeforeAlbumLocationMedia');
        Route::post('albums/{albumId}/locations/{albumLocationId}/medias/{albumLocationMediaId}/image-after','UploadController@updateImageAfterAlbumLocationMedia');
        Route::delete('albums/{albumId}/locations/{albumLocationId}/medias/{albumLocationMediaId}/image-after','UploadController@deleteImageAfterAlbumLocationMedia');

        //User
        Route::post('user/logout', 'AuthController@logout');
        Route::post('user/password/change', 'AuthController@changePassword');
        Route::patch('user/device', 'AuthController@updateDeviceToken');

    });
});
//API For App
Route::middleware('api')->prefix('v1/app')->namespace('AppControllers')->group(function () {

    //Auth
    Route::post('user/login', 'AuthController@login');

    //Only admin system
    Route::middleware('VerifyAdminToken')->group(function () {
        Route::get('companies','CompanyController@getListCompanies');
    });

    //Only Login
    Route::middleware('VerifyToken')->group(function () {

        //Company
        Route::get('user/company','CompanyController@getCompanyInfo');

        //Contract
        Route::get('user/contract','ContractController@getListContract');
        Route::get('contract','ContractController@getListContractsPaging');

        //User
        Route::get('user/profile', 'UserController@getCurrentUser');
        Route::get('user/verify', 'UserController@verifyUser');

        //Album
        Route::get('albums', 'AlbumController@getAlbums');
        Route::post('albums', 'AlbumController@createAlbum');
        Route::get('share/targets', 'AlbumController@getListTargetSharedAlbums');

        // Comment
        Route::get('albums/{albumId}/locations/{locationId}/new-comments','CommentController@getListNewLocationComment');
        Route::get('albums/{albumId}/locations/{locationId}/comments','CommentController@getListLocationComment');
        Route::post('albums/{albumId}/locations/{locationId}/comments','CommentController@addLocationComment');
        Route::put('albums/locations/{locationId}/comments/{commentId}', 'CommentController@editLocationComment');

        Route::get('albums/locations/{locationId}/medias/{mediaId}/new-comments', 'CommentController@getListNewMediaComment');
        Route::get('albums/locations/{locationId}/medias/{mediaId}/comments', 'CommentController@getListMediaComment');
        Route::post('albums/locations/{locationId}/medias/{mediaId}/comments', 'CommentController@addMediaComment');
        Route::put('albums/locations/medias/{mediaId}/comments/{commentId}','CommentController@editMediaComment');

        //Only admin company add owner
        Route::middleware('CheckAlbumBelongToUser')->group(function () {

            //Album
            Route::put('albums/{albumId}', 'AlbumController@updateAlbum');
            Route::get('albums/{albumId}', 'AlbumController@getAlbumDetail');
            Route::post('albums/{albumId}/avatar', 'AlbumController@updateAlbumAvatar');
            Route::post('albums/{albumId}/share', 'AlbumController@shareAlbumForEmail')->middleware('permissions:' . Platform::APP . ',' . Permission::SHARE_ALBUM);

            //Album Location
            Route::post('albums/{albumId}/locations', 'AlbumLocationController@createAlbumLocation');
            Route::put('albums/{albumId}/locations/{locationId}', 'AlbumLocationController@updateAlbumLocation');
            Route::delete('albums/{albumId}/locations/{albumLocationId}', 'AlbumLocationController@removeAlbumLocation');

            //Album Location Media
            Route::delete('albums/{albumId}/locations/{albumLocationId}/medias/{albumLocationMediaId}', 'AlbumLocationMediaController@removeAlbumLocationMedia');
            Route::get('albums/{albumId}/locations/{albumLocationId}/medias/{albumLocationMediaId}', 'AlbumLocationMediaController@getAlbumLocationMediaDetail');
            Route::put('albums/{albumId}/locations/{albumLocationId}/medias/{albumLocationMediaId}/change', 'AlbumLocationMediaController@changeLocationOfMedia');
        });

        //Album Setting
        Route::get('companies/{companyId}/location-types', 'AlbumSettingController@getLocationAlbum');
        Route::get('companies/{companyId}/album-settings', 'AlbumSettingController@getAlbumSetting');
        Route::get('companies/{companyId}/location-properties', 'AlbumSettingController@getLocationProperties');
        Route::get('companies/{companyId}/media-properties', 'AlbumSettingController@getMediaProperties');

        //Guideline
        Route::get('guidelines', 'GuidelineController@getListGuidelines');
        Route::get('guidelines/{guidelineId}', 'GuidelineController@getGuideline');
    });

    Route::get('departments', 'OtherController@getDepartments');
    Route::get('positions', 'OtherController@getPositions');
    Route::get('versions', 'OtherController@retrieveListAppVersions');

    //Link_version
    Route::get('version/links', 'OtherController@getLinkVersion');

    //ServiceActive
    Route::get('versions/active', 'OtherController@getVersionActive');
});

Route::middleware('api')->prefix('v2/app')->namespace('AppControllers')->group(function (){
    Route::middleware('VerifyToken')->group(function () {

        //User
        Route::get('user/profile', 'UserController@getCurrentUserV2');
        Route::get('user/verify', 'UserController@verifyUserV2');
    });
});
