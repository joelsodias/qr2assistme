<?php

namespace App\Entities;

use App\Entities\BaseEntity;


class ChatUserEntity extends BaseEntity
{
    public function getChatUserUid()
    {
        return $this->internalGetUuidField('chat_user_uid');
    }

    public function setChatUserUid(string $uuid)
    {
        $this->internalSetUuidField('chat_user_uid', $uuid);
    }
}
