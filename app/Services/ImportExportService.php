<?php

namespace App\Services;

use App\Constants\Disk;
use App\Exceptions\ForbiddenException;
use App\Exceptions\UnprocessableException;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use App\Models\UserModel;
use App\Models\UserRoleModel;
use App\Repositories\Repository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class ImportExportService extends AbstractService
{
    protected $userRepository;
    protected $userRoleRepository;

    public function __construct(UserModel $userModel, UserRoleModel $userRoleModel)
    {
        $this->userRepository = new Repository($userModel);
        $this->userRoleRepository = new Repository($userRoleModel);
    }

    public function exportUsers(int $companyId)
    {
        $userEntities = $this->userRepository->where('company_id', '=', $companyId)->all();
        $excel = new UsersExport($userEntities);
        $fileName = time() . '.xlsx';
        $excel->store($fileName, Disk::DOWNLOAD);
        return Storage::disk(Disk::DOWNLOAD)->url($fileName);
    }

    public function importUsers(int $companyId, UploadedFile $file)
    {
        $userEntities = $this->userRepository->where('company_id', '=', $companyId)->all();
        $ruleEntities = $this->userRoleRepository->where('company_id', '=', $companyId)->all();
        $import = new UsersImport($companyId, $userEntities, $ruleEntities);
        $import->import($file);
        if ($import->getImportRowCount() === 0 && $import->failures()->count() === 0) {
            throw new ForbiddenException('messages.imported_file_has_no_data');
        }
        return $import->failures();
    }
}
