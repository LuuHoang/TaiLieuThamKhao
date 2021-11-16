<?php

namespace App\Services\WebService;

use App\Constants\Example;
use App\Constants\InputType;
use App\Models\CompanyModel;
use App\Models\AlbumPDFModel;
use App\Models\AlbumTypeModel;
use App\Repositories\Repository;
use App\Services\AbstractService;
use Illuminate\Support\Collection;
use App\Models\AlbumPDFFormatModel;
use App\Constants\AlbumPDFKeyConfig;
use App\Exceptions\NotFoundException;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ShortAlbumPropertyResource;
use App\Http\Resources\ShortCompanyDetailResource;
use App\Http\Resources\ShortMediaPropertyResource;
use App\Http\Resources\ShortLocationPropertyResource;
use App\Repositories\Criteria\SearchAlbumPDFFormatsCriteria;

class AlbumPDFService extends AbstractService
{
    private $_albumPDFFormatRepository;
    private $_albumPDFRepository;
    private $_albumTypeRepository;

    public function __construct(AlbumPDFFormatModel $albumPDFFormatModel, AlbumPDFModel $albumPDFModel, AlbumTypeModel $albumTypeModel)
    {
        $this->_albumPDFFormatRepository = new Repository($albumPDFFormatModel);
        $this->_albumPDFRepository = new Repository($albumPDFModel);
        $this->_albumTypeRepository = new Repository($albumTypeModel);
    }

    public function getListAlbumPDFFormats(?int $limit = null, array $paramQuery = null)
    {
        // dd($paramQuery);
        $currentUser = app('currentUser');
        $companyId = $currentUser->company_id;
        $userIds = $currentUser->company->users->pluck('id')->values()->toArray();
        $query = $this->_albumPDFFormatRepository
            ->whereIn('user_id', $userIds)
            ->with(['user']);
        if ($paramQuery && !empty($paramQuery)) {
            $query->pushCriteria(new SearchAlbumPDFFormatsCriteria($paramQuery));
        }

        return is_null($limit) ? $query->all() : $query->paginate($limit);
    }

    public function createAlbumPDFFormat (Array $params)
    {
        $currentUser = app('currentUser');
        $this->getCurrentCompanyAlbumTypeById($params['album_type_id']);
        $preFileName = time() . '_' . $currentUser->id;
        $coverPage = $this->generateCoverPageViewAlbumPDFFormat($params['cover_page'], $preFileName);
        $contentPage = $this->generateContentPageViewAlbumPDFFormat($params['content_page'], $preFileName);
        $lastPage = $this->generateLastPageViewAlbumPDFFormat($params['last_page'], $preFileName);
        $params['cover_path'] = $coverPage['cover_path'];
        $params['preview_cover_path'] = $coverPage['cover_preview_path'];
        $params['content_path'] = $contentPage['content_path'];
        $params['preview_content_path'] = $contentPage['content_preview_path'];
        $params['last_path'] = $lastPage['last_path'];
        $params['preview_last_path'] = $lastPage['last_preview_path'];
        $params['number_images'] = $contentPage['count_images'];
        return $currentUser->albumPDFFormats()->create($params);
    }

    public function getAlbumPDFFormat (int $formatId)
    {
        $currentUser = app('currentUser');
        $userIds = $currentUser->company->users->pluck('id')->values()->toArray();
        $albumPDFFormatEntity = $this->_albumPDFFormatRepository
            ->whereIn('user_id', $userIds)
            ->find($formatId);
        if ($albumPDFFormatEntity == null) {
            throw new NotFoundException('messages.album_pdf_format_does_not_exist');
        }
        return $albumPDFFormatEntity;
    }

