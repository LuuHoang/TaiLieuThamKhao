<?php

namespace App\Services\AppService;

use App\Exceptions\SystemException;
use App\Models\LinkVersionModel;
use App\Repositories\Repository;
use App\Services\AbstractService;

class LinkVersionService extends AbstractService
{
    protected $_linkVersionRepository;

    public function __construct(LinkVersionModel $linkVersionModel)
    {
        $this->_linkVersionRepository = new Repository($linkVersionModel);
    }

    public function getLinkVersion()
    {
        return $this->_linkVersionRepository->orderBy('created_at', 'DESC')->first(['ios', 'android']);
    }
}
