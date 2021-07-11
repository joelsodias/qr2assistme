<?php

namespace App\Entities;

use App\Entities\BaseEntity;


class TicketEntity extends BaseEntity
{
    public function getTicketUid()
    {
        return $this->internalGetUuidField('ticket_uid');
    }

    public function setTicketUid(string $uuid)
    {
        $this->internalSetUuidField('ticket_uid', $uuid);
    }

    public function getParentUid()
    {
        return $this->internalGetUuidField('ticket_parent_uid');
    }

    public function setParentUid(string $uuid)
    {
        $this->internalSetUuidField('ticket_parent_uid', $uuid);
    }

    public function getOwnerUid()
    {
        return $this->internalGetUuidField('ticket_owner_uid');
    }

    public function setOwnerUid(string $uuid)
    {
        $this->internalSetUuidField('ticket_owner_uid', $uuid);
    }

    public function getExecutorUid()
    {
        return $this->internalGetUuidField('ticket_executor_uid');
    }

    public function setExecutorUid(string $uuid)
    {
        $this->internalSetUuidFieldX('ticket_executor_uid', $uuid);
    }
}
