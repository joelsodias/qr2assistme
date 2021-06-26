<?php

namespace App\Entities;

use App\Entities\BaseEntity;


class ChatUserEntity extends BaseEntity
{
	public function getChatUserUid()
    {
       return $this->_getUuidField('chat_user_uid');
    }

    public function setChatUserUid(string $uuid)
    {
       $this->_setUuidField('chat_user_uid',$uuid);
    } 



}
