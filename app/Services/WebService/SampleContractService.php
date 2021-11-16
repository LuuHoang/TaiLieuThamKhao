<?php


namespace App\Services\WebService;


use App\Constants\AlbumPDFKeyConfig;
use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Exceptions\SystemException;
use App\Exceptions\UnprocessableException;
use App\Models\SampleContractModel;
use App\Repositories\Criteria\SearchSampleContractCriteria;
use App\Repositories\Repository;
use App\Services\AbstractService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class SampleContractService extends AbstractService
{
    protected $_sampleContractRepository;
    public function __construct(
        SampleContractModel $sampleContractModel
    )
    {
        $this->_sampleContractRepository = new Repository($sampleContractModel);

    }

    public function createSampleContract(Array $arrayData)
    {
        $admin = app('currentAdmin')->full_name;
        if(!isset($arrayData['category'])){
            $arrayData['category'] = null;
        }
        try {
            $sampleContract= $this->_sampleContractRepository->create([
                'name_sample_contract'  =>$arrayData['name_contract'],
                'description'           => $arrayData['description'],
                'tags'                  => $arrayData['tags'],
                'category'              => $arrayData['category'],
                'content'               => $arrayData['content'],
                'created_by'            => $admin
            ]);
            $dataPDF = $this->generateContentPageViewPDFFormat($arrayData['content']);
            Storage::disk('public')->put('pdfs/' . $sampleContract->id . '.blade.php', $dataPDF);
            return $sampleContract;
        } catch (\Exception $exception) {
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }

    public function updateSampleContract(Array $arrayData,int $sampleContractId)
    {
        $admin = app('currentAdmin')->full_name;
        $arrayData['updated_by']=$admin;
        if (empty($arrayData)) {
            throw new UnprocessableException('messages.nothing_to_update');
        }
        if(!isset($arrayData['category'])){
            $arrayData['category'] = null;
        }
        $arrayData['name_sample_contract'] = $arrayData['name_contract'];
        if(isset($arrayData['content'])){
            Storage::disk('public')->delete('pdfs/' . $sampleContractId . '.blade.php');
            Storage::disk('public')->put('pdfs/' . $sampleContractId . '.blade.php', $arrayData['content']);
            $sampleContract = $this->_sampleContractRepository->find($sampleContractId);
            $sampleContract->update($arrayData);
            return $sampleContract;
        }
        try {
            $sampleContract = $this->_sampleContractRepository->find($sampleContractId);
            $sampleContract->update($arrayData);
            return $sampleContract;
        } catch (\Exception $exception) {
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }
    public function  getSampleContract(int $sampleContractId)
    {
        $sampleContract = $this->_sampleContractRepository->find($sampleContractId,['id','name_sample_contract','description','tags','created_by','updated_by','category','content']);
        if (!$sampleContract) {
            throw new NotFoundException('messages.not_found_with_sampleContractId');
        }
        return $sampleContract;
    }
    public function deleteSampleContract(int $sampleContractId)
    {
        $sampleContract = $this->_sampleContractRepository->find($sampleContractId);
        if (!$sampleContract) {
            throw new NotFoundException('messages.not_found_with_sampleContractId');
        }
        if ($sampleContract->sampleContractProperties->count() > 0) {
            throw new ForbiddenException('messages.package_cannot_be_deleted');
        }
        $sampleContract->delete();
    }
    public function getListSampleContracts(int $limit, Array $paramQuery): LengthAwarePaginator
    {
        return $this->_sampleContractRepository
            ->pushCriteria(new SearchSampleContractCriteria($paramQuery))
            ->with(['contracts'])
            ->paginate($limit);
    }
    public function getListSampleContract()
    {
        return $this->_sampleContractRepository->all(['id','name_sample_contract','description','tags','category','content']);
    }
    public function generateContentPageViewPDFFormat(string $content)
    {
        foreach (AlbumPDFKeyConfig::LIST_KEY_REGEX as $regex) {
            $content = preg_replace($regex, '{{ $data["$0"] ?? null }}', $content);
        }
        return $content;
    }
}
