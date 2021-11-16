<?php


namespace App\Services\WebService;



use App\Constants\Disk;
use App\Exceptions\NotFoundException;
use App\Exceptions\SystemException;
use App\Exceptions\UnprocessableException;
use App\Http\Requests\WebRequests\CreateCompanyConfigAlbumRequest;
use App\Models\CompanyStampConfigModel;
use App\Repositories\Repository;
use App\Services\AbstractService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use App\Constants\StampType;
use Illuminate\Support\Facades\Storage;
class CompanyConfigAlbumService extends AbstractService
{
    protected $_companyConfigAlbumRepository;
    public function __construct (
        CompanyStampConfigModel $companyStampConfigModel

    )
    {
        $this->_companyConfigAlbumRepository = new Repository($companyStampConfigModel);
    }
    public function updateCompanyConfigStampAlbum(Array $arrayData,int $company_id)
    {
        try{
            $currentUser = app('currentUser');
            if($arrayData['stamp_type'] === StampType::ICON){
                if(array_key_exists('image',$arrayData) && $arrayData['image'] && $arrayData['image'] instanceof UploadedFile) {
                    $fileName = $currentUser->company_id . '/' . time() . $currentUser->company_id . '.' . $arrayData['image']->extension();
                    Storage::disk(Disk::COMPANY)->put($fileName, file_get_contents($arrayData['image']));
                    $arrayData['icon_path'] = $fileName;
                }
                $config= $this->_companyConfigAlbumRepository->where('company_id','=',$company_id)->first();
                $config->update($arrayData);
                return $config;
            }
            if($arrayData['stamp_type'] === StampType::TEXT){
                $config= $this->_companyConfigAlbumRepository->where('company_id','=',$company_id)->first();
                if(!(array_key_exists('text',$arrayData) && $arrayData['text'])){
                    unset($arrayData['text']);
                }
                $config->update($arrayData);
                return $config;
            }
            throw new SystemException('messages.system_error');
        }catch (\Exception $exception) {
            report($exception);
            throw new SystemException('messages.system_error');
        }
    }
    public function  getStampConfig(int $companyId)
    {
        $configStamp = $this->_companyConfigAlbumRepository->where('company_id', '=', $companyId)->first();
        if (!$configStamp) {
            throw new NotFoundException('messages.not_found_with_companyId');
        }else{
            return $configStamp;
        }
    }
    public function createDefaultAlbumConfig(Array $arrayData,int $company_id){

        return $this->_companyConfigAlbumRepository->create(
           [
               'company_id'=>$company_id,
            'text' =>'',
            'mounting_position' => $arrayData['mounting_position'],
            'stamp_type' => StampType::ICON,
            'icon_path' =>  $arrayData['icon_path'],
        ]);
    }
}
