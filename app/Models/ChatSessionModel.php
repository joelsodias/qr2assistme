<?php

namespace App\Models;

use App\Models\BaseModel;
use \Exception;
use Ramsey\Uuid\Nonstandard\UuidV6;

class ChatSessionModel extends BaseModel
{
	protected $table                = 'chat_session';
	protected $returnType           = 'App\Entities\ChatSessionEntity';
	protected $validStatuses        = ["new", "open", "closed"];
	protected $defaultLimit			= 1000;

	//protected $allowedFields        = ['session_uid','session_status', 'attendee_id','attendant_id'];

	public function getRandomChatSession($status = null)
	{
		try {
			$builder = $this->builder();
			if ($status != null) {
				$builder->where("session_status", $status);
			}
			$builder->orderBy('RAND()');
			$builder->limit(1);
			$r = $builder->get()->getResultArray();
			if (count($r)) {
				$e = new \App\Entities\ChatSessionEntity($r[0]);
				return $e;
			} else {
				return null;
			}
		} catch (Exception $error) {
			return null;
		}
	}


	public function getCompleteChatSessionsInfo(array $params)
	{
		$result = null;

		$session_uid = $this->getValueFromArray($params,"session_uid");
		$attendee_uid = $this->getValueFromArray($params,"attendee_uid");
		$attendant_uid = $this->getValueFromArray($params,"attendant_uid");
		$session_status = $this->getValueFromArray($params,"session_status");
		$limit = $this->getValueFromArray($params,"limit",$this->defaultLimit);
		$offset = $this->getValueFromArray($params,"offset",0);

		try {
			if (
				$this->validateUuid($session_uid,true) &&
				$this->validateUuid($attendee_uid,true) &&
				$this->validateUuid($attendant_uid,true) &&
				($session_status == null || in_array($session_status,$this->validStatuses)) 
			) {

				$builder = $this->builder();

				//$attendant_uid_bytes = $this->getUuidBytes($attendant_uid);
				//$attendee_uid_bytes = $this->getUuidBytes($attendee_uid);
				//$session_uid_bytes = $this->getUuidBytes($session_uid);
				$attendant_uid_bytes = $attendant_uid;
				$attendee_uid_bytes = $attendee_uid;
				$session_uid_bytes = $session_uid;


				$proc = "CALL uspGetCompleteSessionsInfo(:p_session_uid:, :p_attendant_uid:, :p_attendee_uid:, :p_session_status:, :p_limit:, :p_offset:)";
				//$proc = "CALL uspGetCompleteSessionsInfo( ?, ?, ?, ?, ?, ?)";

				$query = $this->db->query($proc, 
				  array(
					  "p_session_uid" => $session_uid_bytes,
					  "p_attendant_uid" => $attendant_uid_bytes,
					  "p_attendee_uid" => $attendee_uid_bytes,
					  "p_session_status" => $session_status,
					  "p_limit" => $limit,
					  "p_offset" => $offset
					  )
				);

				$r = $query->getResultArray();
                $r = $this->convertUuidFieldsArray($r); 
				
				if (count($r)) {
  				  $result=$r;
				}
			}
		} catch (Exception $error) {
			return null;
		}
		return $result;
	}


	public function getChatSessionsByUser($chat_user_uid = null, $user_type = null, 
	array $session_status = null, $order = null, $limit = null)
	{
		try {
			if ($this->validateUuid($chat_user_uid)) {
				$builder = $this->builder();
				if ($user_type) {
					//$builder->where($user_type . "_uid", $this->getUuidBytes($chat_user_uid));
					$builder->where($user_type . "_uid", $chat_user_uid);
				} else {
					//$builder->Where("attendee_uid", $this->getUuidBytes($chat_user_uid));
					//$builder->orWhere("attendant_uid", $this->getUuidBytes($chat_user_uid));
					$builder->Where("attendee_uid", $chat_user_uid);
					$builder->orWhere("attendant_uid", $chat_user_uid);
				}
				if ($order) {
					$builder->orderBy("created_at", $order);
				}
				if ($session_status) {
					$builder->whereIn("session_status", $session_status);
				}
				$r = $builder->get(($limit) ? $limit : $this->defaultLimit)->getResultArray();
				if (count($r)) {
					foreach ($r as $value) {
						//protect internal id
						unset($value["session_id"]);
						$result[] = new \App\Entities\ChatSessionEntity($value);
					}
					return $result;
				} else {
					return null;
				}
			}
		} catch (Exception $error) {
			return null;
		}
	}


	public function getChatSessionById($session_uid = null, array $session_status = null)
	{
		try {
			if ($this->validateUuid($session_uid)) {
				$builder = $this->builder();
				//$builder->where("session_uid", $this->getUuidBytes($session_uid));
				$builder->where("session_uid", $session_uid);
				if ($session_status) {
					$builder->whereIn("session_status", $session_status);
				}
				$builder->orderBy("created_at", "desc");
				$builder->limit(1);
				$r = $builder->get()->getResultArray();
				if (count($r)) {
					$e = new \App\Entities\ChatSessionEntity($r[0]);
					return $e;
				} else {
					return null;
				}
			} else {
				return null;
			}
		} catch (Exception $error) {
			return null;
		}
	}

	public function getChatSessionsByStatus($session_status = null, string $order = null, int $limit = null)
	{
		$result = null;

		try {
			if ($session_status) {
				$builder = $this->builder();
				$builder->select(" `chat_session`.*, 
				(SELECT COUNT(*) 
				FROM	chat_message m WHERE m.session_uid = `chat_session`.session_uid) AS message_count ");
				if (is_array($session_status)) {
					if (count(array_diff($session_status, $this->validStatuses)) > 0) {
						return null;
					}
					$builder->whereIn("session_status", $session_status);
				} else {
					if (!in_array($session_status, $this->validStatuses)) {
						return null;
					}
					$builder->where("session_status", $session_status);
				}
				$builder->where("deleted_at is null");

				$builder->orderBy("created_at", (in_array(strtoupper($order), ["ASC", "DESC"])) ? $order : "ASC");
				$builder->limit(($limit) ? $limit : $this->defaultLimit);
				$r = $builder->get()->getResultArray();
				if (count($r)) {
					foreach ($r as $value) {
						//protect internal id
						unset($value["session_id"]);
						unset($value["deleted_at"]);
						$result[] = new \App\Entities\ChatSessionEntity($value);
					}
				}
			}
		} catch (Exception $error) {
			$result = null;
		}
		return $result;
	}

	

	public function createChatSession($attendee_uid)
	{
		try {
			$session_uid = UuidV6::uuid6();
			$session_uid_str = (string) $session_uid;

			$e = new \App\Entities\ChatSessionEntity();
			$e->session_uid = $session_uid_str;
			$e->attendee_uid = $attendee_uid;
			$e->session_status = "new";

			$this->save($e, true);
			$e = $this->find($this->getInsertID());
			return $e;
		} catch (Exception $error) {
			return null;
		}
	}
}
