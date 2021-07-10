<?php

namespace App\Entities;

use App\Entities\BaseEntity;


class WorkerEntity extends BaseEntity
{

   protected $datalabels = [
      "worker_uid" => "Identificador",
      "worker_type" => "Tipo",
      "worker_type_translated" => "Tipo",
      "worker_name" => "Nome",
      "worker_email" => "E-mail",
      "worker_avatar" => "Imagem",
      "worker_description" => "Descrição",

   ];

	public function getWorkerUid()
    {
       return $this->_getUuidField('worker_uid');
    }

    public function setWorkerUid(string $uuid)
    {
       $this->_setUuidField('worker_uid',$uuid);
    } 

	public function getWorkerTypeTranslated()
    {
       $new = $this->worker_type;
       switch ($new) {
          case "field" : $new = "Técnico de Campo"; break; 
          case "attendant" : $new = "Atendente"; break; 
       } 
       return $new;
    }

    public function setWorkerTypeTranslated(string $value)
    {
      return null;
   } 

}
