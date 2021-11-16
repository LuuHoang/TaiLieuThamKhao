<?php

namespace App\Services\WebService;

use App\Constants\Disk;
use App\Constants\InputType;
use App\Constants\Media;
use App\Exceptions\NotFoundException;
use App\Exceptions\SystemException;
use App\Exceptions\UnprocessableException;
use App\Models\GuidelineInformationMediaModel;
use App\Models\GuidelineInformationModel;
use App\Models\GuidelineModel;
use App\Repositories\Repository;
use App\Services\UploadMediaService;
use App\Services\ValidationService;
use Mockery\Exception;

class GuidelineService extends \App\Services\GuidelineService
{
    protected $_validationService;
    protected $_uploadMediaService;
    protected $_guidelineInformationRepository;
    protected $_guidelineInformationMediaRepository;

    public function __construct(
        GuidelineModel $guidelineModel,
        ValidationService $validationService,
        UploadMediaService $uploadMediaService,
        GuidelineInformationModel $guidelineInformationModel,
        GuidelineInformationMediaModel $guidelineInformationMediaModel
    )
    {
        parent::__construct($guidelineModel);
        $this->_validationService = $validationService;
        $this->_uploadMediaService = $uploadMediaService;
        $this->_guidelineInformationRepository = new Repository($guidelineInformationModel);
        $this->_guidelineInformationMediaRepository = new Repository($guidelineInformationMediaModel);
    }

