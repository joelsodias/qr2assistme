<?php

namespace App\Controllers;

use CodeIgniter\I18n\Time;
use Ramsey\Uuid\Nonstandard\UuidV6;
use CodeIgniter\HTTP\RequestInterface;

class ChatController extends BaseAdminLteController
{
    public function chatAttendeeLogin()
    {
    }

    public function chatAttendeeGuest()
    {

        $userModel = new \App\Models\ChatUserModel();
        $e = $userModel->createChatUser("attendee", "S");

        $sessionModel = new \App\Models\ChatSessionModel();
        $session = $sessionModel->createChatSession($e->chat_user_uid);

        return redirect()->to("/attendee/chat/front/" . $e->chat_user_uid);
    }

    public function chatAttendeeIdentified()
    {
        $section = "attendee";
        $provider = $this->getSessionLoginInfo("provider");

        if ($provider && $this->checkSessionLoggedOn($section, $provider)) {
            $model = new \App\Models\ChatUserModel();
            $builder = $model->builder();
            $user = $this->getSessionLoginInfo("user", $section, $provider);
            $token = $this->getSessionLoginInfo("token", $section, $provider);

            $builder->where("google_email", $user["email"]);
            $builder->orWhere("facebook_email", $user["email"]);
            $r = $builder->get(1)->getResult("\App\Entities\ChatUserEntity");

            if ($r) {
                $e = $r[0];
                $e->google_avatar = $user["avatar"];
                $e->google_token = serialize($token);
                $e->updated_at = new Time('now');
                $model->save($e);
            } else {
                $e = new \App\Entities\ChatUserEntity();

                $e->chat_user_uid = $this->getNewUUidString();
                $e->user_type = "attendee";
                $e->google_token = serialize($token);
                $e->google_email = $user["email"];
                $e->google_avatar = $user["avatar"];
                $e->user_avatar = $user["avatar"];
                $e->user_name =  $user["first_name"] . " " . $user["last_name"];
                $e->google_name =  $user["first_name"] . " " . $user["last_name"];
                $e->is_guest = "N";
                $model->insert($e);
            }

            return redirect()->to("/attendee/chat/front/" . $e->chat_user_uid);
        } else {
            return redirect()->to("/attendee/login/chat");
        }
    }


    public function chatAttendee($attendee_uid)
    {
        $errors = [];
        if ($this->validateUuid($attendee_uid)) {
            $userModel = new \App\Models\ChatUserModel();
            $attendee = $userModel->getChatUser($attendee_uid, "attendee");

            if ($attendee) {
                $sessionModel = new \App\Models\ChatSessionModel();
                $session = $sessionModel->getChatSessionsByUser($attendee_uid, "attendee", ["new", "open"], "DESC", 1);

                if (!$session) {
                    $session = $sessionModel->createChatSession($attendee_uid);
                } else {
                    $session = $session[0];
                }




                $data = [
                    "page_title" => "Chat",
                    "layout" => "layouts/layout_bootstrap_clear_noresize",
                    "attendee" => $attendee,
                    "session" => $session,

                ];

                return $this->view("content/chat/chat_attendee_view", $data);
            }
        } else {
            return redirect()->to("/");
        }
    }

    public function chatAttendant($attendant_uid)
    {
        $errors = [];
        if ($this->validateUuid($attendant_uid)) {
            $userModel = new \App\Models\ChatUserModel();
            $attendant = $userModel->getChatUser($attendant_uid, "attendant");

            if ($attendant) {
                $sessionModel = new \App\Models\ChatSessionModel();

                $sessions = $sessionModel->getCompleteChatSessionsInfo([]);
                $sessionsObj = json_decode(json_encode($sessions), false);
                if ($sessionsObj) {
                    $data = [
                        //"layout" => "layouts/layout_bootstrap_clear_noresize",
                        "page_title" => "Chat (Atendente)",
                        "attendant" => $attendant,
                        "sessions" => $sessionsObj,
                    ];

                    return $this->view("content/chat/chat_attendant_view", $data);
                } else {
                    return "Error trying to stablish an attendant chat session";
                }
            }
        } else {
            return redirect()->to("/");
        }
    }

