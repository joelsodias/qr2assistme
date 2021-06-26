<?php

namespace App\Models;

use App\Models\BaseModel;
use CodeIgniter\Database\BaseBuilder;

class WorkerModel extends BaseModel
{
	protected $table      = 'worker';
	protected $returnType     = 'App\Entities\WorkerEntity';

	public function getWorker(string $uid = null, $type = null)
	{

		$builder = $this->builder();
		$builder->resetQuery();
		$builder->where("worker_uid", $uid);

		if (isset($type)) {
			$builder->where("worker_type", $type);
		}

		$r = $builder->get(1, 0, false)->getResult("array");

		if (count($r)) {
			$e = new \App\Entities\WorkerEntity($r[0]);
			return $e;
		} else {
			return null;
		}


	}


	public function getRandomWorker($type = null, bool $mustHaveUser = null)
	{

		$builder = $this->builder();

		if (isset($type)) {
			$builder->where("worker_type", $type);
		}


		if (isset($mustHaveUser)) {
			if ($mustHaveUser) {
				$builder->where(' EXISTS (SELECT user_uid FROM user WHERE user.worker_uid = worker.worker_uid) AND 1 =', 1, false);
			} else {
				$builder->where('NOT EXISTS (SELECT user_uid FROM user WHERE user.worker_uid = worker.worker_uid ) AND 1 =', 1, false);
			}
		}


		$builder->orderBy('','RANDOM');
		$builder->limit(1);
		//$sql = $builder->getCompiledSelect();
		$r = $builder->get(1, 0)->getResult("array");

		if (count($r)) {
			$e = new \App\Entities\WorkerEntity($r[0]);
			return $e;
		} else {
			return null;
		}
	}
}
