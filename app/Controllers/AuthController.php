<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use \App\Helpers\CustomHelper;
use Exception;
use Ramsey\Uuid\Nonstandard\UuidV6;
use CodeIgniter\I18n\Time;
use CodeIgniter\Cookie\Cookie;
use DateTime;

class AuthController extends BaseController
{
	public function index()
	{
		return redirect()->to('/admin/login');
	}

	protected function getCookieLoginInfo(string $key, string $section = null, string $provider = null)
	{
		$doGetCookie = function ($key) {
			$doUnserialize = function ($cookie) {
				$unserialized = @unserialize($cookie);
				if ($unserialized === 'b:0;' || $unserialized !== false) {
					return $unserialized;
				} else {
					return $cookie;
				}
			};


			$cookie = get_cookie($key);
			return $doUnserialize($cookie);
		};

		if (isset($section)) {
			if (isset($provider)) {
				return $doGetCookie('app_auth_' . $section . '_' . $provider . "_" . $key);
			} else return $doGetCookie('app_auth_' . $section . '_' . $key);
		} else return $doGetCookie('app_auth_' . $key);
	}

	protected function setCookieLoginInfo($value, string $section = null, string $provider = null, string $key = null)
	{
		$doSetCookie = function ($key, $value) {
			return cookie($key, serialize($value));
		};

		if (isset($section)) {
			if (isset($provider)) {
				if (isset($key)) {
					return $doSetCookie('app_auth_' . $section . '_' . $provider . "_" . $key, $value);
				} else return $doSetCookie('app_auth_' . $section . '_' . $provider, $value);
			} else return $doSetCookie('app_auth_' . $section, $value);
		} else return $doSetCookie('app_auth', $value);
	}

	protected function clearCookieLoginInfo(string $section = null, string $provider = null)
	{
		return $this->setCookieLoginInfo(null, $section, $provider);
	}

	protected function getSessionLoginInfo(string $key, string $section = null, string $provider = null)
	{
		if (isset($section)) {
			if (isset($provider)) {
				return $_SESSION["auth"][$section][$provider][$key] ?? null;
			} else return $_SESSION["auth"][$section][$key] ?? null;
		} else return $_SESSION["auth"][$key];
	}

	protected function setSessionLoginInfo($value, string $section = null, string $provider = null, string $key = null)
	{
		if (isset($section)) {
			if (isset($provider)) {
				if (isset($key)) {
					$_SESSION["auth"][$section][$provider][$key] = $value;
				} else $_SESSION["auth"][$section][$provider] = $value;
			} else $_SESSION["auth"][$section] = $value;
		} else $_SESSION["auth"] = $value;
		return true;
	}

	public function checkSessionLoggedOn(string $section, string $provider)
	{
		if (isset($section)) {
			if (isset($provider)) {
				return $this->getSessionLoginInfo("connected", $section, $provider);
			}
		}

		return false;
	}

	protected function clearSessionLoginInfo(string $section = null, string $provider = null)
	{
		return $this->setSessionLoginInfo(null, $section, $provider);
	}

	protected function getGoogleLoginData(string $section = "default", array $params = ["force_renew" => false, "redir_url" => "/google/login", "client_id" => "", "client_secret" => ""])
	{
		$doGetGoogleAuthUrl = function (\Google\Client $client, string $section) {
			$this->clearSessionLoginInfo("google");
			$stateCheck = "xchk" . $this->getNewUUidString();
			$this->setSessionLoginInfo($stateCheck, $section, "google", "state_check");
			$client->setState($stateCheck);
			$AuthUrl = $client->createAuthUrl();
			$this->setSessionLoginInfo($AuthUrl, $section, "google", "auth_url");
		};

		$doGetUserData = function (\Google\Client $client, string $section) {
			$service = new \Google_Service_Oauth2($client);
			$userinfo = $service->userinfo_v2_me->get();
			$this->setSessionLoginInfo($userinfo, $section, "google", "user");
			$this->setSessionLoginInfo(true, $section, "google", "connected");
			$this->setSessionLoginInfo(true, $section, "connected");
			$token = $client->getAccessToken();
			$this->setSessionLoginInfo($token, $section, "google", "token");
			$this->setCookieLoginInfo($token, $section, "google", "token");
			return $userinfo;
		};

		$cookieToken = null;

		$paramsDefault = ["redir_url" => "/google/login", "client_id" => "", "client_secret" => ""];

		$p = CustomHelper::array_merge_recursive_distinct($paramsDefault, $params);

		if ($p["client_id"] == "" || $p["client_secret"] == "") {
			throw new Exception("No required credentials informed in \$params parameter");
		}

		$client = new \Google\Client();
		$client->setClientId($p["client_id"]);
		$client->setClientSecret($p["client_secret"]);
		$client->setRedirectUri($params["redir_url"]);
		$client->addScope("email");
		$client->addScope("profile");
		$client->setAccessType('offline');
		$client->setPrompt("consent");
		$service = new \Google_Service_Oauth2($client);

		$sessionToken = $this->getSessionLoginInfo("token", $section, "google");
		$cookieToken = $this->getCookieLoginInfo("token", $section, "google");

		$client->setAccessToken($sessionToken ?? $cookieToken ?? "");

		$token  = null;

		if ($this->getRequestParam("code")) {

			$stateCheck = $this->getSessionLoginInfo("state_check", $section, "google");
			$stateCheckURL = $this->getRequestParam("state");

			if ($stateCheckURL != $stateCheck) {
				$this->clearSessionLoginInfo($section, "google");
				redirect()->to("/error/wronghandshake");
			}

			$token = $client->fetchAccessTokenWithAuthCode($this->getRequestParam("code"));

			if (!isset($token['error'])) {
				// set session

				$userinfo = $doGetUserData($client, $section);
			} else {

				$AuthUrl = $doGetGoogleAuthUrl($client, $section);
			}
		} else {

			if (!isset($sessionToken) && !isset($sessionToken["refresh_token"]) && !isset($cookieToken)) {

				$token = null;

				$AuthUrl = $doGetGoogleAuthUrl($client, $section);
			} else {

				if ($client->isAccessTokenExpired()) {

					$refreshBySession = $client->fetchAccessTokenWithRefreshToken($sessionToken);

					$refreshByCookie = $client->fetchAccessTokenWithRefreshToken($cookieToken);
					$client->fetchAccessTokenWithRefreshToken($cookieToken);

					if (!isset($refreshBySession['error']) || !isset($refreshByCookie['error'])) {
						// set session
						$userinfo = $doGetUserData($client, $section);
					} else {
						$AuthUrl = $doGetGoogleAuthUrl($client, $section);
					}
				} else {
					$userinfo = $doGetUserData($client, $section);
				}
			}
		}

		return $this->getSessionLoginInfo("google", $section);
	}



