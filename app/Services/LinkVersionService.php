<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Models\LinkVersionModel;
use App\Repositories\Repository;

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