    public function registerChatUser($type)
    {
        if ($type == "attendee" || $type == "attendant" ||  $type == "guest") {
            $uuid = $this->getNewUUidString();

            $guest = ($type == "guest") ? "S" : "N";
            $type = ($type == "guest") ? "attendee" : $type;

            $model = new \App\Models\ChatUserModel();
            $e = $model->createChatUser($type, $guest);

            $data = [
                "status" => "Success",
                "status_code" => 200,
                "status_message" => "User created",
                "user_data" => [
                    "chat_user_uid" => $uuid,
                    "user_type" => $e->user_type,
                    "user_name" => $e->user_name,
                    "user_email" => $e->user_email,
                    "user_avatar" => $e->user_avatar,
                ]
            ];
        } else {
            $data = [
                "status" => "Bad Request",
                "status_code" => 400,
                "status_message" => "Error: Invalid user type",
                "user_data" => []
            ];
        }
        //return $this->response->setJSON($data);
        return $this->getJsonWithCSRF($data);
    }

    public function listChatUsers()
    {
        $limit = 100; // TODO: get limit from Global Options
        $userModel = new \App\Models\ChatUserModel();
        $user = $userModel->findAll($limit);
        $count = count($user);
        if ($count > 0) {
            $data = [
                "status" => "Success",
                "status_code" => 200,
                "status_message" => ($count == $limit ? "Limit of " : "") . "$count users returned",
                "user_data" => $user
            ];
        } else {
            $data = [
                "status" => "Not Found",
                "status_code" => 404,
                "status_message" => "Error: No users returned",
                "user_data" => []
            ];
        }
        //return $this->response->setJSON($data);
        return $this->getJsonWithCSRF($data);
    }

    public function getChatUser($chat_user_uid = null)
    {
        $e = null;
        if ($this->validateUuid($chat_user_uid)) {
            $userModel = new \App\Models\ChatUserModel();
            $e = $userModel->getChatUser($chat_user_uid);
        }

        if ($e) {
            $data = [
                "status" => "Success",
                "status_code" => 200,
                "status_message" => "User found",
                "user_data" => [
                    "chat_user_uid" => $e->chat_user_uid,
                    "user_type" => $e->user_type,
                    "user_name" => $e->user_name,
                    "user_avatar" => $e->user_avatar,
                    "created_at" => $e->created_at,
                    "updated_at" => $e->updated_at,
                ]
            ];
        } else {
            $data = [
                "status" => "Not Found",
                "status_code" => 404,
                "status_message" => "Error: No user found",
                "user_data" => []
            ];
        }

        //return $this->response->setJSON($data);
        return $this->getJsonWithCSRF($data);
    }


    public function getRandomChatUser($type = null)
    {
        $model = new \App\Models\ChatUserModel();
        $e = $model->getRandomChatUser($type);

        if ($e) {
            $data = [
                "status" => "Success",
                "status_code" => 200,
                "status_message" => "User found",
                "user_data" => [
                    "chat_user_uid" => $e->chat_user_uid,
                    "user_type" => $e->user_type,
                    "user_name" => $e->user_name,
                    "user_avatar" => $e->user_avatar,
                    "created_at" => $e->created_at,
                    "updated_at" => $e->updated_at,
                ]
            ];
        } else {
            $data = [
                "status" => "Not Found",
                "status_code" => 404,
                "status_message" => "Error: No user found",
                "user_data" => []
            ];
        }

        //return $this->response->setJSON($data);
        return $this->getJsonWithCSRF($data);
    }

    public function randomChatUser()
    {
        return $this->getRandomChatUser();
    }

    public function randomChatAttendee()
    {
        return $this->getRandomChatUser('attendee');
    }

    public function randomChatAttendant()
    {
        return $this->getRandomChatUser('attendant');
    }

    public function createChatSession($attendee_uid)
    {
        $messages = [];
        $r = null;
        if ($this->validateUuid($attendee_uid)) {
            $userModel = new \App\Models\ChatUserModel();
            $attendee = $userModel->getChatUser($attendee_uid);
            if ($attendee) {
                $attendee_uid = $attendee->chat_user_uid;
                $session = new \App\Models\ChatSessionModel();
                $r = $session->createChatSession($attendee_uid);
            } else {
                $messages[] = "Invalid attendee id";
            }
        }
        if ($r) {
            $messages[] = "Session created";
            $data = [
                "status" => "Success",
                "status_code" => 200,
                "status_message" => "Session created",
                "session_data" => [
                    "session_uid" => $r->session_uid,
                    "attendee_uid" => $r->attendee_uid,
                    "session_status" => $r->session_status,
                    "created_at" => $r->created_at,
                ]
            ];
        } else {
            $data = [
                "status" => "Bad Request",
                "status_code" => 400,
                "status_message" => $messages,
                "session_data" => []
            ];
        }
        //return $this->response->setJSON($data);
        return $this->getJsonWithCSRF($data);
    }

