<?php

namespace App\Entities;

use App\Entities\BaseEntity;


class WorkerEntity extends BaseEntity
{
	public function getWorkerUid()
    {
       return $this->_getUuidField('worker_uid');
    }

    public function setWorkerUid(string $uuid)
    {
       $this->_setUuidField('worker_uid',$uuid);
    } 



}
