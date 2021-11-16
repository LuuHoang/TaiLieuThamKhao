<?php

namespace App\Models;

class PdfFileModel extends AbstractModel
{
    protected $table = 'pdf_files';

    protected $fillable = ['path', 'name', 'size', 'created_user'];
}
