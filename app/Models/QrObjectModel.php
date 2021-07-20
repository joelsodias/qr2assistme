<?php

namespace App\Models;

use App\Models\BaseModel;

class QrObjectModel extends BaseModel
{
    protected $table      = 'qrobject';
    protected $returnType     = 'App\Entities\QrObjectEntity';

    public function getObject(string $object_uid = null)
    {
        $builder = $this->builder();
        $builder->join("customer", "qrobject.object_owner_uid = customer.customer_uid", "left outer");
        $builder->where("object_uid", $object_uid);

        $r = $builder->get(1)->getResult();

        if (isset($r) && count($r)) {
            return $r[0];
        } else {
            return null;
        }
    }
}