    public function getChatSessionById(string $session_uid)
    {
        $r = null;
        $session = null;
        $message = [];
        if ($this->validateUuid($session_uid)) {
            $sessionModel = new \App\Models\ChatSessionModel();
            $r = $sessionModel->getChatSession($session_uid);
            $message[] = "Session found";
        } else {
            $message[] = "Session ($session_uid) not found";
        }
        if ($r) {
            $data = [
                "status" => "Success",
                "status_code" => 200,
                "status_message" => $message,
                "session_data" => [
                    "session_uid" => $r->session_uid,
                    "attendee_uid" => $r->attendee_uid,
                    "attendant_uid" => $r->attendant_uid,
                    "session_status" => $r->session_status,
                    "created_at" => $r->created_at,
                    "updated_at" => $r->updated_at,
                ]
            ];
        } else {
            $data = [
                "status" => "Not Found",
                "status_code" => 404,
                "status_message" => $message,
                "session_data" => []
            ];
        }
        //return $this->response->setJSON($data);
        return $this->getJsonWithCSRF($data);
    }

    public function getChatSessionsByUser($chat_user_uid)
    {
        $e = null;

        $session = null;
        $message = [];

        if ($this->validateUuid($chat_user_uid)) {
            $sessionModel = new \App\Models\ChatSessionModel();
            $e = $sessionModel->getChatSessionsByUser($chat_user_uid);
        }

        if ($e) {
            $message[] = count($e) . " sessions found";
            $data = [
                "status" => "Success",
                "status_code" => 200,
                "status_message" => $message,
                "session_data" => $e
            ];
        } else {
            $message[] = "No sessions found for this user";
            $data = [
                "status" => "Not Found",
                "status_code" => 404,
                "status_message" => $message,
                "session_data" => []
            ];
        }
        //return $this->response->setJSON($data);
        return $this->getJsonWithCSRF($data);
    }


    public function getChatSessionsByStatus($session_status = null, string $order = null, int $limit = null)
    {
        $r = null;
        $session = null;
        $message = [];

        $sessionModel = new \App\Models\ChatSessionModel();
        $e = $sessionModel->getChatSessionsByStatus($session_status, $order, $limit);

        if ($e) {
            $message[] = count($e) . " sessions found";
            $data = [
                "status" => "Success",
                "status_code" => 200,
                "status_message" => $message,
                "session_data" => $e
            ];
        } else {
            $message[] = "No $session_status sessions found";
            $data = [
                "status" => "Not Found",
                "status_code" => 404,
                "status_message" => $message,
                "session_data" => []
            ];
        }
        //return $this->response->setJSON($data);
        return $this->getJsonWithCSRF($data);
    }


    public function getOpenChatSessions()
    {
        return $this->getChatSessionsByStatus("open");
    }

    public function getNewChatSessions()
    {
        return $this->getChatSessionsByStatus("new");
    }

    public function getClosedChatSessions()
    {
        return $this->getChatSessionsByStatus("closed");
    }

    public function getAllChatSessions()
    {
        return $this->getChatSessionsByStatus(["new", "open", "closed"]);
    }

    public function getChatSessionsDetailed(array $params)
    {
        $r = null;
        $session = null;
        $message = [];

        $sessionModel = new \App\Models\ChatSessionModel();
        $e = $sessionModel->getCompleteChatSessionsInfo($params);

        if ($e) {
            $message[] = "Sessions found";
            $data = [
                "status" => "Success",
                "status_code" => 200,
                "status_message" => $message,
                "session_data" => $e
            ];
        } else {
            $message[] = "No sessions found";
            $data = [
                "status" => "Not Found",
                "status_code" => 404,
                "status_message" => $message,
                "session_data" => []
            ];
        }
        //return $this->response->setJSON($data);
        return $this->getJsonWithCSRF($data);
    }

    public function getAllChatSessionsDetailed()
    {
        return $this->getChatSessionsDetailed([]);
    }

    public function getChatSessionsDetailedByAttendant($attendant_uid)
    {
        return $this->getChatSessionsDetailed(["attendant_uid" => $attendant_uid]);
    }


