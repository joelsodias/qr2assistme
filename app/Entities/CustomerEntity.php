<?php

namespace App\Entities;

use App\Entities\BaseEntity;


class CustomerEntity extends BaseEntity
{

   protected $datalabels = [
      "customer_name" => "Nome",
      "customer_email" => "E-mail",
      "customer_description" => "Descrição",
      "customer_admin_user_uid" => "Id do administrador",
   ];

	public function getCustomerUid()
    {
       return $this->_getUuidField('customer_uid');
    }

    public function setCustomerUid(string $uuid)
    {
       $this->_setUuidField('customer_uid',$uuid);
    } 



}