    public function updateAlbumPDFFormat (Array $params, int $formatId)
    {
        $currentUser = app('currentUser');
        $this->getCurrentCompanyAlbumTypeById($params['album_type_id']);
        $userIds = $currentUser->company->users->pluck('id')->values()->toArray();
        $preFileName = time() . '_' . $currentUser->id;
        $albumPDFFormatEntity = $this->_albumPDFFormatRepository
            ->whereIn('user_id', $userIds)
            ->find($formatId);
        if ($albumPDFFormatEntity == null) {
            throw new NotFoundException('messages.album_pdf_format_does_not_exist');
        }
        if (!empty($params['cover_page'])) {
            $coverPage = $this->generateCoverPageViewAlbumPDFFormat($params['cover_page'], $preFileName);
            $params['cover_path'] = $coverPage['cover_path'];
            $params['preview_cover_path'] = $coverPage['cover_preview_path'];
            Storage::disk('public')->delete('views/' . $albumPDFFormatEntity->cover_path . '.blade.php');
            Storage::disk('public')->delete('views/' . $albumPDFFormatEntity->preview_cover_path . '.blade.php');
        }
        if (!empty($params['content_page'])) {
            $contentPage = $this->generateContentPageViewAlbumPDFFormat($params['content_page'], $preFileName);
            $params['content_path'] = $contentPage['content_path'];
            $params['preview_content_path'] = $contentPage['content_preview_path'];
            $params['number_images'] = $contentPage['count_images'];
            Storage::disk('public')->delete('views/' . $albumPDFFormatEntity->content_path . '.blade.php');
            Storage::disk('public')->delete('views/' . $albumPDFFormatEntity->preview_content_path . '.blade.php');
        }
        if (!empty($params['last_page'])) {
            $lastPage = $this->generateLastPageViewAlbumPDFFormat($params['last_page'], $preFileName);
            $params['last_path'] = $lastPage['last_path'];
            $params['preview_last_path'] = $lastPage['last_preview_path'];
            Storage::disk('public')->delete('views/' . $albumPDFFormatEntity->last_path . '.blade.php');
            Storage::disk('public')->delete('views/' . $albumPDFFormatEntity->preview_last_path . '.blade.php');
        }
        $this->_albumPDFRepository->delete([['style_id', '=', $formatId]]);
        return tap($albumPDFFormatEntity)->update($params);
    }

    public function deleteAlbumPDFFormat(int $formatId)
    {
        $currentUser = app('currentUser');
        $userIds = $currentUser->company->users->pluck('id')->values()->toArray();
        $albumPDFFormatEntity = $this->_albumPDFFormatRepository
            ->whereIn('user_id', $userIds)
            ->find($formatId);
        if ($albumPDFFormatEntity == null) {
            throw new NotFoundException('messages.album_pdf_format_does_not_exist');
        }
        Storage::disk('public')->delete('views/' . $albumPDFFormatEntity->cover_path . '.blade.php');
        Storage::disk('public')->delete('views/' . $albumPDFFormatEntity->content_path . '.blade.php');
        Storage::disk('public')->delete('views/' . $albumPDFFormatEntity->last_path . '.blade.php');
        $albumPDFFormatEntity->delete();
    }

    public function getConfigAlbumPDFFormat()
    {
        $currentUser = app('currentUser');
        $companyEntity = $currentUser->company;
        $albumPropertyEntities = $companyEntity->albumProperties;
        $locationPropertyEntities = $companyEntity->locationProperties;
        $mediaPropertyEntities = $companyEntity->mediaProperties;
        return $this->generateKeyConfigAlbumPDFFormat($companyEntity, $albumPropertyEntities, $locationPropertyEntities, $mediaPropertyEntities);
    }