    public function openSession(string $session_uid = null, string $attendant_uid = null)
    {
        $r = null;
        $message = [];

        if (
            ($this->validateUuid($session_uid)) &&
            ($this->validateUuid($attendant_uid))
        ) {
            $sessionModel = new \App\Models\ChatSessionModel();
            $session = $sessionModel->getChatSessionById($session_uid);

            if ($session) {
                if ($session->session_status == "new") {
                    $userModel = new \App\Models\ChatUserModel();
                    $attendant = $userModel->getChatUser($attendant_uid);

                    if ($attendant) {
                        $session->attendant_uid = $attendant->chat_user_uid;
                        $session->updated_at = new Time('now');
                        $session->session_status = 'open';

                        $sessionModel->save($session);

                        $r = $session;

                        $message[] = "Session opened";
                    } else {
                        $message[] = "Error: Invalid session/user id";
                    }
                } else {
                    //$r = $session;

                    if ($session->session_status == "open") {
                        $message[] = "Error: Session ($session_uid) is already opened";
                    } elseif ($session->session_status == "closed") {
                        $message[] = "Error: Session ($session_uid) was closed";
                    }
                }
            } else {
                $message[] = "Error: Session ($session_uid) not found";
            }
        } else {
            $message[] = "Error: Invalid session/user id";
        }

        if ($r) {
            $data = [
                "status" => "Success",
                "status_code" => 200,
                "status_message" => $message,
                "session_data" => [
                    "session_uid" => $r->session_uid,
                    "attendee_uid" => $r->attendee_uid,
                    "attendant_uid" => $r->attendant_uid,
                    "session_status" => $r->session_status,
                    "created_at" => $r->created_at,
                    "updated_at" => $r->updated_at,
                ]
            ];
        } else {
            $data = [
                "status" => "Bad Request",
                "status_code" => 400,
                "status_message" => $message,
                "session_data" => []
            ];
        }
        //return $this->response->setJSON($data);
        return $this->getJsonWithCSRF($data);
    }


    public function confirmMessage($json)
    {
        if (
            $this->validateUuid($json->session_uid) &&
            $this->validateUuid($json->sender_uid) &&
            $this->validateUuid($json->message_uid)
        ) {
            $messageModel = new \App\Models\ChatMessageModel();
            $message = $messageModel->getMessage($json->message_uid);

            if ($message->session_uid == $json->session_uid && $message->sender_uid == $json->sender_uid) {
                $message->sync_status = "confirmed";
                $message->confirmed_at = new Time('now');
                $messageModel->save($message);
            }
        }
    }

    public function confirmReadMessage($json)
    {
        if (
            $this->validateUuid($json->session_uid) &&
            $this->validateUuid($json->sender_uid) &&
            $this->validateUuid($json->message_uid)
        ) {
            $messageModel = new \App\Models\ChatMessageModel();
            $message = $messageModel->getMessage($json->message_uid);

            if ($message->session_uid == $json->session_uid && $message->sender_uid == $json->sender_uid) {
                $message->sync_status = "done";
                //$message->confirmed_at = new Time('now');
                $messageModel->save($message);
            }
        }
    }

    public function readMessage($json)
    {
        if (
            $this->validateUuid($json->reader_uid) &&
            $this->validateUuid($json->message_uid)
        ) {
            $messageModel = new \App\Models\ChatMessageModel();
            $message = $messageModel->getMessage($json->message_uid);

            // TODO At this time the is only one reader, but probably can have more if create groups

            if ($message->sender_uid != $json->reader_uid) {
                $message->sync_status = "read";
                $message->read_at = new Time('now');
                $messageModel->save($message);
            }
        }
    }


    public function storeMessage($json)
    {
        $data = null;
        $errors = [];

        if (
            $this->validateUuid($json->session_uid) &&
            $this->validateUuid($json->sender_uid)
        ) {
            $sessionModel = new \App\Models\ChatSessionModel();
            $session = $sessionModel->getChatSessionById($json->session_uid);

            $userModel = new \App\Models\ChatUserModel();
            $sender = $userModel->getChatUser($json->sender_uid);

            if ($session && $sender) {
                //if (in_array($json->sender_uid, [$session->attendee_uid, $session->attendant_uid])) {
                    $message_uid = $this->getNewUUidString();

                    $messageModel = new \App\Models\ChatMessageModel();
                    $e = new \App\Entities\ChatMessageEntity();
                    $e->message_uid = $message_uid;
                    $e->message = $json->message;
                    $e->session_uid = $json->session_uid;
                    $e->sender_uid = $json->sender_uid;
                    $e->sync_status = "stored";

                    $messageModel->insert($e, true);
                    $e = $messageModel->find($messageModel->getInsertID());

                    $data = [
                        "status" => "Success",
                        "status_code" => 200,
                        "status_message" => "Message sent",
                        "message_uid" => $e->message_uid,
                        "client_message_id" => $json->client_message_id,
                        "sync_status" => $e->sync_status,
                    ];
                //} else {
                //    $errors[] = "Error: Sender does not participate of this session";
                //}
            } else {
                $errors[] = "Error: Invalid session or sender Id";
            }
        } else {
            $errors[] = "Error: Invalid session or sender Id";
        }

        if (count($errors)) {
            $data = [
                "status" => "Bad Request",
                "status_code" => 400,
                "status_message" => $errors,
                "user_data" => []
            ];
        }

        return $data;
    }


