<?php

namespace App\Entities;

use App\Entities\BaseEntity;


class QrObjectEntity extends BaseEntity
{

   protected $datalabels = [
      "customer_name" => "Proprietário",
      "object_name" => "Nome",
      "object_serial" => "Número Serial",
      "object_model" => "Modelo",
      "object_voltage" => "Voltagem",
      "object_description" => "Descrição",
      "object_obs" => "Observações",
      "created_at" => "Criação",
      "object_uid" => "Id de Objeto",
      "owner_uid" => "Id do Propritário",
   ];
   

	public function getObjectUid()
    {
       return $this->_getUuidField('object_uid');
    }

    public function setObjectUid(string $uuid)
    {
       $this->_setUuidField('object_uid',$uuid);
    } 

    public function getOwnerUid()
    {
       return $this->_getUuidField('owner_uid');
    }

    public function setOwnerUid(string $uuid)
    {
       $this->_setUuidField('owner_uid',$uuid);
    } 
}
