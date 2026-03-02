<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateSk extends Model
{
    protected $fillable = ['nama_template', 'file_path', 'is_active'];
}
