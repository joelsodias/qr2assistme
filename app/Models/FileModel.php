<?php

namespace App\Models;

use App\Models\BaseModel;

class FileModel extends BaseModel
{
    protected $table      = 'file';
    protected $returnType     = 'App\Entities\FileEntity';

}