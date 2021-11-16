<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class PdfContentTemplateModel extends AbstractModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pdf_content_templates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'html', 'image_no'];
}
