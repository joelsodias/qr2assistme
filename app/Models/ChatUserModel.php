<?php

namespace App\Models;

use App\Models\BaseModel;
use Ramsey\Uuid\Nonstandard\UuidV6;
use Ramsey\Uuid\Uuid;
use CodeIgniter\Database\BaseBuilder;


class ChatUserModel extends BaseModel
{
	protected $table                = 'chat_user';
	protected $returnType           = 'App\Entities\ChatUserEntity';



	public function isGoogleRegistered($google_uid)
	{
		$b = $this->builder();

		$q = $b->where('google_token', $google_uid, true)->get();
		$r = $q->getResult();

		if (count($r)) {
			return true;
		} else {
			return false;
		}
	}


	public function isFacebookRegistered($facebook_uid)
	{
		$b = $this->builder();

		$q = $b->where('facebook_token', $facebook_uid, true)->get();
		$r = $q->getResult("array");

		if (count($r)) {
			return true;
		} else {
			return false;
		}
	}

	public function getRandomChatUser($type = null)
	{

		$builder = $this->builder();
		if ($type != null) {
			$builder->where("user_type", $type);
		}
		$builder->orderBy('','RANDOM');
		$builder->limit(1);
		$r = $builder->get()->getResult("array");

		if (count($r)) {
			$e = new \App\Entities\ChatUserEntity($r[0]);
			return $e;
		} else {
			return null;
		}
	}

	public function getRandomChatUserWithNoChatSessions($type = null)
	{

		$builder = $this->builder();
		if ($type != null) {
			$builder->where("user_type", $type);
		}
		$builder->_CustomPropertyType = $type;
		$builder->whereNotIn('chat_user_uid', function (BaseBuilder $builder) {
			return $builder->select($builder->_CustomPropertyType . '_uid', false)->from('chat_session');
		});
		$builder->orderBy('','RANDOM');
		$builder->limit(1);
		$r = $builder->get()->getResult("array");

		if (count($r)) {
			$e = new \App\Entities\ChatUserEntity($r[0]);
			return $e;
		} else {
			return null;
		}
	}

	public function getChatUser(string $chat_user_uid, string $user_type = null)
	{
		if ($this->validateUuid($chat_user_uid)) {
			$builder = $this->builder();
			//$builder->where("chat_user_uid", $this->getUuidBytes($chat_user_uid));
			$builder->where("chat_user_uid", $chat_user_uid);
			if ($user_type) {
				$builder->where("user_type", $user_type);
			}
			$builder->limit(1);
			$r = $builder->get()->getResult("array");

			if (isset($r) && count($r)) {
				$e = new \App\Entities\ChatUserEntity($r[0]);
				return $e;
			} else {
				return null;
			}
		} else {
			return null;
		}
	}

	public function createChatUser($type, $guest = "N", \App\Entities\ChatUserEntity $data = null)
	{
		$e = null;
		if ($type == "attendee" || $type == "attendant") {
			
			if (!isset($data)) {
				$e = new \App\Entities\ChatUserEntity();
				$uuid = (string) Uuid::uuid6();
				$e->chat_user_uid = $uuid;
				$e->google_token = null;
				$e->facebook_token = null;
				$e->user_name = (($guest == "S") ? "Guest" : $type) . "_" . $uuid;
				$e->user_email = null;
				$e->user_avatar = '/images/avatar/' . $type . '.png';
				$e->user_type = $type;
				$e->is_guest = $guest;
			} else {
				$e = $data;
				$e->chat_user_uid = $e->chat_user_uid ?? (string) Uuid::uuid6();
				$e->user_type = $type;
			}
			
			$id = $this->insert($e, true);
			$e = $this->find($id);
		}
		return $e;
	}
}
