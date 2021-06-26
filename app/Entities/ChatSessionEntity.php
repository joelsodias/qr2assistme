<?php

namespace App\Entities;

use App\Entities\BaseEntity;


class ChatSessionEntity extends BaseEntity
{
   public function getSessionUid()
   {
      return $this->_getUuidField('session_uid');
   }

   public function setSessionUid(string $uuid)
   {
      $this->_setUuidField('session_uid', $uuid);
   }

   public function getAttendeeUid()
   {
      return $this->_getUuidField('attendee_uid');
   }

   public function setAttendeeUid(string $uuid)
   {
      $this->_setUuidField('attendee_uid', $uuid);
   }

   public function getAttendantUid()
   {
      return $this->_getUuidField('attendant_uid');
   }

   public function setAttendantUid($uuid)
   {
      $this->_setUuidField('attendant_uid', $uuid);
   }
}