    public function sync()
    {
        // TODO Create a disconnect timer based in the last message of user to close the chat session
        // TODO Create a warning to customer if attendand stops answering

        $post = $this->getRequest()->getPost();
        $body = $this->getRequest()->getBody();
        $json = $this->getRequest()->getJSON();

        $sent_messages = [];
        $errors = [];
        $init = false;
        $result = [];

        // Process Messages
        foreach ($json->data as $key => $value) {
            if (property_exists($value, "action")) {
                switch ($value->action) {
                    case "ping":
                        break;
                    case "store-message":
                        $message = $this->storeMessage($value->data);
                        if ($message["status_code"] == 200) {
                            $result[] = ["stored" => $message];
                        } else {
                            $errors[] = [
                                "request" => $value,
                                "result" => $message
                            ];
                        }
                        break;

                    case "confirm-message":
                        $message = $this->confirmMessage($value->data);
                        break;

                    case "read-message":
                        $message = $this->readMessage($value->data);
                        break;

                    case "confirm-read-message":
                        $message = $this->confirmReadMessage($value->data);
                        break;

                    case "sessions":
                        $sessionModel = new \App\Models\ChatSessionModel();
                        $sessions = $sessionModel->getCompleteChatSessionsInfo(
                            ["attendant_uid" => $value->user_sessions]
                        );

                        if ($sessions) {
                            $result[] = ["sessions" => $sessions];
                        }
                        break;

                    case "init":
                        $sessionModel = new \App\Models\ChatSessionModel();
                        $session = $sessionModel->getChatSessionById($value->current_suid);
                        $sessionDetails = null;
                        if ($session) {
                            if (!$session->attendant_uid) {
                                if (property_exists($json, "current_uuid")) {
                                    $userModel = new \App\Models\ChatUserModel();
                                    $attendant = $userModel->getChatUser($json->current_uuid, "attendant");

                                    if ($attendant) {
                                        $session->attendant_uid = $json->current_suid;
                                        $session->session_status = "open";
                                        $sessionModel->save($session);

                                        $sessionDetails = $sessionModel->getCompleteChatSessionsInfo(
                                            ["attendant_uid" => $json->current_suid]
                                        );
                                    }
                                }
                            }

                            $sessionDetails = $sessionDetails ?? $sessionModel->getCompleteChatSessionsInfo(
                                ["session_uid" => $session->session_uid]
                            );

                            $messageModel = new \App\Models\ChatMessageModel();
                            $messages =  $messageModel->getMessagesBySession($session->session_uid);

                            $result[] = [
                                "init" => [
                                    "messages" => $messages,
                                    "session"  => $sessionDetails[0],
                                ]
                            ];
                            $init = true;
                            break;
                        }
                }

                if (!$init) {
                    if (property_exists($json, "current_uuid")) {
                        $messageModel = new \App\Models\ChatMessageModel();
                        $e = $messageModel->getUnreadMessagesByUser($json->current_uuid, $json->current_suid);

                        if ($e) {
                            foreach ($e as $key => $item) {
                                if ($item->sync_status == "read") {
                                    $result[] = [
                                        "read" => ["message_uid" => $item->message_uid]
                                    ];
                                } else {
                                    $result[] = [
                                        "new" => $item
                                    ];
                                }
                            }
                        }
                    }
                }
            } else {
                $errors[] = "No action informed";
            }

            if (count($errors)) {
                $result[] = ["errors" => $errors];
            }

            $now = new \DateTime();

            $data = [
                "timestamp" => $now->getTimestamp(),
                "data" => $result
            ];


            //return $this->response->setJSON($data);
            return $this->getJsonWithCSRF($data);
        }
    }
}
