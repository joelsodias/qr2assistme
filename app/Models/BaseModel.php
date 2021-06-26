<?php

namespace App\Models;

use CodeIgniter\Model;
use Ramsey\Uuid\Nonstandard\UuidV6;
use App\Helpers\CustomHelper;

class BaseModel extends Model
{
	protected $DBGroup              = 'default';
	// protected $table                = strtolower(str_replace("Model", "", $this::class));
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = true;
	protected $protectFields        = false;
	protected $allowedFields        = [];

	// Dates
	protected $useTimestamps        = false;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';

	// Validation
	protected $validationRules      = [];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks       = true;
	protected $beforeInsert         = [];
	protected $afterInsert          = [];
	protected $beforeUpdate         = [];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];


	protected function validateUuid($uuid =null, $allowNull = false) {
		return  ((!isset($uuid) && $allowNull) || (isset($uuid) && UuidV6::isValid($uuid)));
	}

	protected function getUuidBytes(string $uuid = null)
	{
		return ($uuid) ? UuidV6::fromString($uuid)->getBytes() : null;
	}

	protected function getUuidString($uuid = null)
	{
		if ($uuid) {
			if (strlen($uuid) == 16) {
				$uuid = (string) UuidV6::fromBytes($uuid);
			} elseif (strlen($uuid) == 36) {
				$uuid = (UuidV6::isValid($uuid)) ? $uuid : null;
			} else { throw new \Exception("Uuid with wrong length expecting 16 or 36");  }
		} 
		return $uuid;
	}

	protected function convertUuidFieldsArray(array $data){
       array_walk_recursive($data, function (&$item, $key){
		   if (CustomHelper::str_ends_with($key,"_uid")) {
			   $item = $this->getUuidString($item);
		   }
	   });
	   return $data;
	}


	protected function getValueFromArray(array $params, $key, $default = null)
	{
		return (array_key_exists($key, $params)) ? $params[$key] : $default;
	}


}
