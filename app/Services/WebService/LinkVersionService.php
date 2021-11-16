<?php


namespace App\Services\WebService;



use App\Exceptions\SystemException;

class LinkVersionService extends \App\Services\LinkVersionService
{
    public function CreateOrUpdateLinkVersion(Array $linkVersionData)
    {
        try {
            $this->beginTransaction();
            $oldLinkVersions = $this->_linkVersionRepository->all();
            foreach ($oldLinkVersions as $oldLinkVersion){
                $oldLinkVersion->delete();
            }
            $this->_linkVersionRepository->create($linkVersionData);
            $this->commitTransaction();
        } catch (\Exception $exception) {
            $this->rollbackTransaction();
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }
}
