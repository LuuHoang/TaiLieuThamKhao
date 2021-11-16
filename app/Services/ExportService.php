<?php

namespace App\Services;

use App\Constants\App;
use App\Constants\Boolean;
use App\Constants\Disk;
use App\Constants\Example;
use App\Constants\ExportStyle;
use App\Constants\InputType;
use App\Constants\Media;
use App\Constants\ServerCommunication;
use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Exceptions\UnprocessableException;
use App\Jobs\ExportContentPagePDFProcess;
use App\Jobs\ExportCoverPagePDFProcess;
use App\Jobs\ExportingPDFProcess;
use App\Jobs\ExportPDFContentPageProcess;
use App\Jobs\ExportPDFCoverPageProcess;
use App\Jobs\ExportPDFLastPageProcess;
use App\Jobs\ExportPDFProcess;
use App\Models\AlbumLocationModel;
use App\Models\AlbumModel;
use App\Models\AlbumPDFFormatModel;
use App\Models\AlbumPDFModel;
use App\Models\CompanyModel;
use App\Models\MediaPropertyModel;
use App\Repositories\Repository;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExportService extends AbstractService
{
    protected $_albumRepository;
    protected $httpService;
    protected $_mediaPropertyRepository;
    protected $_albumPDFFormatRepository;
    protected $_albumPDFRepository;

    public function __construct(AlbumModel $albumModel, HTTPService $httpService, MediaPropertyModel $mediaPropertyModel, AlbumPDFFormatModel $albumPDFFormatModel, AlbumPDFModel $albumPDFModel)
    {
        $this->_albumRepository = new Repository($albumModel);
        $this->httpService = $httpService;
        $this->_mediaPropertyRepository = new Repository($mediaPropertyModel);
        $this->_albumPDFFormatRepository = new Repository($albumPDFFormatModel);
        $this->_albumPDFRepository = new Repository($albumPDFModel);
    }

    public function exportingAlbumPDF(int $albumId, int $style)
    {
        $userId = app('currentUser')->id;
        $albumEntity = $this->_albumRepository
            ->with([
                'user.company.mediaProperties',
                'albumType',
                'albumLocations.albumLocationMedias.mediaInformation.mediaProperty',
                'albumLocations.locationInformation.locationProperty',
                'albumInformations.albumProperty',
                'albumPDFs'
            ])
            ->find($albumId);
        if (!array_key_exists($style, ExportStyle::CONFIG)) {
            throw new UnprocessableException('messages.pdf_file_format_is_not_supported');
        }
        $albumPDF = $albumEntity->albumPDFs->where('style_id', $style)->first();
        if ($albumPDF != null && $this->_checkModifyAlbum($albumEntity, strtotime($albumPDF->created_at))) {
            if ($albumPDF->status == Boolean::FALSE) {
                throw new ForbiddenException('messages.creating_file_PDF');
            } else {
                return [
                    "url"   =>  Storage::disk(Disk::PDF)->url($albumPDF->file_name)
                ];
            }
        } else {
            $createTime = time();
            $fileName = $createTime . '_' . $albumEntity->id . '_' . $userId . '.pdf';
            $styleConfig["mapping"] = ExportStyle::MAPPING[$style];
            $styleConfig['config']  =   ExportStyle::CONFIG[$style];
            $totalProcess = 0;
            $dataExport = [];
            if ($styleConfig['config']['cover']) {
                $totalProcess += 1;
                $dataExport['cover_page_data'] = $this->_generateCoverPageData($albumEntity);
            }
            $dataContentPage = $this->_generateContentPageData($albumEntity, $styleConfig['config']);
            $totalProcess += $dataContentPage['total_page'];
            $dataExport['content_page_data'] = $dataContentPage['pages'];
            $albumEntity->albumPDFs()->where('style_id', '=', $style)->delete();
            $albumEntity->albumPDFs()->create(
                ['style_id' => $style, 'file_name' => $fileName, 'status' => 0]
            );
            Cache::put('pdf_'. $createTime . '_' . $userId . '_' . $albumEntity->id, $totalProcess, App::EXPIRE_TIME * 60);
            dispatch(new ExportingPDFProcess($style, $styleConfig, $dataExport, $userId, $albumEntity->id, $fileName, $dataContentPage['total_page'], $createTime));
            return [];
        }
    }

    private function _generateContentPageData(AlbumModel $albumEntity, Array $styleConfig)
    {
        $countPage = 0;
        $pageContent = [];
        if ($albumEntity->albumLocations != null) {
            $extendData = [];
            if (!empty($styleConfig['extend_data'])) {
                $extendDataConfigs = $styleConfig['extend_data'];
                if (!empty($extendDataConfigs['album'])) {
                    $extendDataAlbumConfigs = $extendDataConfigs['album'];
                    foreach ($extendDataAlbumConfigs as $key => $extendDataAlbumConfig) {
                        $extendData['album'][$key] = $albumEntity->albumInformations()
                            ->whereHas('albumProperty', function (Builder $query) use ($extendDataAlbumConfig) {
                                $query->where('title', 'like', "%" . $extendDataAlbumConfig . "%");
                            })->with(['albumProperty'])->first();
                    }
                }
            }
            foreach ($albumEntity->albumLocations as $location) {
                $images = $location->albumLocationMedias()
                    ->where('type', Media::TYPE_IMAGE)->get()->map->only(['id', 'path', 'url', 'description', 'created_time']);
                if ($images->count() == 0) {
                    $countPage += 1;
                    $pageContent[] = [
                        'location'      =>  $location->only('id', 'title', 'description'),
                        'images'        =>  [],
                        'extend_data'   =>  $extendData
                    ];
                } else {
                    $countPage = $countPage + ceil($images->count() / $styleConfig['limit']);
                    $pageImages =  $images->chunk($styleConfig['limit']);
                    foreach ($pageImages as $pageImage) {
                        $pageContent[] = [
                            'location'      =>  $location->only('id', 'title', 'description'),
                            'images'        =>  $pageImage->values()->toArray(),
                            'extend_data'   =>  $extendData
                        ];
                    }
                }
            }
        }
        return [
            'total_page'    =>  $countPage,
            'pages'         =>  $pageContent
        ];
    }

    private function _generateCoverPageData(AlbumModel $albumModel)
    {
        $information = [];
        $highlight = [];
        foreach ($albumModel->albumInformations as $albumInformation) {
            $information[] = ['title' => $albumInformation->albumProperty->title, 'value' => $albumInformation->value];
            if ($albumInformation->albumProperty->highlight == Boolean::TRUE) {
                $highlight = ['title' => $albumInformation->albumProperty->title, 'value' => $albumInformation->value];
            }
        }
        return [
            'company' =>  $albumModel->user->company->only('company_name', 'company_code', 'logo_url', 'address'),
            'user'  =>  $albumModel->user->only('staff_code', 'full_name', 'email', 'avatar_url', 'address'),
            'album' =>  [
                'type'  =>  $albumModel->albumType->title,
                'image_url' =>  $albumModel->image_url,
                'information'   =>  $information,
                'highlight' => $highlight
            ]
        ];
    }

    public function exportAlbumPDF(int $styleId, Array $style, Array $data, int $userId, int $albumId, String $fileName, int $totalPage, int $createTime)
    {
        if ($style['config']['cover'] == Boolean::TRUE) {
            dispatch(new ExportCoverPagePDFProcess($styleId, $style, $data['cover_page_data'], $userId, $albumId, $fileName, $totalPage, $createTime));
        }
        foreach ($data['content_page_data'] as $key => $contentPage) {
            dispatch(new ExportContentPagePDFProcess($styleId, $style, $contentPage, $userId, $albumId, $fileName, $key + 1, $totalPage, $createTime));
        }
    }

    public function exportAlbumPDFByPage(Array $format, Array $data, int $userId, int $albumId, String $fileName, int $totalPage, int $createTime)
    {
        dispatch(new ExportPDFCoverPageProcess($format, $data['cover_page_data'], $userId, $albumId, $fileName, $totalPage, $createTime));
        foreach ($data['content_page_data'] as $key => $contentPage) {
            dispatch(new ExportPDFContentPageProcess($format, $contentPage, $userId, $albumId, $fileName, $key + 1, $totalPage, $createTime));
        }
        if ($format['last_path']) {
            dispatch(new ExportPDFLastPageProcess($format, $data['last_page_data'], $userId, $albumId, $fileName, $totalPage, $createTime));
        }
    }

    public function exportCoverPagePDF(int $styleId, Array $style, Array $data, int $userId, int $albumId, String $fileName, int $totalPage, int $createTime)
    {
        $countProcess = Cache::get('pdf_'. $createTime . '_' . $userId . '_' . $albumId);
        $pdf = app(\Barryvdh\Snappy\PdfWrapper::class);
        $pdf = $pdf->loadView('exports.' . $style['mapping']['cover_page'], ['data' => $data]);
        if (!empty($style['config']['orientation'])) {
            $pdf = $pdf->setOrientation($style['config']['orientation']);
        }
        if (!empty($style['config']['paper_size'])) {
            $pdf = $pdf->setPaper($style['config']['paper_size']);
        }
        $pdf->save(Storage::disk(Disk::PDF)->path($fileName));
        $this->_generatePDF($styleId, $style, $userId, $albumId, $fileName, $totalPage, $createTime);
    }

    public function exportPDFCoverPage(Array $format, Array $data, int $userId, int $albumId, String $fileName, int $totalPage, int $createTime)
    {
        $countProcess = Cache::get('pdf_'. $createTime . '_' . $userId . '_' . $albumId);
        $pdf = app(\Barryvdh\Snappy\PdfWrapper::class);
        $pdf = $pdf->loadView($format['cover_path'], ['data' => $data])->setOrientation('portrait')->setPaper('a4');
        $pdf->save(Storage::disk(Disk::PDF)->path($fileName));
        $this->_generatePDFFile($format, $userId, $albumId, $fileName, $totalPage, $createTime);
    }

    public function exportPDFLastPage(Array $format, Array $data, int $userId, int $albumId, String $fileName, int $totalPage, int $createTime)
    {
        $countProcess = Cache::get('pdf_'. $createTime . '_' . $userId . '_' . $albumId);
        $pdf = app(\Barryvdh\Snappy\PdfWrapper::class);
        $pdf = $pdf->loadView($format['last_path'], ['data' => $data])->setOrientation('portrait')->setPaper('a4');
        $pdf->save(Storage::disk(Disk::PDF)->path(Str::beforeLast($fileName, '.pdf') . '_last.pdf'));
        $this->_generatePDFFile($format, $userId, $albumId, $fileName, $totalPage, $createTime);
    }

    public function exportContentPagePDF(int $styleId, Array $style, Array $data, int $userId, int $albumId, String $fileName, int $currentPage, int $totalPage, int $createTime)
    {
        $countProcess = Cache::get('pdf_'. $createTime . '_' . $userId . '_' . $albumId);
        $pdf = app(\Barryvdh\Snappy\PdfWrapper::class);
        $pdf = $pdf->loadView('exports.' . $style['mapping']['content_page'], ['data' => $data]);
        if (!empty($style['config']['orientation'])) {
            $pdf = $pdf->setOrientation($style['config']['orientation']);
        }
        if (!empty($style['config']['paper_size'])) {
            $pdf = $pdf->setPaper($style['config']['paper_size']);
        }
        if (!empty($style['config']['page_number'])) {
            $content_page_number = $style['config']['page_number'][1];
            $content_page_number = str_replace(['[current_page]', '[total_page]'], [$currentPage, $totalPage], $content_page_number);
            $pdf = $pdf->setOption($style['config']['page_number'][0], $content_page_number);
        }
        $pdf->save(Storage::disk(Disk::PDF)->path($this->_generateFileNamePagePDF($fileName, $currentPage, $totalPage)));
        $this->_generatePDF($styleId, $style, $userId, $albumId, $fileName, $totalPage, $createTime);
    }

    public function exportPDFContentPage(Array $format, Array $data, int $userId, int $albumId, String $fileName, int $currentPage, int $totalPage, int $createTime)
    {
        $countProcess = Cache::get('pdf_'. $createTime . '_' . $userId . '_' . $albumId);
        $pdf = app(\Barryvdh\Snappy\PdfWrapper::class);
        $pdf = $pdf->loadView($format['content_path'], ['data' => $data])->setOrientation('portrait')->setPaper('a4')->setOption('footer-center', 'Page ' . $currentPage . '/' . $totalPage);
        $pdf->save(Storage::disk(Disk::PDF)->path($this->_generateFileNamePagePDF($fileName, $currentPage, $totalPage)));
        $this->_generatePDFFile($format, $userId, $albumId, $fileName, $totalPage, $createTime);
    }

    private function _generateFileNamePagePDF(string $fileName, int $currentPage, int $totalPage)
    {
        $name = Str::beforeLast($fileName, '.pdf');
        return $name . '(' . $currentPage . '.' . $totalPage . ').pdf';
    }

    private function _mergeFilePagePDF(string $fileName, Array $style, int $totalPage)
    {
        $merger = app(\GrofGraf\LaravelPDFMerger\PDFMerger::class);
        if (!empty($style['config']['cover'])) {
            $merger->addPathToPDF(Storage::disk(Disk::PDF)->path($fileName), 'all', $style['config']['merge_orientation']);
        }
        for ($i = 0; $i < $totalPage; $i++) {
            $merger->addPathToPDF(
                Storage::disk(Disk::PDF)->path($this->_generateFileNamePagePDF($fileName, $i + 1, $totalPage)),
                'all',
                $style['config']['merge_orientation']
            );
        }
        $merger->merge();
        $this->_deleteFileAfterMergePDF($fileName, $style, $totalPage);
        $merger->setFileName($fileName);
        $merger->save(Storage::disk(Disk::PDF)->path($fileName));
    }

    private function _mergePagePDF(string $fileName, Array $format, int $totalPage)
    {
        $merger = app(\GrofGraf\LaravelPDFMerger\PDFMerger::class);
        $merger->addPathToPDF(Storage::disk(Disk::PDF)->path($fileName), 'all', 'P');
        for ($i = 0; $i < $totalPage; $i++) {
            $merger->addPathToPDF(Storage::disk(Disk::PDF)->path($this->_generateFileNamePagePDF($fileName, $i + 1, $totalPage)), 'all', 'P');
        }
        if ($format['last_path']) {
            $merger->addPathToPDF(Storage::disk(Disk::PDF)->path(Str::beforeLast($fileName, '.pdf') . '_last.pdf'), 'all', 'P');
        }
        $merger->merge();
        $this->_deletePageFileAfterMergePDF($fileName, $format, $totalPage);
        $merger->setFileName($fileName);
        $merger->save(Storage::disk(Disk::PDF)->path($fileName));
    }

    private function _deleteFileAfterMergePDF(string $fileName, Array $style, int $totalPage)
    {
        if ($style['config']['cover']) {
            Storage::disk(Disk::PDF)->delete($fileName);
        }
        for ($i = 0; $i < $totalPage; $i++) {
            Storage::disk(Disk::PDF)->delete($this->_generateFileNamePagePDF($fileName, $i + 1, $totalPage));
        }
    }

    private function _deletePageFileAfterMergePDF(string $fileName, Array $format, int $totalPage)
    {
        Storage::disk(Disk::PDF)->delete($fileName);

        for ($i = 0; $i < $totalPage; $i++) {
            Storage::disk(Disk::PDF)->delete($this->_generateFileNamePagePDF($fileName, $i + 1, $totalPage));
        }

        if ($format['last_path']) {
            Storage::disk(Disk::PDF)->delete(Str::beforeLast($fileName, '.pdf') . '_last.pdf');
        }
    }

    private function _checkModifyAlbum(AlbumModel $albumModel, int $time) {
        if (strtotime($albumModel->updated_at) > $time || strtotime($albumModel->albumType->updated_at) > $time) {
            return false;
        }
        foreach ($albumModel->albumLocations as $albumLocation) {
            if (strtotime($albumLocation->updated_at) > $time) {
                return false;
            }
            foreach ($albumLocation->locationInformation as $locationInformation) {
                if (strtotime($locationInformation->updated_at) > $time) {
                    return false;
                }
                if (strtotime($locationInformation->locationProperty->updated_at) > $time) {
                    return false;
                }
            }
            foreach ($albumLocation->albumLocationMedias as $media) {
                if (strtotime($media->updated_at) > $time) {
                    return false;
                }
                foreach ($media->mediaInformation as $mediaInformation) {
                    if (strtotime($mediaInformation->updated_at) > $time) {
                        return false;
                    }
                    if (strtotime($mediaInformation->mediaProperty->updated_at) > $time) {
                        return false;
                    }
                }
            }
        }
        foreach ($albumModel->albumInformations as $albumInformation) {
            if (strtotime($albumInformation->updated_at) > $time) {
                return false;
            }
            if (strtotime($albumInformation->albumProperty->updated_at) > $time) {
                return false;
            }
        }
        return true;
    }

    private function _generatePDF(int $styleId, Array $style, int $userId, int $albumId, String $fileName, int $totalPage, int $createTime)
    {
        $lock = Cache::lock('pdf_'. $createTime . '_' . $userId . '_' . $albumId. '_lock', 10);
        try {
            $lock->block(5);
            if (Cache::has('pdf_'. $createTime . '_' . $userId . '_' . $albumId)) {
                $countProcess = Cache::get('pdf_'. $createTime . '_' . $userId . '_' . $albumId);
                if ($countProcess == 1) {
                    $this->_mergeFilePagePDF($fileName, $style, $totalPage);
                    $albumEntity = $this->_albumRepository
                        ->with([
                            'albumType',
                            'albumLocations.albumLocationMedias',
                            'albumInformations.albumProperty',
                            'albumPDFs'
                        ])
                        ->find($albumId);
                    if ($this->_checkModifyAlbum($albumEntity, $createTime)) {
                        $albumEntity->albumPDFs->where('style_id', '=', $styleId)->first()->update(['file_name'=>$fileName, 'status'=>Boolean::TRUE]);
                    }
                    Cache::forget('pdf_'. $createTime . '_' . $userId . '_' . $albumId);
                    $dataRequest = [
                        "user"  =>  ["id" => $userId],
                        "data"  =>  ["id" => $albumId, "url" => Storage::disk(Disk::PDF)->url($fileName)]
                    ];
                    $this->httpService->sendRequest('POST', env('SOCKET_URL') . ServerCommunication::GENERATE_ALBUM_PDF_EVENT, $dataRequest);
                } else {
                    Cache::put('pdf_'. $createTime . '_' . $userId . '_' . $albumId, $countProcess - 1, App::EXPIRE_TIME * 60);
                }
            }
        } catch (LockTimeoutException $exception) {
            throw $exception;
        } finally {
            optional($lock)->release();
        }
    }

    private function _generatePDFFile(Array $format, int $userId, int $albumId, String $fileName, int $totalPage, int $createTime)
    {
        $lock = Cache::lock('pdf_'. $createTime . '_' . $userId . '_' . $albumId. '_lock', 10);
        try {
            $lock->block(5);
            if (Cache::has('pdf_'. $createTime . '_' . $userId . '_' . $albumId)) {
                $countProcess = Cache::get('pdf_'. $createTime . '_' . $userId . '_' . $albumId);
                if ($countProcess == 1) {
                    $this->_mergePagePDF($fileName, $format, $totalPage);
                    $albumEntity = $this->_albumRepository
                        ->with([
                            'albumType',
                            'albumLocations.albumLocationMedias',
                            'albumInformations.albumProperty',
                            'albumPDFs'
                        ])
                        ->find($albumId);
                    if ($this->_checkModifyAlbum($albumEntity, $createTime)) {
                        $albumEntity->albumPDFs->where('style_id', '=', $format['id'])->first()->update(['file_name'=>$fileName, 'status'=>Boolean::TRUE]);
                    }
                    Cache::forget('pdf_'. $createTime . '_' . $userId . '_' . $albumId);
                    $dataRequest = [
                        "user"  =>  ["id" => $userId],
                        "data"  =>  ["id" => $albumId, "url" => Storage::disk(Disk::PDF)->url($fileName)]
                    ];
                    $this->httpService->sendRequest('POST', env('SOCKET_URL') . ServerCommunication::GENERATE_ALBUM_PDF_EVENT, $dataRequest);
                } else {
                    Cache::put('pdf_'. $createTime . '_' . $userId . '_' . $albumId, $countProcess - 1, App::EXPIRE_TIME * 60);
                }
            }
        } catch (LockTimeoutException $exception) {
            throw $exception;
        } finally {
            optional($lock)->release();
        }
    }

    public function exportAlbumPDFFile(int $albumId, int $formatId)
    {
        $userId = app('currentUser')->id;
        $albumEntity = $this->_albumRepository
            ->with([
                'user.company.mediaProperties',
                'albumType',
                'albumLocations.albumLocationMedias.mediaInformation.mediaProperty',
                'albumLocations.locationInformation.locationProperty',
                'albumInformations.albumProperty',
                'albumPDFs'
            ])
            ->find($albumId);
        if ($albumEntity == null) {
            throw new NotFoundException('messages.album_does_not_exist');
        }
        $formatEntity = $this->_albumPDFFormatRepository->find($formatId);
        if ($formatEntity == null) {
            throw new NotFoundException('messages.album_pdf_format_does_not_exist');
        }
        $albumPDF = $albumEntity->albumPDFs->where('style_id', $formatId)->first();
        if ($albumPDF != null && $this->_checkModifyAlbum($albumEntity, strtotime($albumPDF->created_at))) {
            if ($albumPDF->status == Boolean::FALSE) {
                throw new ForbiddenException('messages.creating_file_PDF');
            } else {
                return [
                    "url"   =>  Storage::disk(Disk::PDF)->url($albumPDF->file_name)
                ];
            }
        } else {
            $createTime = time();
            $fileName = $createTime . '_' . $albumEntity->id . '_' . $userId . '.pdf';
            $dataCoverPage = $this->_generateCoverPageDataView($albumEntity);
            $dataContentPage = $this->_generateContentPageDataView($albumEntity, $formatEntity->number_images);
            $dataLastPage = $dataCoverPage;
            if ($formatEntity->last_path) {
                $totalProcess = $dataContentPage['total_page'] + 2;
            } else {
                $totalProcess = $dataContentPage['total_page'] + 1;
            }
            $dataExport = [
                'cover_page_data'   =>  $dataCoverPage,
                'content_page_data' =>  $dataContentPage['pages'],
                'last_page_data'   =>  $dataLastPage
            ];
            $albumEntity->albumPDFs()->where('style_id', '=', $formatId)->delete();
            $albumEntity->albumPDFs()->create(
                ['style_id' => $formatId, 'file_name' => $fileName, 'status' => 0]
            );
            Cache::put('pdf_'. $createTime . '_' . $userId . '_' . $albumEntity->id, $totalProcess, App::EXPIRE_TIME * 60);
            dispatch(new ExportPDFProcess($formatEntity->only(['id', 'cover_path', 'content_path', 'last_path']), $dataExport, $userId, $albumEntity->id, $fileName, $dataContentPage['total_page'], $createTime));
            return [];
        }
    }

    private function _generateContentPageDataView(AlbumModel $albumEntity, int $limit)
    {
        $countPage = 0;
        $pageContent = [];
        $companyData = $this->_generateDataCompany($albumEntity->user->company);
        $albumData = $this->_generateDataAlbum($albumEntity);
        if ($albumEntity->albumLocations != null) {
            foreach ($albumEntity->albumLocations as $location) {
                $locationData = $this->_generateDataLocation($location);
                $images = $location->albumLocationMedias()->where('type', Media::TYPE_IMAGE)->get();
                if ($images->count() == 0 || $limit <= 0) {
                    $countPage += 1;
                    $pageContent[] = [
                        'company'       =>  $companyData,
                        'album'         =>  $albumData,
                        'location'      =>  $locationData,
                        'medias'        =>  [],
                        'medias_after'  =>  []
                    ];
                } else {
                    $countPage = $countPage + ceil($images->count() / $limit);
                    $pageImages =  $images->chunk($limit);
                    foreach ($pageImages as $pageImage) {
                        $pageContent[] = [
                            'company'       =>  $companyData,
                            'album'         =>  $albumData,
                            'location'      =>  $locationData,
                            'medias'        =>  $this->_generateDataMedias($pageImage),
                            'medias_after'  =>  $this->_generateDataMediasAfter($pageImage)
                        ];
                    }
                }
            }
        }
        return [
            'total_page'    =>  $countPage,
            'pages'         =>  $pageContent
        ];
    }

    private function _generateCoverPageDataView(AlbumModel $albumModel)
    {
        return [
            'company'   =>  $this->_generateDataCompany($albumModel->user->company),
            'album'     =>  $this->_generateDataAlbum($albumModel),
        ];
    }

    private function _generateDataCompany(CompanyModel $companyEntity)
    {
        return [
            'company_name'      =>  $companyEntity->company_name,
            'company_code'      =>  $companyEntity->company_code,
            'address'           =>  $companyEntity->address,
            'representative'    =>  $companyEntity->representative,
            'tax_code'          =>  $companyEntity->tax_code,
            'logo_url'          =>  $companyEntity->logo_url,
        ];
    }

    private function _generateDataAlbum(AlbumModel $albumEntity)
    {
        $currentUser = app('currentUser');
        $albumPropertyEntities = $currentUser->company->albumProperties;
        $albumData = [
            'album_type'    =>  $albumEntity->albumType->title,
            'image_url'     =>  $albumEntity->image_url,
            'user_creator'  =>  $albumEntity->user->full_name
        ];
        if ($albumPropertyEntities->isNotEmpty()) {
            $informationData = [];
            foreach ($albumPropertyEntities as $albumPropertyEntity) {
                $information = $albumEntity->albumInformations->where('album_property_id', $albumPropertyEntity->id)->first();
                $informationValue = $information->value ?? "";
                $informationData[$albumPropertyEntity->id] = [
                    'title'     =>  $albumPropertyEntity->title,
                    'value'     =>  $this->_convertInformationValue($informationValue, $albumPropertyEntity->type)
                ];
            }
            $albumData['information'] = $informationData;
        }
        return $albumData;
    }
    private function _generateDataLocation(AlbumLocationModel $locationEntity)
    {
        $currentUser = app('currentUser');
        $locationPropertyEntities = $currentUser->company->locationProperties;
        $locationData = [
            'title'         =>  $locationEntity->title,
            'description'   =>  $locationEntity->description
        ];
        if ($locationPropertyEntities->isNotEmpty()) {
            $informationData = [];
            foreach ($locationPropertyEntities as $locationPropertyEntity) {
                $information = $locationEntity->locationInformation->where('id', $locationPropertyEntity->id)->first();
                $informationValue = $information->value ?? "";
                $informationData[$locationPropertyEntity->id] = [
                    'title'     =>  $locationPropertyEntity->title,
                    'value'     =>  $this->_convertInformationValue($informationValue, $locationPropertyEntity->type)
                ];
            }
            $locationData['information'] = $informationData;
        }
        return $locationData;
    }

    private function _generateDataMedias(Collection $mediaEntities)
    {
        $currentUser = app('currentUser');
        $mediaPropertyEntities = $currentUser->company->mediaProperties;
        $dataMedias = [];
        foreach ($mediaEntities as $mediaEntity)
        {
            $dataMedia = [
                'url'           =>  $mediaEntity->url,
                'name'          =>  Str::afterLast($mediaEntity->path, '/'),
                'created_time'  =>  $mediaEntity->created_time,
                'description'   =>  $mediaEntity->description
            ];
            if ($mediaPropertyEntities->isNotEmpty()) {
                $informationData = [];
                foreach ($mediaPropertyEntities as $mediaPropertyEntity) {
                    $information = $mediaEntity->mediaInformation->where('media_property_id', $mediaPropertyEntity->id)->first();
                    $informationValue = $information->value ?? "";
                    $informationData[$mediaPropertyEntity->id] = [
                        'title'     =>  $mediaPropertyEntity->title,
                        'value'     =>  $this->_convertInformationValue($informationValue, $mediaPropertyEntity->type)
                    ];
                }
                $dataMedia['information'] = $informationData;
            }
            $dataMedias[] = $dataMedia;
        }
        return $dataMedias;
    }

    private function _generateDataMediasAfter(Collection $mediaEntities)
    {
        $currentUser = app('currentUser');
        $mediaPropertyEntities = $currentUser->company->mediaProperties;
        $dataMedias = [];
        foreach ($mediaEntities as $mediaEntity)
        {
            $dataMedia = [
                'url'           =>  $mediaEntity->image_after_url,
                'name'          =>  $mediaEntity->image_after_path,
                'created_time'  =>  $mediaEntity->created_time,
                'description'   =>  $mediaEntity->description
            ];
            if ($mediaPropertyEntities->isNotEmpty()) {
                $informationData = [];
                foreach ($mediaPropertyEntities as $mediaPropertyEntity) {
                    $information = $mediaEntity->mediaInformation->where('media_property_id', $mediaPropertyEntity->id)->first();
                    $informationValue = $information->value ?? "";
                    $informationData[$mediaPropertyEntity->id] = [
                        'title'     =>  $mediaPropertyEntity->title,
                        'value'     =>  $this->_convertInformationValue($informationValue, $mediaPropertyEntity->type)
                    ];
                }
                $dataMedia['information'] = $informationData;
            }
            $dataMedias[] = $dataMedia;
        }
        return $dataMedias;
    }

    private function _convertInformationValue($content, int $inputType)
    {
        $currentUser = app('currentUser');
        $contentConverted = !empty($content) ? $content : "";
        if ($inputType == InputType::DATE || $inputType == InputType::SHORT_DATE || $inputType == InputType::DATE_TIME) {
            if (!empty($content)) {
                $contentConverted = date(InputType::CONFIG[$inputType]['format'][$currentUser->userSetting->language], $content);
            } else {
                $contentConverted = Example::DATE_NONE;
            }
        }
        return $contentConverted;
    }

    public function previewAlbumPDF(int $albumId, int $formatId)
    {
        $albumEntity = $this->_albumRepository
            ->with([
                'user.company.mediaProperties',
                'albumType',
                'albumLocations.albumLocationMedias.mediaInformation.mediaProperty',
                'albumLocations.locationInformation.locationProperty',
                'albumInformations.albumProperty',
                'albumPDFs'
            ])
            ->find($albumId);
        if ($albumEntity == null) {
            throw new NotFoundException('messages.album_does_not_exist');
        }
        $formatEntity = $this->_albumPDFFormatRepository->find($formatId);
        if ($formatEntity == null) {
            throw new NotFoundException('messages.album_pdf_format_does_not_exist');
        }
        $dataCoverPage = $this->_generateCoverPageDataView($albumEntity);
        $dataContentPage = $this->_generateContentPageDataView($albumEntity, $formatEntity->number_images);
        $dataLastPage = $dataCoverPage;
        $response = [];
        $response['cover_page'] = view($formatEntity->preview_cover_path, ['data' => $dataCoverPage])->render();
        foreach ($dataContentPage['pages'] as $page) {
            $response['content_pages'][] = view($formatEntity->preview_content_path, ['data' => $page])->render();
        }
        if ($formatEntity->preview_last_path) {
            $response['last_page'] = view($formatEntity->preview_last_path, ['data' => $dataLastPage])->render();
        }
        return $response;
    }

    public function removeAlbumPDFFailed(int $albumId, int $styleId, string $fileName)
    {
        $this->_albumPDFRepository->delete([
            ['album_id', '=', $albumId],
            ['style_id', '=', $styleId],
            ['file_name', '=', $fileName]
        ]);
    }
}
