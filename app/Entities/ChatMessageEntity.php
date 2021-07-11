<?php

namespace App\Entities;

use App\Entities\BaseEntity;

class ChatMessageEntity extends BaseEntity
{
    public function getMessageUid()
    {
        return $this->internalGetUuidField('message_uid');
    }

    public function setMessageUid(string $uuid)
    {
        $this->internalSetUuidField('message_uid', $uuid);
    }

    public function getSessionUid()
    {
        return $this->internalGetUuidField('session_uid');
    }

    public function setSessionUid(string $uuid)
    {
        $this->internalSetUuidField('session_uid', $uuid);
    }

    public function getSenderUid()
    {
        return $this->internalGetUuidField('sender_uid');
    }

    public function setSenderUid(string $uuid)
    {
        $this->internalSetUuidField('sender_uid', $uuid);
    }
}
