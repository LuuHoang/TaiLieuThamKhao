<?php

namespace App\Imports;

use App\Constants\App;
use App\Constants\ColumnExcelUser;
use App\Models\UserModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Throwable;

class UsersImport implements ToModel, WithValidation, WithHeadingRow, SkipsOnFailure, WithUpserts, SkipsOnError
{
    use Importable, SkipsFailures, SkipsErrors;

    protected $_companyId;

    protected $_userEntities;

    protected $_roleEntities;

    private $importRows = 0;

    public function __construct(int $companyId, Collection $userEntities, Collection $roleEntities)
    {
        $this->_companyId = $companyId;
        $this->_userEntities = $userEntities;
        $this->_roleEntities = $roleEntities;
    }

    public function getImportRowCount(): int
    {
        return $this->importRows;
    }

    /**
     * @inheritDoc
     */
    public function model(array $row)
    {
        ++$this->importRows;
        return new UserModel([
            'company_id' => $this->_companyId,
            'department' => $row[Str::slug(ColumnExcelUser::TITLES[ColumnExcelUser::DEPARTMENT], '_')],
            'position' => $row[Str::slug(ColumnExcelUser::TITLES[ColumnExcelUser::POSITION], '_')],
            'staff_code' => $row[Str::slug(ColumnExcelUser::TITLES[ColumnExcelUser::STAFF_CODE], '_')],
            'full_name' => $row[Str::slug(ColumnExcelUser::TITLES[ColumnExcelUser::NAME], '_')],
            'email' => $row[Str::slug(ColumnExcelUser::TITLES[ColumnExcelUser::EMAIL], '_')],
            'address' => $row[Str::slug(ColumnExcelUser::TITLES[ColumnExcelUser::ADDRESS], '_')],
            'password' => Hash::make(App::PASSWORD_DEFAULT),
            'created_at' => now(),
            'updated_at' => now(),
            'user_created_id' => $this->_userEntities->where('email', $row[Str::slug(ColumnExcelUser::TITLES[ColumnExcelUser::USER_MANAGER], '_')])->first()->id ?? null,
            'role_id' => $this->_roleEntities->filter(function ($roleEntity) use ($row) {
                return strtolower($roleEntity->name) === strtolower($row[Str::slug(ColumnExcelUser::TITLES[ColumnExcelUser::ROLE], '_')]);
            })->first()->id,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            Str::slug(ColumnExcelUser::TITLES[ColumnExcelUser::DEPARTMENT], '_') => 'nullable|string|max:255',
            Str::slug(ColumnExcelUser::TITLES[ColumnExcelUser::POSITION], '_') => 'nullable|string|max:255',
            Str::slug(ColumnExcelUser::TITLES[ColumnExcelUser::STAFF_CODE], '_') => "nullable|alpha_num|max:8|import_unique:users,staff_code,deleted_at,NULL,company_id,{$this->_companyId}",
            Str::slug(ColumnExcelUser::TITLES[ColumnExcelUser::NAME], '_') => 'required|string|max:255',
            Str::slug(ColumnExcelUser::TITLES[ColumnExcelUser::EMAIL], '_') => "required|email|max:255",
            Str::slug(ColumnExcelUser::TITLES[ColumnExcelUser::ADDRESS], '_') => 'nullable|string|max:255',
            Str::slug(ColumnExcelUser::TITLES[ColumnExcelUser::USER_MANAGER], '_') => "nullable|email|max:255|exists:users,email,company_id,{$this->_companyId},deleted_at,NULL",
            Str::slug(ColumnExcelUser::TITLES[ColumnExcelUser::ROLE], '_') => "required|string|max:255|exists:user_roles,name,company_id,{$this->_companyId},deleted_at,NULL",
        ];
    }

    public function customValidationAttributes()
    {
        return [
            Str::slug(ColumnExcelUser::TITLES[ColumnExcelUser::DEPARTMENT], '_') => ColumnExcelUser::TITLES[ColumnExcelUser::DEPARTMENT],
            Str::slug(ColumnExcelUser::TITLES[ColumnExcelUser::POSITION], '_') => ColumnExcelUser::TITLES[ColumnExcelUser::POSITION],
            Str::slug(ColumnExcelUser::TITLES[ColumnExcelUser::STAFF_CODE], '_') => ColumnExcelUser::TITLES[ColumnExcelUser::STAFF_CODE],
            Str::slug(ColumnExcelUser::TITLES[ColumnExcelUser::NAME], '_') => ColumnExcelUser::TITLES[ColumnExcelUser::NAME],
            Str::slug(ColumnExcelUser::TITLES[ColumnExcelUser::EMAIL], '_') => ColumnExcelUser::TITLES[ColumnExcelUser::EMAIL],
            Str::slug(ColumnExcelUser::TITLES[ColumnExcelUser::ADDRESS], '_') => ColumnExcelUser::TITLES[ColumnExcelUser::ADDRESS],
            Str::slug(ColumnExcelUser::TITLES[ColumnExcelUser::USER_MANAGER], '_') => ColumnExcelUser::TITLES[ColumnExcelUser::USER_MANAGER],
            Str::slug(ColumnExcelUser::TITLES[ColumnExcelUser::ROLE], '_') => ColumnExcelUser::TITLES[ColumnExcelUser::ROLE],
        ];
    }

    public function customValidationMessages()
    {
        return [
            Str::slug(ColumnExcelUser::TITLES[ColumnExcelUser::STAFF_CODE], '_') . '.import_unique' => __('messages.staff_code_already_exists'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function uniqueBy()
    {
        return ['company_id', 'email'];
    }
}
