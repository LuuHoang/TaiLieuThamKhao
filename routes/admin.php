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

//Api for admin SCSoft
Route::middleware('api')->prefix('v1/admin')->namespace('WebControllers')->group(function () {

    //Auth
    Route::post('login', 'AuthController@loginAdmin');

    Route::middleware('VerifyAdminToken')->group(function () {

        //upload
        Route::post('avatar','AdminSCSoftController@uploadAvatar');

        //Auth
        Route::get('verify', 'AdminController@verifyAdmin');
        Route::post('logout', 'AdminController@logout');
        Route::get('all','AdminController@getListUserAreAdmin');

        // Admin
        Route::post('create','AdminController@createAdmin');
        Route::post('update/{adminId}','AdminController@updateAdmin');
        Route::delete('delete/{adminId}/{responsibleAdminId}','AdminController@deleteAdmin');
        Route::get('list','AdminController@getListAdmin');

        //Dashboards
        Route::get('dashboards', 'OtherController@getDashboardForAdmin');

        //Sample Contract
        Route::get('sample/contract/{sampleContractId}','AdminSampleContractController@getSampleContract');
        Route::post('sample/contract','AdminSampleContractController@createSampleContract');
        Route::put('sample/contract/{sampleContractId}','AdminSampleContractController@updateSampleContract');
        Route::delete('sample/contract/{sampleContractId}','AdminSampleContractController@deleteSampleContract');
        Route::get('list-sample-contract','AdminSampleContractController@getListSampleContract');
        Route::get('list-short-code','AdminSampleContractController@getListShortCode');

        // Contract
        Route::get('contract/{contractId}','AdminContractController@getContractInfo');
        Route::post('contract','AdminContractController@createContract');
        Route::get('list/sample/contract','AdminContractController@getListSampleContracts');
        Route::get('list/contract','AdminContractController@getListContracts');
        Route::put('contract/{contractId}','AdminContractController@updateContract');
        Route::get('list-contract-by-company/{companyId}','AdminContractController@getListContractsByCompany');

        //Company
        Route::get('companies', 'CompanyController@getListCompanies');
        Route::get('companies/{companyId}', 'CompanyController@getCompany');
        Route::post('companies', 'CompanyController@createCompany');
        Route::post('companies/{companyId}', 'CompanyController@updateCompany');
        Route::delete('companies/{companyId}', 'CompanyController@deleteCompany');

        Route::get('list-company', 'OtherController@getListCompanies');

        //User
        Route::post('users', 'AdminSCSoftController@createUser');
        Route::put('users/{userId}', 'AdminSCSoftController@updateUser');
        Route::get('users/{userId}', 'AdminSCSoftController@getUser');
        Route::delete('users/{userId}', 'AdminSCSoftController@deleteUser');
        Route::get('users', 'AdminSCSoftController@getAllUser');

        //Packages
        Route::get('packages', 'PackageController@getListServicePackages');
        Route::post('packages', 'PackageController@createServicePackage');
        Route::get('packages/{packageId}', 'PackageController@getServicePackage');
        Route::put('packages/{packageId}', 'PackageController@updateServicePackage');
        Route::delete('packages/{packageId}', 'PackageController@deleteServicePackage');
        Route::get('package/all','PackageController@getAllListServicePackages');


        //Extend
        Route::get('extends', 'PackageController@getListExtendPackages');
        Route::post('extends', 'PackageController@createExtendPackage');
        Route::get('extends/{extendId}', 'PackageController@getExtendPackage');
        Route::put('extends/{extendId}', 'PackageController@updateExtendPackage');
        Route::delete('extends/{extendId}', 'PackageController@deleteExtendPackage');

        //Role
        Route::get('companies/{companyId}/roles', 'AdminSCSoftController@getListCompanyRole');

        //App version
        Route::get('versions', 'AppVersionController@retrieveListAppVersions');
        Route::post('versions', 'AppVersionController@createAppVersion');
        Route::get('versions/{versionId}', 'AppVersionController@retrieveAppVersionDetail');
        Route::put('versions/{versionId}', 'AppVersionController@updateAppVersion');

        //Link_version
        Route::get('version/links','AdminSCSoftController@getLinkVersion');
        Route::post('version/links', 'AdminSCSoftController@createOrUpdateVersionLink');
    });
});

