<?php

namespace App\Models;

use App\Models\BaseModel;

class FileModel extends BaseModel
{
    protected $table      = 'file';
    protected $returnType     = 'App\Entities\FileEntity';

    public function getFiles(string $schedule_uid = null)
    {
        $builder = $this->builder();
        $f = null;
        if ($this->validateUuid($schedule_uid)) {
            $builder->where("schedule_uid", $schedule_uid);

            $r = $builder->get()->getResult($this->returnType);

            if (isset($r) && count($r)) {
                $f = $r;
            }
        }
        return $f;
    }
}
