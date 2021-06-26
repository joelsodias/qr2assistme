<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use CodeIgniter\I18n\Time;
use Ramsey\Uuid\Nonstandard\UuidV6;
use App\Helpers\CustomHelper;

class BaseEntity extends Entity
{

    protected $datalabels = [];

    protected $_datalabels = [
        "created_at" => "Criação",
        "updated_at" => "Atualização",
        //"deleted_at" => "Deleção",
    ];

    protected $datamap = [

    ];


    public function __construct(array $data = null) {
        parent::__construct($data);
        $this->datalabels = CustomHelper::array_merge_recursive_distinct($this->_datalabels, $this->datalabels);
    }

    public function getFieldLabel($fieldname) {
        return array_key_exists($fieldname,$this->datalabels) ? $this->datalabels[$fieldname] : ""; 
    }

    public function getFieldLabels(array $fields = []) {
        if (count($fields)) {
            return array_intersect_key($this->datalabels, array_flip($fields));
        } else {
            return $this->datalabels;
        }
    }

    protected function _getUuidField($field)
    {
        //return ($this->attributes[$field]) ? (string) UuidV6::fromBytes($this->attributes[$field]) : null;
        return ($this->attributes[$field]); 
        }

    protected function _setUuidField($field, $uuid)
    {
        //$this->attributes[$field] =  (strlen($uuid) > 16) ? UuidV6::fromString($uuid)->getBytes() : $uuid;
        $this->attributes[$field] = $uuid;
    }

    protected function _getDateAt($field, string $format = 'Y-m-d H:i:s')
    {
        // Convert to CodeIgniter\I18n\Time object
        $date = $this->mutateDate($this->attributes[$field]);

        if ($date) {

            $timezone = $this->timezone ?? app_timezone();

            $date->setTimezone($timezone);

            $date = $date->format($format);
        }
        return $date;
    }
    protected function _setDateAt($field, $value)
    {
        $date = $this->mutateDate($value);

        if ($date) {

            $timezone = $this->timezone ?? app_timezone();

            $date->setTimezone($timezone);

            $this->attributes[$field] = $date;
        }
        return $date;
    }
    public function getCreatedAt(string $format = 'Y-m-d H:i:s')
    {
        return $this->_getDateAt("created_at");
    }

    public function getUpdatedAt(string $format = 'Y-m-d H:i:s')
    {
        return $this->_getDateAt("updated_at");
    }
    
    public function setUpdatedAt($value)
    {
        return $this->_setDateAt("updated_at", $value);
    }

    public function getDeletedAt(string $format = 'Y-m-d H:i:s')
    {
        return $this->_getDateAt("deleted_at");
    }
}
