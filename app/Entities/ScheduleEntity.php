<?php

namespace App\Entities;

use App\Entities\BaseEntity;


class ScheduleEntity extends BaseEntity
{
	public function getScheduleUid()
    {
       return $this->_getUuidField('schedule_uid');
    }

    public function setScheduleUid(string $uuid)
    {
       $this->_setUuidField('schedule_uid',$uuid);
    }

    public function getScheduledByUid()
    {
       return $this->_getUuidField('schedule_by_uid');
    }

    public function setScheduledByUid(string $uuid)
    {
       $this->_setUuidField('schedule_by_uid',$uuid);
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



}