    public function generateKeyConfigAlbumPDFFormat(CompanyModel $companyModel, Collection $albumProperties, Collection $locationProperties, Collection $mediaProperties)
    {
        $locale = app('currentUser')->userSetting->language;
        $data['company'] = [
            "company.company_name"      =>  $companyModel->company_name,
            "company.company_code"      =>  $companyModel->company_code,
            "company.address"           =>  $companyModel->address,
            "company.representative"    =>  $companyModel->representative,
            "company.tax_code"          =>  $companyModel->tax_code,
            "company.logo_url"          =>  $companyModel->logo_url
        ];
        $data['album'] = [
            "album.album_type"          =>  trans('messages.sample_album_type', [], $locale),
            "album.user_creator"        =>  trans('messages.sample_user_creator', [], $locale),
            "album.image_url"           =>  Example::IMAGE_URL
        ];
        if ($albumProperties->isNotEmpty()) {
            foreach ($albumProperties as $albumProperty) {
                $keyTitle = "album.information." . $albumProperty->id . ".title";
                $keyValue = "album.information." . $albumProperty->id . ".value";
                $sampleValue = "";
                if ($albumProperty->type == InputType::TEXT) {
                    $sampleValue = trans('messages.sample_album_information', [], $locale);
                } elseif ($albumProperty->type == InputType::DATE || $albumProperty->type == InputType::SHORT_DATE || $albumProperty->type == InputType::DATE_TIME) {
                    $sampleValue = now()->format(InputType::CONFIG[$albumProperty->type]['format'][$locale]);
                } elseif ($albumProperty->type == InputType::NUMBER) {
                    $sampleValue = mt_rand(0, 999999999);
                } elseif ($albumProperty->type == InputType::EMAIL) {
                    $sampleValue = Example::EMAIL;
                }
                $data['album']['information'][] = [
                    $keyTitle   =>  $albumProperty->title,
                    $keyValue   =>  $sampleValue
                ];
            }
        }

        $data['location'] = [
            "location.title"            =>  trans('messages.sample_location_title', [], $locale),
            "location.description"      =>  trans('messages.sample_location_description', [], $locale)
        ];
        if ($locationProperties->isNotEmpty()) {
            foreach ($locationProperties as $locationProperty) {
                $keyTitle = "location.information." . $locationProperty->id . ".title";
                $keyValue = "location.information." . $locationProperty->id . ".value";
                $sampleValue = "";
                if ($locationProperty->type == InputType::TEXT) {
                    $sampleValue = trans('messages.sample_location_information', [], $locale);
                } elseif ($locationProperty->type == InputType::DATE || $locationProperty->type == InputType::SHORT_DATE || $locationProperty->type == InputType::DATE_TIME) {
                    $sampleValue = now()->format(InputType::CONFIG[$locationProperty->type]['format'][$locale]);
                } elseif ($locationProperty->type == InputType::NUMBER) {
                    $sampleValue = mt_rand(0, 999999999);
                } elseif ($locationProperty->type == InputType::EMAIL) {
                    $sampleValue = Example::EMAIL;
                }
                $data['location']['information'][] = [
                    $keyTitle   =>  $locationProperty->title,
                    $keyValue   =>  $sampleValue
                ];
            }
        }

        $data['medias'] = [
            "medias.*.url"              =>  Example::IMAGE_URL,
            "medias.*.name"             =>  Example::IMAGE_NAME,
            "medias.*.created_time"     =>  Example::DATE_NONE,
            "medias.*.description"      =>  trans('messages.sample_media_description', [], $locale)
        ];
        $data['medias_after'] = [
            "medias_after.*.url"              =>  Example::IMAGE_URL,
            "medias_after.*.name"             =>  Example::IMAGE_NAME,
            "medias_after.*.created_time"     =>  Example::DATE_NONE,
            "medias_after.*.description"      =>  trans('messages.sample_media_description', [], $locale)
        ];
        if ($mediaProperties->isNotEmpty()) {
            foreach ($mediaProperties as $mediaProperty) {
                $keyTitle = "medias.*.information." . $mediaProperty->id . ".title";
                $keyValue = "medias.*.information." . $mediaProperty->id . ".value";
                $keyAfterTitle = "medias_after.*.information." . $mediaProperty->id . ".title";
                $keyAfterValue = "medias_after.*.information." . $mediaProperty->id . ".value";
                $sampleValue = "";
                if ($mediaProperty->type == InputType::TEXT) {
                    $sampleValue = trans('messages.sample_media_information', [], $locale);
                } elseif ($mediaProperty->type == InputType::DATE || $mediaProperty->type == InputType::SHORT_DATE || $mediaProperty->type == InputType::DATE_TIME) {
                    $sampleValue = now()->format(InputType::CONFIG[$mediaProperty->type]['format'][$locale]);
                } elseif ($mediaProperty->type == InputType::NUMBER) {
                    $sampleValue = mt_rand(0, 999999999);
                } elseif ($mediaProperty->type == InputType::EMAIL) {
                    $sampleValue = Example::EMAIL;
                }
                $data['medias']['information'][] = [
                    $keyTitle   =>  $mediaProperty->title,
                    $keyValue   =>  $sampleValue
                ];
                $data['medias_after']['information'][] = [
                    $keyAfterTitle   =>  $mediaProperty->title,
                    $keyAfterValue   =>  $sampleValue
                ];
            }
        }
        return $data;
    }

