<?php

namespace App\Entities;

use App\Entities\BaseEntity;


class FileEntity extends BaseEntity
{
	public function getScheduleUid()
    {
       return $this->_getUuidField('schedule_uid');
    }

    public function setScheduleUid(string $uuid)
    {
       $this->_setUuidField('schedule_uid',$uuid);
    }

	public function getWorkerUid()
    {
       return $this->_getUuidField('worker_uid');
    }

    public function setWorkerUid(string $uuid)
    {
       $this->_setUuidField('worker_uid',$uuid);
    } 

    public function getTicketUid()
    {
       return $this->_getUuidField('ticket_uid');
    }

    public function setTicketUid(string $uuid)
    {
       $this->_setUuidField('ticket_uid',$uuid);
    } 

    public function getObjectUid()
    {
       return $this->_getUuidField('object_uid');
    }

    public function setObjectUid(string $uuid)
    {
       $this->_setUuidField('object_uid',$uuid);
    } 

    public function getFileUid()
    {
       return $this->_getUuidField('file_uid');
    }

    public function setFileUid(string $uuid)
    {
       $this->_setUuidField('file_uid',$uuid);
    } 



}
