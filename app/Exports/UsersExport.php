<?php

namespace App\Exports;

use App\Constants\ColumnExcelUser;
use App\Http\Resources\UserExportFormatResource;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;

class UsersExport extends StringValueBinder implements FromCollection, WithHeadings, WithCustomValueBinder
{
    use Exportable;

    private $writerType = Excel::XLSX;

    protected $_dataUsers;

    public function __construct(Collection $dataUsers)
    {
        $this->_dataUsers = $dataUsers;
    }

    /**
     * @inheritDoc
     */
    public function headings(): array
    {
        return array_values(ColumnExcelUser::TITLES);
    }

    /**
     * @inheritDoc
     */
    public function collection()
    {
        return UserExportFormatResource::collection($this->_dataUsers);
    }
}
