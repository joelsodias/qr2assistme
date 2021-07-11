<?php

namespace App\Entities;

use App\Entities\BaseEntity;


class FileEntity extends BaseEntity
{
    public function getScheduleUid()
    {
        return $this->internalGetUuidField('schedule_uid');
    }

    public function setScheduleUid(string $uuid)
    {
        $this->internalSetUuidField('schedule_uid', $uuid);
    }

    public function getWorkerUid()
    {
        return $this->internalGetUuidField('worker_uid');
    }

    public function setWorkerUid(string $uuid)
    {
        $this->internalSetUuidField('worker_uid', $uuid);
    }

    public function getTicketUid()
    {
        return $this->internalGetUuidField('ticket_uid');
    }

    public function setTicketUid(string $uuid)
    {
        $this->internalSetUuidField('ticket_uid', $uuid);
    }

    public function getObjectUid()
    {
        return $this->internalGetUuidField('object_uid');
    }

    public function setObjectUid(string $uuid)
    {
        $this->internalSetUuidField('object_uid', $uuid);
    }

    public function getFileUid()
    {
        return $this->internalGetUuidField('file_uid');
    }

    public function setFileUid(string $uuid)
    {
        $this->internalSetUuidField('file_uid', $uuid);
    }
}