	public function AttendeeChatLogin()
	{

		$data = null;
		$section = "attendee_chat";
		$googleData = $this->getGoogleLoginData($section, [
			"force_renew" => false,
			"redir_url" => getenv("app.baseURL") . "/chat/login",
			//"client_id" => "718315869853-2cma32pj46u5jtj54movvktpdmkiivie.apps.googleusercontent.com",
			"client_id" =>  getenv("app.chat.google.client_id"),
			"client_secret" => getenv("app.chat.google.client_secret"),
		]);


		if ($this->checkSessionLoggedOn($section, "google")) {

			$model = new \App\Models\ChatUserModel();
			$builder = $model->builder();
			$user = $this->getSessionLoginInfo("user", $section, "google");
			$token = $this->getSessionLoginInfo("token", $section, "google");

			$builder->where("google_email", $user["email"]);
			$builder->orWhere("facebook_email", $user["email"]);
			$r = $builder->get(1)->getResult("\App\Entities\ChatUserEntity");

			if ($r) {

				$e = $r[0];

				if ($user["givenName"] . " " . $user["familyName"]) {

					$e->google_avatar = $user["picture"];
					$e->google_token = serialize($token);
					$e->updated_at = new Time('now');
					$model->save($e);
				}
			} else {
				$e = new \App\Entities\ChatUserEntity();

				$e->chat_user_uid = $this->getNewUUidString();
				$e->user_type = "attendee";
				$e->google_token = serialize($token);
				$e->google_email = $user["email"];
				$e->google_avatar = $user["picture"];
				$e->user_avatar = $user["picture"];
				$e->user_name =  $user["givenName"] . " " . $user["familyName"];
				$e->google_name =  $user["givenName"] . " " . $user["familyName"];
				$e->is_guest = "N";
				$model->insert($e);
			}

			return redirect()->to("/chat/front/" . $e->chat_user_uid);
		} else {

			$data = [
				"layout" => "layouts/layout_bootstrap_clear_noresize",
				"google" => $googleData,
			];

			return $this->view("content/chat/chat_attendee_login_view",  $data);
		}
	}

	public function logoff()
	{
		//
		return "<h1>Logoff</h1>";
	}



	public function getAdmLogin()
	{

		$email = $this->getCookieLoginInfo("email", "admin", "local");

		$data = [
			"saved_email" => $email,
			"layout" => "layouts/layout_adminlte_clear",
			"add_body_class" => "login-page"
		];

		return $this->view("content/admin/login_view", $data);
	}

	public function postAdmLogin()
	{
		// helper('cookie');
		$email = stripslashes(htmlspecialchars(trim($this->getRequestParam("email"))));
		$password = $this->getRequestParam("password");

		$userModel = new \App\Models\UserModel();
		$user = $userModel->doLogin($email, $password);
		
		if (isset($user)) {
			$workerModel = new \App\Models\WorkerModel();
			$worker = $workerModel->getWorker($user->worker_uid);
			$user->worker = $worker;
			$this->setSessionLoginInfo(true, "admin", "local", "connected");
			$this->setSessionLoginInfo(true, "admin", "connected");
			$this->setSessionLoginInfo($user, "admin", "local", "user");
			$this->setSessionLoginInfo($email, "admin", "local", "email");
			$cookie = $this->setCookieLoginInfo($email, "admin", "local", "email");
		
			return redirect()->to("/admin");
		} else {
			return redirect()->to("/admin/login");
		}
	}
}
