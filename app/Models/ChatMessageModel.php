<?php

namespace App\Models;

use CodeIgniter\Model;
use Ramsey\Uuid\Nonstandard\UuidV6;
use Ramsey\Uuid\Uuid;


class ChatMessageModel extends BaseModel
{
	protected $table                = 'chat_message';
	protected $returnType           = 'App\Entities\ChatMessageEntity';


	public function getMessage(string $message_uid)
	{
		if ($this->validateUuid($message_uid)) {
			$builder = $this->builder();

			$builder->where("message_uid", $message_uid);
			$builder->limit(1);
			$r = $builder->get()->getResultArray();
		}
		if (count($r)) {
			$e = new \App\Entities\ChatMessageEntity($r[0]);
			return $e;
		} else {
			return null;
		}
	}

	public function getMessagesBySession(string $session_uid, $limit = null)
	{
		if ($this->validateUuid($session_uid)) {
			$builder = $this->builder();
			$builder->select("chat_message.*, chat_user.user_avatar");
			$builder->join("chat_user","chat_user.chat_user_uid = chat_message.sender_uid","left outer");

			$builder->where("session_uid", $session_uid);
			if ($limit) {
				$builder->limit($limit);
			}
			$builder->orderBy("created_at", "ASC");
			$r = $builder->get()->getResultArray();
		}
		if (count($r)) {
			foreach ($r as $value) {
				//protect internal id
				unset($value["chat_message_id"]);
				$result[] = new \App\Entities\ChatMessageEntity($value);
			}
			return $result;
		} else {
			return null;
		}
	}

	public function getUnreadMessagesByUser(string $chat_user_uid, string $session_uid = null, $limit = null)
	{
		if ($this->validateUuid($chat_user_uid)) {

			$builder = $this->builder();

			$sql =
			//  " SELECT m.* FROM chat_message m          "
			// . " WHERE m.session_uid = :p_session_uid:  " 
			// . " AND ((m.sync_status in ('stored','confirmed')  "
			// . " AND m.sender_uid <> :p_user_uid:)    "
			// . " OR ((m.sync_status in 'read'          "
			// . " AND m.sender_uid = :p_user_uid:)))    "
			// . " ORDER BY m.created_at                  ";

			 " SELECT m.* FROM chat_message m "           
			. " WHERE m.session_uid = :p_session_uid: "  
			. " AND "
			. " ( "
			. "   (m.sync_status in ('stored','confirmed') AND m.sender_uid <> :p_user_uid:) "
			. "   OR "
			. "   (m.sync_status IN ('read')  AND m.sender_uid = :p_user_uid:) "
			. " )     "
			. " ORDER BY m.created_at ASC ";

			$query = $this->db->query($sql, 
			  [
				'p_user_uid' => $chat_user_uid,
				'p_session_uid' => $session_uid
				
				]);
			$r = $query->getResultArray();
		}
		if (count($r)) {
			foreach ($r as $value) {
				//protect internal id
				unset($value["chat_message_id"]);
				$result[] = new \App\Entities\ChatMessageEntity($value);
			}
			return $result;
		} else {
			return null;
		}
	}
}
