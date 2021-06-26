<?php

namespace App\Entities;

use App\Entities\BaseEntity;


class ChatMessageEntity extends BaseEntity
{
   public function getMessageUid()
   {
      return $this->_getUuidField('message_uid');
   }

   public function setMessageUid(string $uuid)
   {
      $this->_setUuidField('message_uid', $uuid);
   }

   public function getSessionUid()
   {
      return $this->_getUuidField('session_uid');
   }

   public function setSessionUid(string $uuid)
   {
      $this->_setUuidField('session_uid', $uuid);
   }

   public function getSenderUid()
   {
      return $this->_getUuidField('sender_uid');
   }

   public function setSenderUid(string $uuid)
   {
      $this->_setUuidField('sender_uid', $uuid);
   }
}
