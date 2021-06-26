<?php

namespace App\Entities;

use App\Entities\BaseEntity;


class UserEntity extends BaseEntity
{
	public function getUserUid()
    {
       return $this->_getUuidField('user_uid');
    }

    public function setUserUid(string $uuid)
    {
       $this->_setUuidField('user_uid',$uuid);
    } 



}
