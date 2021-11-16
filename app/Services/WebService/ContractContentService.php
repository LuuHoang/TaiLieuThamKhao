<?php


namespace App\Services\WebService;


use App\Exceptions\NotFoundException;
use App\Exceptions\SystemException;
use App\Models\ContractContentModel;
use App\Models\SampleContractPropertyModel;
use App\Repositories\Repository;
use App\Services\AbstractService;

class ContractContentService extends AbstractService
{
    private $_contractContentRepository;
    public function __construct(
        ContractContentModel $contractContentModel
    )
    {
        $this->_contractContentRepository = new Repository($contractContentModel);
    }
    public function createContractContentService(int $contractId,int $sampleProperty,string $content)
    {
        try {
            $arrayData['contract_id'] = $contractId;
            $arrayData['sample_contract_property_id'] = $sampleProperty;
            $arrayData['content'] = $content;
            $this->_contractContentRepository->create($arrayData);
        }catch (\Exception $exception)
        {
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }
    public function updateContractContentService(array $contractContent,int $contractId)
    {
        $sampleContractPropertyIds = SampleContractPropertyModel::all()->pluck('id')->toArray();
        $contractContentId = ContractContentModel::where('contract_id','=',$contractId)->pluck('id')->toArray();
        foreach ( $contractContent as $item)
        {
            if (!in_array($item['sample_contract_property_id'], $sampleContractPropertyIds)) {
                throw new NotFoundException('messages.not_found_with_company_id');
            }
            if(isset($item['id']) && in_array($item['id'],$contractContentId)){
                $contractContent = $this->_contractContentRepository->find($item['id']);
                $contractContent->update($item);
                $key = array_search($item['id'], $contractContentId);
                unset($contractContentId[$key]);
                continue;
            }
                $arrayData['contract_id'] = $contractId;
                $arrayData['sample_contract_property_id'] = $item['sample_contract_property_id'];
                $arrayData['content'] = $item['content'];
                $this->_contractContentRepository->create($arrayData);
        }
        $this->_contractContentRepository->whereIn('id',$contractContentId)->delete();
    }
}