    public function createGuideline(Array $params)
    {
        $currentUser = app('currentUser');
        $guidelineInformationData = $params['information'] ?? [];
        if (!empty($guidelineInformationData)) {
            foreach ($guidelineInformationData as $guidelineInformationDatum) {
                $this->_validationService->checkValidateInputType($guidelineInformationDatum['title'], $guidelineInformationDatum['content'], $guidelineInformationDatum['type'], $currentUser->userSetting->language);
            }
        }
        try {
            $this->beginTransaction();
            $companyEntity = $currentUser->company;
            $guidelineData = [
                'title' =>  $params['title'],
                'content'   =>  $params['content']
            ];
            $guidelineEntity = $companyEntity->guidelines()->create($guidelineData);
            if (!empty($guidelineInformationData)) {
                foreach ($guidelineInformationData as $guidelineInformation) {
                    $this->_insertGuidelineInformation($guidelineEntity, $guidelineInformation);
                }
            }
            $this->commitTransaction();
            return $guidelineEntity->fresh(['guidelineInformation.guidelineInformationMedias']);
        } catch (Exception $exception) {
            $this->rollbackTransaction();
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }
    private function _generateFileAttachGuideline(Array $files, int $inputType)
    {
        $mediaData = [];
        foreach ($files as $file) {
            if ($inputType == InputType::IMAGES) {
                $fileData = $this->_uploadMediaService->uploadImage($file, Disk::IMAGE);
                $mediaData[] = [
                    'path'  =>  $fileData,
                    'type'  =>  Media::TYPE_IMAGE
                ];
            } elseif ($inputType == InputType::PDFS) {
                $fileData = $this->_uploadMediaService->uploadImage($file, Disk::PDF);
                $mediaData[] = [
                    'path'  =>  $fileData,
                    'type'  =>  Media::TYPE_PDF
                ];
            } elseif ($inputType == InputType::VIDEOS) {
                $fileData = $this->_uploadMediaService->UploadVideo($file);
                $mediaData[] = [
                    'path'              =>  $fileData['file_name'],
                    'thumbnail_path'    =>  $fileData['file_thumb_name'],
                    'type'              =>  Media::TYPE_VIDEO
                ];
            }
        }
        return $mediaData;
    }

    public function updateGuideline(int $guidelineId, Array $bodyData)
    {
        $currentUser = app('currentUser');
        $guidelineEntity = $currentUser->company
            ->guidelines()
            ->find($guidelineId);
        if ($guidelineEntity == null) {
            throw new NotFoundException('messages.guideline_does_not_exist');
        }
        $guidelineInformationData = $bodyData['information'] ?? [];
        $guidelineInformationDataUpdate = [];
        $guidelineInformationDataInsert = [];
        $guidelineInformationUpdateTarget = [];
        if (!empty($guidelineInformationData)) {
            $this->_generateGuidelineInformationDataUpdate($guidelineInformationData, $guidelineInformationDataUpdate, $guidelineInformationDataInsert);
            if (!empty($guidelineInformationDataInsert)) {
                foreach ($guidelineInformationDataInsert as $item) {
                    $this->_validationService->checkValidateInputType($item['title'], $item['content'], $item['type'], $currentUser->userSetting->language);
                }
            }
            if (!empty($guidelineInformationDataUpdate)) {
                foreach ($guidelineInformationDataUpdate as $key => $item) {
                    $guidelineInformationEntity = $guidelineEntity->guidelineInformation()->find($key);
                    $this->_checkValidateUpdateGuidelineInformation($guidelineInformationEntity, $item);
                    $guidelineInformationUpdateTarget[] = [
                        "guidelineInformation"  =>  $guidelineInformationEntity,
                        "data"                  =>  $item
                    ];
                }
            }
        }

        try {
            $guidelineDataUpdate = [];
            if (!empty($bodyData['title'])) {
                $guidelineDataUpdate['title'] = $bodyData['title'];
            }
            if (!empty($bodyData['content'])) {
                $guidelineDataUpdate['content'] = $bodyData['content'];
            }
            if (!empty($guidelineDataUpdate)) {
                $guidelineEntity->update($guidelineDataUpdate);
            }
            if (!empty($guidelineInformationDataInsert)) {
                foreach ($guidelineInformationDataInsert as $guidelineInformation) {
                    $this->_insertGuidelineInformation($guidelineEntity, $guidelineInformation);
                }
            }
            if (!empty($guidelineInformationUpdateTarget)) {
                foreach ($guidelineInformationUpdateTarget as $dataUpdate) {
                    $this->_updateGuidelineInformation($dataUpdate['guidelineInformation'], $dataUpdate['data']);
                }
            }
            $this->commitTransaction();
            return $guidelineEntity->fresh(['guidelineInformation.guidelineInformationMedias']);
        } catch (Exception $exception) {
            $this->rollbackTransaction();
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }

    private function _generateGuidelineInformationDataUpdate($guidelineInformationData, &$guidelineInformationDataUpdate, &$guidelineInformationDataInsert)
    {
        foreach ($guidelineInformationData as $guidelineInformationDatum) {
            if (!empty($guidelineInformationDatum['id'])) {
                if (!empty($guidelineInformationDatum['title'])) {
                    $guidelineInformationDataUpdate[$guidelineInformationDatum['id']]['title'] = $guidelineInformationDatum['title'];
                }
                if (!empty($guidelineInformationDatum['content'])) {
                    $guidelineInformationDataUpdate[$guidelineInformationDatum['id']]['content'] = $guidelineInformationDatum['content'];
                }
            } else {
                $guidelineInformationDataInsert[] = [
                    'title'     =>  $guidelineInformationDatum['title'],
                    'type'      =>  $guidelineInformationDatum['type'],
                    'content'   =>  $guidelineInformationDatum['content']
                ];
            }
        }
    }

    private function _checkValidateUpdateGuidelineInformation($guidelineInformationEntity, $guidelineInformationData)
    {
        $currentUser = app('currentUser');
        if ($guidelineInformationEntity == null) {
            throw new NotFoundException('messages.guideline_information_exists');
        }
        if (!empty($guidelineInformationData['title'])) {
            $countTitle = $guidelineInformationEntity->guideline->guidelineInformation()
                ->where('title', '=', $guidelineInformationData['title'])
                ->where('id', '<>', $guidelineInformationEntity->id)->count();
            if ($countTitle > 0) {
                throw new UnprocessableException('messages.guideline_information_title_unique');
            }
        }
        if (!empty($guidelineInformationData['content'])) {
            $this->_validationService->checkValidateInputType($guidelineInformationEntity->title, $guidelineInformationData['content'], $guidelineInformationEntity->type, $currentUser->userSetting->language);
        }
    }

    private function _insertGuidelineInformation($guidelineEntity, $guidelineInformationData)
    {
        $files = [];
        $data = $guidelineInformationData;
        if ($guidelineInformationData['type'] == InputType::IMAGES || $guidelineInformationData['type'] == InputType::VIDEOS || $guidelineInformationData['type'] == InputType::PDFS) {
            $files = $guidelineInformationData['content'];
            $data = [
                'title' =>  $guidelineInformationData['title'],
                'type'  =>  $guidelineInformationData['type']
            ];
        }
        $guidelineInformationEntity = $guidelineEntity->guidelineInformation()->create($data);
        if (!empty($files)) {
            $mediaData = $this->_generateFileAttachGuideline($files, $guidelineInformationData['type']);
            if (!empty($mediaData)) {
                $guidelineInformationEntity->guidelineInformationMedias()->createMany($mediaData);
            }
        }
    }

    private function _updateGuidelineInformation($guidelineInformationEntity, $guidelineInformationData)
    {
        $files = [];
        $data = [];
        if (!empty($guidelineInformationData['title'])) {
            $data['title'] = $guidelineInformationData['title'];
        }
        if (!empty($guidelineInformationData['content'])) {
            if ($guidelineInformationEntity->type == InputType::IMAGES || $guidelineInformationEntity->type == InputType::VIDEOS || $guidelineInformationEntity->type == InputType::PDFS) {
                $files = $guidelineInformationData['content'];
            } else {
                $data['content'] = $guidelineInformationData['content'];
            }
        }
        $guidelineInformationEntity->update($data);
        if (!empty($files)) {
            $mediaData = $this->_generateFileAttachGuideline($files, $guidelineInformationEntity->type);
            if (!empty($mediaData)) {
                $guidelineInformationEntity->guidelineInformationMedias()->createMany($mediaData);
            }
        }
    }

    public function deleteGuideline(int $guidelineId)
    {
        $currentUser = app('currentUser');
        $guidelineEntity = $currentUser->company
            ->guidelines()
            ->find($guidelineId);
        if ($guidelineEntity == null) {
            throw new NotFoundException('messages.guideline_does_not_exist');
        }
        $guidelineEntity->delete();
    }

    public function deleteGuidelineInformation(int $guidelineId, int $informationId)
    {
        $currentUser = app('currentUser');
        $guidelineEntity = $currentUser->company
            ->guidelines()
            ->find($guidelineId);
        if ($guidelineEntity == null) {
            throw new NotFoundException('messages.guideline_does_not_exist');
        }
        $guidelineInformationEntity = $guidelineEntity->guidelineInformation()->find($informationId);
        if ($guidelineInformationEntity == null) {
            throw new NotFoundException('messages.guideline_information_exists');
        }
        $guidelineInformationEntity->delete();
    }

    public function deleteGuidelineInformationMedia(int $guidelineId, int $informationId, int $mediaId)
    {
        $currentUser = app('currentUser');
        $guidelineEntity = $currentUser->company
            ->guidelines()
            ->find($guidelineId);
        if ($guidelineEntity == null) {
            throw new NotFoundException('messages.guideline_does_not_exist');
        }
        $guidelineInformationEntity = $guidelineEntity->guidelineInformation()->find($informationId);
        if ($guidelineInformationEntity == null) {
            throw new NotFoundException('messages.guideline_information_exists');
        }
        $guidelineInformationMediaEntity = $guidelineInformationEntity->guidelineInformationMedias()->find($mediaId);
        if ($guidelineInformationMediaEntity == null) {
            throw new NotFoundException('messages.guideline_information_media_does_not_exist');
        }
        $guidelineInformationMediaEntity->delete();
    }
}
