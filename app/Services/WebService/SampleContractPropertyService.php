<?php
namespace App\Services\WebService;

use App\Constants\StampType;
use App\Exceptions\NotFoundException;
use App\Exceptions\SystemException;
use App\Exceptions\UnprocessableException;
use App\Models\SampleContractPropertyModel;
use App\Repositories\Repository;
use App\Services\AbstractService;

class SampleContractPropertyService extends AbstractService
{
    protected $_sampleContractPropertyRepository;

    public function __construct(
        SampleContractPropertyModel $sampleContractPropertyModel

    )
    {
        $this->_sampleContractPropertyRepository = new Repository($sampleContractPropertyModel);
    }

    public function createSampleContractProperty(Array $arrayData,int $sampleContractId)
    {
        try {
            foreach ($arrayData as $value){
                $this->_sampleContractPropertyRepository->create([
                'title' =>$value['title'],
                'data_type' => $value['data_type'],
                'sample_contract_id' => $sampleContractId,
                ]);
            }
        } catch (\Exception $exception) {
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }
    public function updateOrCreateSampleContractProperties(Array $arrayData,int $sampleContractId)
    {
        $oldIds = SampleContractPropertyModel::all()->pluck('id')->toArray();
        try {
            foreach ($arrayData as $value){
                if(isset($value['id']) && in_array($value['id'], $oldIds)){
                    $sampleContractProperties = $this->_sampleContractPropertyRepository->find($value['id']);
                    $sampleContractProperties->update($arrayData);
                    $key = array_search($value['id'], $oldIds);
                    unset($oldIds[$key]);
                    continue;
                }
                $this->_sampleContractPropertyRepository->create(
                    [
                    'title' =>$value['title'],
                    'data_type' => $value['data_type'],
                    'sample_contract_id' => $sampleContractId,
                ]);
            }
            $this->_sampleContractPropertyRepository->whereIn('id',$oldIds)->delete();
        } catch (\Exception $exception) {
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }
    public function deleteSampleContractProperties(int $sampleContractId)
    {
        try {
            $sampleContractProperty = $this->_sampleContractPropertyRepository->where('sample_contract_id','=',$sampleContractId)->all();
            if (!$sampleContractProperty) {
                throw new NotFoundException('messages.not_found_with_sampleContractId');
            }
            foreach ($sampleContractProperty as $value){
                $value->delete();
            }
        } catch (\Exception $exception) {
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }

}
