<?php

namespace App\Entities;

use App\Entities\BaseEntity;

class ScheduleEntity extends BaseEntity
{
    public function getScheduleUid()
    {
        return $this->internalGetUuidField('schedule_uid');
    }

    public function setScheduleUid($uuid)
    {
        $this->internalSetUuidField('schedule_uid', $uuid);
    }

    public function getScheduledByUid()
    {
        return $this->internalGetUuidField('schedule_by_uid');
    }

    public function setScheduledByUid($uuid)
    {
        $this->internalSetUuidField('schedule_by_uid', $uuid);
    }

    public function getWorkerUid()
    {
        return $this->internalGetUuidField('worker_uid');
    }

    public function setWorkerUid($uuid)
    {
        $this->internalSetUuidField('worker_uid', $uuid);
    }

    public function getTicketUid()
    {
        return $this->internalGetUuidField('ticket_uid');
    }

    public function setTicketUid($uuid)
    {
        $this->internalSetUuidField('ticket_uid', $uuid);
    }

    public function getObjectUid()
    {
        return $this->internalGetUuidField('object_uid');
    }

    public function setObjectUid($uuid)
    {
        $this->internalSetUuidField('object_uid', $uuid);
    }
}
