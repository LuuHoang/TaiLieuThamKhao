<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Models\GuidelineModel;
use App\Repositories\Repository;

class GuidelineService extends AbstractService
{
    protected $_guidelineRepository;

    public function __construct(GuidelineModel $guidelineModel)
    {
        $this->_guidelineRepository = new Repository($guidelineModel);
    }

    public function getListGuidelines(Array $params, int $limit)
    {
        $currentUser = app('currentUser');
        $companyEntity = $currentUser->company;
        $search = $params['search'] ?? "";
        return $companyEntity->guidelines()
            ->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('content', 'like', '%' . $search . '%');
            })
            ->with(['guidelineInformation.guidelineInformationMedias'])
            ->paginate($limit);
    }

    public function getGuideline(int $guidelineId)
    {
        $currentUser = app('currentUser');
        $companyEntity = $currentUser->company;
        $guidelineEntity = $companyEntity->guidelines()
            ->with(['guidelineInformation.guidelineInformationMedias'])
            ->find($guidelineId);
        if ($guidelineEntity == null) {
            throw new NotFoundException('messages.guideline_does_not_exist');
        }
        return $guidelineEntity;
    }
}
