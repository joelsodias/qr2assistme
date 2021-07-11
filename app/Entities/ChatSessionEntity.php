<?php

namespace App\Entities;

use App\Entities\BaseEntity;


class ChatSessionEntity extends BaseEntity
{
    public function getSessionUid()
    {
        return $this->internalGetUuidField('session_uid');
    }

    public function setSessionUid(string $uuid)
    {
        $this->internalSetUuidField('session_uid', $uuid);
    }

    public function getAttendeeUid()
    {
        return $this->internalGetUuidField('attendee_uid');
    }

    public function setAttendeeUid(string $uuid)
    {
        $this->internalSetUuidField('attendee_uid', $uuid);
    }

    public function getAttendantUid()
    {
        return $this->internalGetUuidField('attendant_uid');
    }

    public function setAttendantUid($uuid)
    {
        $this->internalSetUuidField('attendant_uid', $uuid);
    }
}