    public function generateMediaKeysConfig(string $content)
    {
        $currentUser = app('currentUser');
        $mediaPropertyIds = $currentUser->company->mediaProperties->pluck('id')->toArray();
        foreach ($mediaPropertyIds as $mediaPropertyId) {
            $content = $this->generateMediaKeyConfig($content, 'medias', 'information', $mediaPropertyId, 'title')['content'];
            $content = $this->generateMediaKeyConfig($content, 'medias_after', 'information', $mediaPropertyId, 'title')['content'];
        }
        foreach ($mediaPropertyIds as $mediaPropertyId) {
            $content = $this->generateMediaKeyConfig($content, 'medias', 'information', $mediaPropertyId, 'value')['content'];
            $content = $this->generateMediaKeyConfig($content, 'medias_after', 'information', $mediaPropertyId, 'value')['content'];
        }
        $images = $this->generateMediaKeyConfig($content, 'medias', 'url');
        $content = $images['content'];
        $content = $this->generateMediaKeyConfig($content, 'medias_after', 'url')['content'];
        $content = $this->generateMediaKeyConfig($content, 'medias', 'name')['content'];
        $content = $this->generateMediaKeyConfig($content, 'medias_after', 'name')['content'];
        $content = $this->generateMediaKeyConfig($content, 'medias', 'created_time')['content'];
        $content = $this->generateMediaKeyConfig($content, 'medias_after', 'created_time')['content'];
        $content = $this->generateMediaKeyConfig($content, 'medias', 'description')['content'];
        $content = $this->generateMediaKeyConfig($content, 'medias_after', 'description')['content'];
        return [
            'content'       => $content,
            'count_images'  =>  $images['number']
        ];
    }

    public function generateMediaKeyConfig(string $content, string $key1, string $key2, ?string $key3 = null, ?string $key4 = null)
    {
        $keyAfter = $key2;
        if (!empty($key3)) {
            $keyAfter = $keyAfter . '.' . $key3;
        }
        if (!empty($key4)) {
            $keyAfter = $keyAfter . '.' . $key4;
        }
        $arrayData = explode($key1 . '.*.' . $keyAfter, $content);
        $result = "";
        $index = 0;
        foreach($arrayData as $key => $value) {
            if($key != count($arrayData) - 1) {
                $result = $result . $value . $key1. '.' . $index . '.' . $keyAfter;
                if (preg_match('/\[\[ *$/i', $value) == false) {
                    $index = $index + 1;
                }
            } else {
                $result = $result . $value;
            }
        }
        return [
            'content'   =>  $result,
            'number'    =>  $index
        ];
    }
    public function generateContentPageViewAlbumPDFFormat(string $content, string $preFileName)
    {
        $contentData = $this->generateMediaKeysConfig($content);
        $content = $contentData['content'];
        foreach (AlbumPDFKeyConfig::LIST_KEY_REGEX as $regex) {
            $content = preg_replace($regex, '{{ $data["$0"] ?? null }}', $content);
        }
        $content = $this->removeRedundantCharacters($content);
        $contentPage = AlbumPDFKeyConfig::BEFORE_CONTENT . $content . AlbumPDFKeyConfig::AFTER_CONTENT;
        $contentPagePreview = AlbumPDFKeyConfig::BEFORE_PREVIEW_CONTENT . $content . AlbumPDFKeyConfig::AFTER_PREVIEW_CONTENT;
        Storage::disk('public')->put('views/' . $preFileName . '_content_page.blade.php', $contentPage);
        Storage::disk('public')->put('views/' . $preFileName . '_content_page_preview.blade.php', $contentPagePreview);

        return [
            'count_images'            =>  $contentData['count_images'],
            'content_path'            =>  $preFileName . '_content_page',
            'content_preview_path'    =>  $preFileName . '_content_page_preview'
        ];
    }

