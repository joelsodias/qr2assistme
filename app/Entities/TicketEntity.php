<?php

namespace App\Entities;

use App\Entities\BaseEntity;


class TicketEntity extends BaseEntity
{
	public function getTicketUid()
    {
       return $this->_getUuidField('ticket_uid');
    }

    public function setTicketUid(string $uuid)
    {
       $this->_setUuidField('ticket_uid',$uuid);
    } 

    public function getParentUid()
    {
       return $this->_getUuidField('parent_uid');
    }

    public function setParentUid(string $uuid)
    {
       $this->_setUuidField('parent_uid',$uuid);
    } 
    
    public function getOwnerUid()
    {
       return $this->_getUuidField('owner_uid');
    }

    public function setOwnerUid(string $uuid)
    {
       $this->_setUuidField('owner_uid',$uuid);
    } 
    
    public function getExecutorUid()
    {
       return $this->_getUuidField('executor_uid');
    }

    public function setExecutorUid(string $uuid)
    {
       $this->_setUuidField('executor_uid',$uuid);
    } 

}
