<?php
namespace App\Utils;

use App\Models\PdfFileModel;
use Illuminate\Support\Collection;

class Util
{
    public static function setPdfFilesByUser(int $userId): void
    {
        $files = PdfFileModel::all();
        app()->instance('pdf_files', $files);
    }

    public static function getPdfFilesOfUser(?array $list = []): Collection
    {
        $collection = app()->bound('pdf_files') ? app('pdf_files') : collect([]);
        if ($collection->isEmpty() || is_null($list)) {
            return $collection;
        }

        return $collection->whereIn('id', $list);
    }
}
