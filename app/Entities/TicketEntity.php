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
       return $this->_getUuidField('ticket_parent_uid');
    }

    public function setParentUid(string $uuid)
    {
       $this->_setUuidField('ticket_parent_uid',$uuid);
    } 
    
    public function getOwnerUid()
    {
       return $this->_getUuidField('ticket_owner_uid');
    }

    public function setOwnerUid(string $uuid)
    {
       $this->_setUuidField('ticket_owner_uid',$uuid);
    } 
    
    public function getExecutorUid()
    {
       return $this->_getUuidField('ticket_executor_uid');
    }

    public function setExecutorUid(string $uuid)
    {
       $this->_setUuidField('ticket_executor_uid',$uuid);
    } 

}
