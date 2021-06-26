<@php

namespace {namespace};

use App\Models\BaseModel;

class {class} extends BaseModel
{
	protected $DBGroup              = 'default';
	protected $table                = '{class}';
	protected $primaryKey           = '{class}_id';
	protected $returnType           = 'App\Entities\{class}Entity';
	protected $protectFields        = true;
	protected $allowedFields        = [];

	
}
