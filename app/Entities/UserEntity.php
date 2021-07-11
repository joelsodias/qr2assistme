<?php

namespace App\Entities;

use App\Entities\BaseEntity;


class UserEntity extends BaseEntity
{
    public function getUserUid()
    {
        return $this->internalGetUuidField('user_uid');
    }

    public function setUserUid(string $uuid)
    {
        $this->internalSetUuidField('user_uid', $uuid);
    }
}
