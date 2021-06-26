<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class ChatSessionSeeder extends Seeder
{
	public function run()
	{
		$max = 100;
		for ($i = 0; $i < $max; $i++) {
			// get a randiom attendee
			$userModel = new \App\Models\ChatUserModel();
			$attendee = $userModel->getRandomChatUserWithNoChatSessions("attendee");
			$attendee_uid = $attendee->chat_user_uid;

			// creata a new session 
			$sessionModel = new \App\Models\ChatSessionModel();
			$session = $sessionModel->createChatSession($attendee_uid);
			$session_uid = $session->session_uid;

			// get a random attendant
			$attendant = $userModel->getRandomChatUser("attendant");
			$attendant_uid = $attendant->chat_user_uid;

			// open a chat session
			$session->attendant_uid = $attendant->chat_user_uid;
			$session->updated_at = new Time('now');
			$session->session_status = 'open';

			$sessionModel->save($session);
		}
	}
}