//Api for web
Route::middleware('api')->prefix('v1/web')->namespace('WebControllers')->group(function () {

    //Auth
    Route::post('user/login', 'AuthController@login');

    //album shared
    Route::middleware('VerifyShareUser')->group(function () {
        Route::post('albums/shared', 'AlbumController@getSharedAlbum');

        Route::post('shared/albums/{albumId}/locations/{locationId}/comments/list', 'CommentController@getListShareAlbumLocationComment');
        Route::post('shared/albums/{albumId}/locations/{locationId}/comments', 'CommentController@createShareAlbumLocationComment');
        Route::put('shared/albums/locations/{locationId}/comments/{commentId}', 'CommentController@editShareAlbumLocationComment');

        Route::post('shared/albums/locations/{locationId}/medias/{mediaId}/comments/list', 'CommentController@getListShareAlbumLocationMediaComment');
        Route::post('shared/albums/locations/{locationId}/medias/{mediaId}/comments', 'CommentController@createShareAlbumLocationMediaComment');
        Route::put('shared/albums/locations/medias/{mediaId}/comments/{commentId}', 'CommentController@editShareAlbumLocationMediaComment');
    });

    //Only login
    Route::middleware('VerifyToken')->group(function () {

        //Album pdf format
        Route::middleware('permissions:' . Platform::WEB . ',' . Permission::PDF_CONFIG)->group(function () {
            Route::get('album-pdfs/formats', 'AlbumPDFController@getListAlbumPDFFormats');
            Route::post('album-pdfs/formats', 'AlbumPDFController@createAlbumPDFFormat');
            Route::get('album-pdfs/formats/{formatId}', 'AlbumPDFController@getAlbumPDFFormat');
            Route::put('album-pdfs/formats/{formatId}', 'AlbumPDFController@updateAlbumPDFFormat');
            Route::delete('album-pdfs/formats/{formatId}', 'AlbumPDFController@deleteAlbumPDFFormat');
            Route::get('album-pdfs/config', 'AlbumPDFController@getConfigAlbumPDFFormat');
        });
        Route::get('other/pdf/formats', 'OtherController@getListPDFFormat');

        //User
        Route::get('user/profile', 'UserController@getCurrentUser');
        Route::put('user/profile', 'UserController@updateCurrentUser');
        Route::get('user/verify', 'UserController@verifyUser');

        //Album
        Route::get('albums', 'AlbumController@getAlbums')->middleware('permissions:' . Platform::WEB . ',' . Permission::ALBUM_MANAGER);
        Route::post('albums', 'AlbumController@createAlbum')->middleware('permissions:' . Platform::WEB . ',' . Permission::ALBUM_MANAGER);

        //Company config Stamp
        Route::post('config/stamp','CompanyStampConfigController@updateCompanyConfigStampAlbum');
        Route::get('config/stamp','CompanyStampConfigController@getStampConfig');

        //Share Album
        Route::prefix('share')->middleware('permissions:' . Platform::WEB . ',' . Permission::SHARE_ALBUM)->group(function () {
            Route::get('albums', 'AlbumController@getListSharedAlbums');
            Route::patch('albums/{sharedAlbumId}', 'AlbumController@changeStatusSharedAlbum');
            Route::get('targets', 'AlbumController@getListTargetSharedAlbums');
        });

        Route::prefix('album-types')->group(function () {
            Route::get('/', 'AlbumSettingController@getTypeAlbum');
            Route::post('/', 'AlbumSettingController@addAlbumType');
            Route::put('/{albumTypeId}', 'AlbumSettingController@updateAlbumType');
            Route::delete('/{albumTypeId}', 'AlbumSettingController@deleteAlbumType');
        });

        //Album Setting
//        Route::middleware('permissions:' . Platform::WEB . ',' . Permission::ALBUM_CONFIG)->group(function () {
        Route::get('companies/{companyId}/album-types', 'AlbumSettingController@getTypeAlbum');
        Route::get('companies/{companyId}/album-settings', 'AlbumSettingController@getAlbumSetting');
        Route::get('companies/{companyId}/album-properties', 'AlbumSettingController@getAlbumProperties');
        Route::get('companies/{companyId}/location-types', 'AlbumSettingController@getLocationAlbum');
        Route::get('companies/{companyId}/location-settings', 'AlbumSettingController@getLocationSetting');
        Route::get('companies/{companyId}/location-properties', 'AlbumSettingController@getLocationProperties');
        Route::get('companies/{companyId}/media-properties', 'AlbumSettingController@getMediaProperties');
//        });

        //Company
        Route::get('companies', 'CompanyController@getCompanyCurrentUser')->middleware('permissions:' . Platform::WEB . ',' . Permission::COMPANY_MANAGER);
        //Contract
        Route::get('contracts','AdminContractController@getListContractsByCurrentUsers');

        //User
        Route::get('users', 'AdminCompanyController@getListUser')->middleware('permissions:' . Platform::WEB . ',' . Permission::USER_MANAGER);
        Route::post('users', 'AdminCompanyController@createUser')->middleware('permissions:' . Platform::WEB . ',' . Permission::USER_MANAGER);

        //Check user belong to company
        Route::middleware(['CheckUserBelongToCompany', 'permissions:' . Platform::WEB . ',' . Permission::USER_MANAGER])->group(function () {
            Route::get('users/{userId}', 'AdminCompanyController@getUser');
            Route::put('users/{userId}', 'AdminCompanyController@updateUser');
            Route::delete('users/{userId}', 'AdminCompanyController@deleteUser');
        });

        //Album Setting
        Route::middleware('permissions:' . Platform::WEB . ',' . Permission::ALBUM_CONFIG)->group(function () {

            // Album Type Version 2
            Route::group(['prefix' => 'album-types', 'as' => 'album-types'], function () {
                Route::get('/{albumTypeId}/album-information-config', 'AlbumSettingController@getAlbumConfigByAlbumType');
                Route::post('/{albumTypeId}/album-properties', 'AlbumSettingController@addAlbumProperty');
                Route::put('/{albumTypeId}/album-properties/{albumPropertyId}', 'AlbumSettingController@updateAlbumProperty');
                Route::delete('/{albumTypeId}/album-properties/{albumPropertyId}', 'AlbumSettingController@deleteAlbumProperty');
                Route::post('/{albumTypeId}/location-properties', 'AlbumSettingController@addLocationProperty');
                Route::put('/{albumTypeId}/location-properties/{locationPropertyId}', 'AlbumSettingController@updateLocationProperty');
                Route::delete('/{albumTypeId}/location-properties/{locationPropertyId}', 'AlbumSettingController@deleteLocationProperty');
            });

            Route::post('companies/{companyId}/album-types', 'AlbumSettingController@addAlbumType');
            Route::post('companies/{companyId}/location-types', 'AlbumSettingController@addLocationType');
            Route::put('companies/{companyId}/album-types/{albumTypeId}', 'AlbumSettingController@updateAlbumType');
            Route::put('companies/{companyId}/location-types/{locationTypeId}', 'AlbumSettingController@updateLocationType');
            Route::delete('companies/{companyId}/album-types/{albumTypeId}', 'AlbumSettingController@deleteAlbumType');
            Route::delete('companies/{companyId}/location-types/{locationTypeId}', 'AlbumSettingController@deleteLocationType');
            Route::post('companies/{companyId}/location-properties', 'AlbumSettingController@addLocationProperty');
            Route::put('companies/{companyId}/location-properties/{locationPropertyId}', 'AlbumSettingController@updateLocationProperty');
            Route::delete('companies/{companyId}/location-properties/{locationPropertyId}', 'AlbumSettingController@deleteLocationProperty');
            Route::post('album-types/{albumTypeId}/media-properties', 'AlbumSettingController@addMediaProperty');
            Route::put('album-types/{albumTypeId}/media-properties/{mediaPropertyId}', 'AlbumSettingController@updateMediaProperty');
            Route::delete('album-types/{albumTypeId}/media-properties/{mediaPropertyId}', 'AlbumSettingController@deleteMediaProperty');
        });

        //Company
        Route::post('companies/{companyId}', 'AdminCompanyController@updateCompany')->middleware('permissions:' . Platform::WEB . ',' . Permission::COMPANY_MANAGER);

        //Guideline
        Route::middleware('permissions:' . Platform::WEB . ',' . Permission::GUIDELINE_MANAGER)->group(function () {
            Route::post('guidelines', 'GuidelineController@createGuideline');
            Route::get('guidelines', 'GuidelineController@getListGuidelines');
            Route::get('guidelines/{guidelineId}', 'GuidelineController@getGuideline');
            Route::post('guidelines/{guidelineId}', 'GuidelineController@updateGuideline');
            Route::delete('guidelines/{guidelineId}', 'GuidelineController@deleteGuideline');
            Route::delete('guidelines/{guidelineId}/information/{informationId}', 'GuidelineController@deleteGuidelineInformation');
            Route::delete('guidelines/{guidelineId}/information/{informationId}/medias/{mediaId}', 'GuidelineController@deleteGuidelineInformationMedia');
        });

        //Only admin company add owner
        Route::middleware(['CheckAlbumBelongToUser', 'permissions:' . Platform::WEB . ',' . Permission::ALBUM_MANAGER])->group(function () {

            //Album
            Route::put('albums/{albumId}', 'AlbumController@updateAlbum');
            Route::get('albums/{albumId}', 'AlbumController@getAlbumDetail');
            Route::post('albums/{albumId}/avatar', 'AlbumController@updateAlbumAvatar');
            Route::post('albums/{albumId}/share', 'AlbumController@shareAlbumForEmail')->middleware('permissions:' . Platform::WEB . ',' . Permission::SHARE_ALBUM);
            Route::get('albums/{albumId}/config', 'AlbumController@getConfigNameAlbum');
            Route::patch('albums/{albumId}/config', 'AlbumController@setConfigNameAlbum');
            Route::get('albums/{albumId}/medias/download', 'AlbumController@downloadMediaByNameConfig');

            //Generate Album PDF
            Route::get('albums/{albumId}/export', 'AlbumController@exportAlbumPDF');
            Route::get('albums/{albumId}/pdfs/export', 'AlbumController@exportAlbumPDFFile');
            Route::get('albums/{albumId}/pdfs/preview', 'AlbumController@previewAlbumPDF');

            //Album Location
            Route::post('albums/{albumId}/locations', 'AlbumLocationController@createAlbumLocation');
            Route::put('albums/{albumId}/locations/{locationId}', 'AlbumLocationController@updateAlbumLocation');
            Route::delete('albums/{albumId}/locations/{albumLocationId}', 'AlbumLocationController@removeAlbumLocation');

            //Album Location Media
            Route::delete('albums/{albumId}/locations/{albumLocationId}/medias/{albumLocationMediaId}', 'AlbumLocationMediaController@removeAlbumLocationMedia');
            Route::put('albums/{albumId}/locations/{albumLocationId}/medias/{albumLocationMediaId}/change', 'AlbumLocationMediaController@changeLocationOfMedia');
        });

        // Comment
        Route::get('albums/{albumId}/locations/{locationId}/comments','CommentController@getListLocationComment');
        Route::post('albums/{albumId}/locations/{locationId}/comments','CommentController@addLocationComment');
        Route::put('albums/locations/{locationId}/comments/{commentId}', 'CommentController@editLocationComment');

        Route::get('albums/locations/{locationId}/medias/{mediaId}/comments', 'CommentController@getListMediaComment');
        Route::post('albums/locations/{locationId}/medias/{mediaId}/comments', 'CommentController@addMediaComment');
        Route::put('albums/locations/medias/{mediaId}/comments/{commentId}','CommentController@editMediaComment');

        Route::get('list-users', 'OtherController@getListUsers');
        Route::get('all-users', 'OtherController@getListAllUsers');

        //Dashboards
        Route::get('dashboards', 'OtherController@getDashboard');

        //Role Management
        Route::get('roles', 'UserRoleController@retrieveListRoles');
        Route::get('roles/{roleId}', 'UserRoleController@retrieveRoleDetail');
        Route::post('roles', 'UserRoleController@createRole');
        Route::put('roles/{roleId}', 'UserRoleController@updateRole');
        Route::delete('roles/{roleId}', 'UserRoleController@deleteRole');

        //import export
        Route::post('user/import', 'AdminCompanyController@importUsers');
        Route::get('user/export', 'AdminCompanyController@exportUsers');

        Route::group(['prefix' => 'template', 'as' => 'template'], function () {
            Route::group(['prefix' => 'emails', 'as' => 'email'], function () {
                Route::get('/', 'TemplateController@getTemplateEmailList');
                Route::get('/{id}', 'TemplateController@getTemplateEmailDetail')->where('id', '[0-9]+');
                Route::post('/', 'TemplateController@createTemplateEmail');
                Route::delete('/{id}', 'TemplateController@deleteTemplateEmail')->where('id', '[0-9]+');
                Route::put('/{id}', 'TemplateController@updateTemplateEmail')->where('id', '[0-9]+');
                Route::patch('/{id}', 'TemplateController@updateTemplateEmailDefault')->where('id', '[0-9]+');
                Route::get('/config', 'TemplateController@getTemplateEmailConfig');
                Route::get('/all', 'TemplateController@getAllTemplateEmails');
                Route::get('/sample', 'TemplateController@getTemplateEmailSample');
            });
        });
    });

    Route::get('departments', 'OtherController@getDepartments');
    Route::get('positions', 'OtherController@getPositions');
    Route::get('packages', 'OtherController@getServicePackages');
    Route::get('extends', 'OtherController@getExtendPackages');
    Route::get('other/companies/{companyId}/roles', 'OtherController@retrieveListRoleInCompany');
    Route::get('companies/{companyId}/list-users', 'OtherController@getListUsersOfCompany');
    Route::get('pdf/templates', 'OtherController@getListPDFContentTemplates');
});

Route::middleware('api')->prefix('v2/web')->namespace('WebControllers')->group(function (){
    Route::middleware('VerifyToken')->group(function () {

        //User
        Route::get('user/profile', 'UserController@getCurrentUserV2');
    });
});
