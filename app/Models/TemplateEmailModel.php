<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class TemplateEmailModel extends AbstractModel
{
    use SoftDeletes;

    protected $table = 'template_emails';

    protected $fillable = [
        'company_id',
        'title',
        'subject',
        'content',
        'created_user',
        'updated_user',
        'default',
        'cc',
        'bcc',
    ];

    protected $casts = [
        'cc' => 'array',
        'bcc' => 'array',
    ];

    public function createdUser()
    {
        return $this->belongsTo(UserModel::class, 'created_user', 'id');
    }

    public function updatedUser()
    {
        return $this->belongsTo(UserModel::class, 'updated_user', 'id');
    }
}
