<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use \App\Helpers\CustomHelper;
use Exception;
use Ramsey\Uuid\Nonstandard\UuidV6;
use CodeIgniter\I18n\Time;
use CodeIgniter\Cookie\Cookie;
use DateTime;
use \Facebook;

class AuthController extends BaseController
{

	protected $allowedProviders = ["google"];

	public function index()
	{
		return redirect()->to('/land');
	}

	protected function getFacebookLoginData(string $section = "default", array $params = [])
	{

		$paramsDefault = ["redir_url" => "/facebook/login", "client_id" => "", "client_secret" => ""];

		$p = CustomHelper::array_merge_recursive_distinct($paramsDefault, $params);

		if ($p["client_id"] == "" || $p["client_secret"] == "") {
			throw new Exception("No credentials informed in \$params parameter");
		}

		

		$stateCheck = $this->getSessionLoginInfo("state_check", $section, "google");

		if(!isset($stateCheck)) {
			$stateCheck = "xchk" . $this->getNewUUidString();
			$this->setSessionLoginInfo($stateCheck, $section, "facebook", "state_check");
		}



		$fb = new \Facebook\Facebook([
			'app_id' => $p["client_id"],
			'app_secret' => $p["client_secret"],
			'default_graph_version' => 'v2.10',
			'state' => $stateCheck
		]);

		$helper = $fb->getRedirectLoginHelper();

		$errors = [];
		$token = null;
		$refreshToken = null;

		if ($this->getRequestParam("state")) {

			$_SESSION['FBRLH_state'] = $_GET['state'];

			$stateCheckURL = $this->getRequestParam("state");
			$stateCheck = $this->getSessionLoginInfo("state_check", $section, "google");

			try {
				$token = $helper->getAccessToken();
				$refreshToken = $token->getValue();

				$this->setSessionLoginInfo($token, $section, "facebook", "token");
				$this->setSessionLoginInfo($refreshToken, $section, "facebook", "refresh_token");

				try {
					$userinfo = $fb->get('/me', $token->getValue());

					$this->setSessionLoginInfo($userinfo, $section, "facebook", "user");
					$this->setSessionLoginInfo(true, $section, "facebook", "connected");
					$this->setSessionLoginInfo(true, $section, "connected");

				} catch (Facebook\Exceptions\FacebookResponseException $e) {
					$errors[] = ["Graph" =>  $e->getMessage()];
					exit;
				} catch (Facebook\Exceptions\FacebookSDKException $e) {
					$errors[] = ["SDK" => $e->getMessage()];
					exit;
				}
			} catch (Facebook\Exceptions\FacebookResponseException $e) {
				$errors[] = ["Graph" =>  $e->getMessage()];
				exit;
			} catch (Facebook\Exceptions\FacebookSDKException $e) {
				$errors[] = ["SDK" => $e->getMessage()];
				exit;
			}
		}

		if (!isset($token)) {

			if ($helper->getError()) {

				$error["Error"] = $helper->getError();
				$error["Error Code"] = $helper->getErrorCode();
				$error["Error Reason"] = $helper->getErrorReason();
				$error["Error Description"] = $helper->getErrorDescription();
				$errors[] = $error;
			}

			$permissions = []; // Permissões Opcionais
			//$permissions = ['email']; // Permissões Opcionais
			$AuthUrl = $helper->getLoginUrl($p["redir_url"], $permissions); //Url de retorno onde iremos ter a validação do login. O domínio deverá ser configurado no seu app.
			$this->setSessionLoginInfo($AuthUrl, $section, "facebook", "auth_url");
		}

		return $this->getSessionLoginInfo("facebook", $section);
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
			throw new Exception("No credentials informed in \$params parameter");
		}

		$client = new \Google\Client();
		$client->setClientId($p["client_id"]);
		$client->setClientSecret($p["client_secret"]);
		$client->setRedirectUri($p["redir_url"]);
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



	public function AttendeeLogin($key)
	{

		if (in_array($key, ["chat", "schedule"]) || in_array($key, $this->allowedProviders)) {

			$provider = null;
			$data = null;
			$section = "attendee";
			$service = null;

			if (in_array($key, ["chat", "schedule"])) {
				$this->setSessionLoginInfo($key, "service");
				$service = $key;
			} else {
				$service = $this->getSessionLoginInfo("service");
			}

			if (in_array($key, $this->allowedProviders)) {
				$this->setSessionLoginInfo($key, "provider");
				$provider = $key;
			} else {
				$provider = $this->getSessionLoginInfo("provider") ?? ($this->getSessionLoginInfo("connected", $section, "google")) ? "google" : (($this->getSessionLoginInfo("connected", $section, "facebook")) ? "facebook" : null);
				$this->setSessionLoginInfo($provider, "provider");
			}

			$googleData = $this->getGoogleLoginData($section, [
				"force_renew" => false,
				"redir_url" => getenv("app.baseURL") . "attendee/login/google",
				"client_id" =>  getenv("app.google.client_id"),
				"client_secret" => getenv("app.google.client_secret"),
			]);

			$facebookData = $this->getFacebookLoginData($section, [
				"force_renew" => false,
				"redir_url" => getenv("app.baseURL") . "attendee/login/facebook",
				"client_id" =>  getenv("app.facebook.client_id"),
				"client_secret" => getenv("app.facebook.client_secret"),
			]);

			if (isset($provider) && $this->checkSessionLoggedOn($section, $provider)) {


				// register user

				$user = $this->getSessionLoginInfo("user", $section, $provider);
				$token = $this->getSessionLoginInfo("token", $section, $provider);

				$model = new \App\Models\ChatUserModel();
				$builder = $model->builder();

				$builder->where($provider . "_email", $user["email"]);
				$r = $builder->get(1)->getResult("\App\Entities\ChatUserEntity");

				if ($r) {

					$e = $r[0];

					if ($user["givenName"] . " " . $user["familyName"]) {

						$e->{$provider . "_avatar"} = $user["picture"];
						$e->{$provider . "_token"} = serialize($token);
						$e->updated_at = new Time('now');
						$model->save($e);
					}
				} else {
					$e = new \App\Entities\ChatUserEntity();

					$e->chat_user_uid = $this->getNewUUidString();
					$e->user_type = "attendee";
					$e->{$provider . "_email"} = $user["email"];
					$e->{$provider . "_name"} =  $user["givenName"] . " " . $user["familyName"];
					$e->{$provider . "_token"} = serialize($token);
					$e->{$provider . "_avatar"} = $user["picture"];
					$e->user_avatar = $user["picture"];
					$e->user_name =  $user["givenName"] . " " . $user["familyName"];
					$e->is_guest = "N";
					$model->insert($e);
				}

				$this->setSessionLoginInfo($e, $section, "user");






				switch ($service) {
					case "chat":
						return redirect()->to("/attendee/chat/identified");
						break;
					case "schedule":
						return redirect()->to("/attendee/schedule/identified");
						break;
					default:
						return redirect()->to("/land");
						break;
				}
			} else {

				$data = [
					"pageTitle" => "Login",
					"layout" => "layouts/layout_bootstrap_clear_noresize",
					"google" => $googleData,
					"facebook" => $facebookData,
				];

				return $this->view("content/chat/chat_attendee_login_view",  $data);
			}
		} else return redirect()->to("/land");
	}

	public function getAtendeeLogout()
	{

		$this->setSessionLoginInfo(null, "attendee");

		return redirect()->to("/land");
	}



	public function getAdmLogin()
	{

		$email = $this->getCookieLoginInfo("email", "admin", "local");

		$data = [
			"saved_email" => $email,
			"pageTitle" => "Login",
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