    public function generateCoverPageViewAlbumPDFFormat(string $cover, string $preFileName)
    {
        foreach (AlbumPDFKeyConfig::LIST_KEY_REGEX as $regex) {
            $cover = preg_replace($regex, '{{ $data["$0"] ?? null }}', $cover);
        }
        $cover = $this->removeRedundantCharacters($cover);
        $coverPageContent = AlbumPDFKeyConfig::BEFORE_CONTENT . $cover . AlbumPDFKeyConfig::AFTER_CONTENT;
        $coverPagePreviewContent = AlbumPDFKeyConfig::BEFORE_PREVIEW_CONTENT . $cover . AlbumPDFKeyConfig::AFTER_PREVIEW_CONTENT;

        Storage::disk('public')->put('views/' . $preFileName . '_cover_page.blade.php', $coverPageContent);
        Storage::disk('public')->put('views/' . $preFileName . '_cover_page_preview.blade.php', $coverPagePreviewContent);

        return [
            'cover_path'            =>  $preFileName . '_cover_page',
            'cover_preview_path'    =>  $preFileName . '_cover_page_preview'
        ];
    }

    public function generateLastPageViewAlbumPDFFormat(string $last, string $preFileName)
    {
        foreach (AlbumPDFKeyConfig::LIST_KEY_REGEX as $regex) {
            $last = preg_replace($regex, '{{ $data["$0"] ?? null }}', $last);
        }
        $last = $this->removeRedundantCharacters($last);
        $lastPageContent = AlbumPDFKeyConfig::BEFORE_CONTENT . $last . AlbumPDFKeyConfig::AFTER_CONTENT;
        $lastPagePreviewContent = AlbumPDFKeyConfig::BEFORE_PREVIEW_CONTENT . $last . AlbumPDFKeyConfig::AFTER_PREVIEW_CONTENT;

        Storage::disk('public')->put('views/' . $preFileName . '_last_page.blade.php', $lastPageContent);
        Storage::disk('public')->put('views/' . $preFileName . '_last_page_preview.blade.php', $lastPagePreviewContent);

        return [
            'last_path'            =>  $preFileName . '_last_page',
            'last_preview_path'    =>  $preFileName . '_last_page_preview'
        ];
    }

    public function removeRedundantCharacters(string $content)
    {
        $arrayContent = explode(' ?? null }} ]]', $content);
        $tmpContent = '';
        foreach ($arrayContent as $key => $value) {
            if($key != count($arrayContent) - 1) {
                $tmpContent = $tmpContent . $value . ') ? "" : "hidden" }}';
            } else {
                $tmpContent = $tmpContent . $value;
            }
        }
        $content = $tmpContent;
        $arrayContent = explode('[[ {{ ', $content);
        $tmpContent = '';
        foreach ($arrayContent as $key => $value) {
            if($key != count($arrayContent) - 1) {
                $tmpContent = $tmpContent . $value . '{{ !empty(';
            } else {
                $tmpContent = $tmpContent . $value;
            }
        }
        return $tmpContent;
    }

    public function getCurrentCompanyAlbumTypeById(int $albumTypeId)
    {
        $companyId = app('currentUser')->company_id;
        $albumTypeEntity = $this->_albumTypeRepository
            ->where('company_id','=',$companyId)->find($albumTypeId);
        if(!$albumTypeEntity){
            abort(404,"messages.album_type_id_not_invalid");
        }
        return $albumTypeEntity;
    }
}
