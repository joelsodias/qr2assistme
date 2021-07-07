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
use GuzzleHttp\Client;


class Guzzle6HttpClient implements Facebook\HttpClients\FacebookHttpClientInterface
{
	private $client;

	public function __construct(\GuzzleHttp\Client $client)
	{
		$this->client = $client;
	}

	public function send($url, $method, $body, array $headers, $timeOut)
	{
		$request = new \GuzzleHttp\Psr7\Request($method, $url, $headers, $body);
		try {
			$response = $this->client->send($request, ['timeout' => $timeOut, 'http_errors' => false]);
		} catch (\GuzzleHttp\Exception\RequestException $e) {
			throw new Facebook\Exceptions\FacebookSDKException($e->getMessage(), $e->getCode());
		}
		$httpStatusCode = $response->getStatusCode();
		$responseHeaders = $response->getHeaders();

		foreach ($responseHeaders as $key => $values) {
			$responseHeaders[$key] = implode(', ', $values);
		}

		$responseBody = $response->getBody()->getContents();

		return new Facebook\Http\GraphRawResponse(
			$responseHeaders,
			$responseBody,
			$httpStatusCode
		);
	}
}


class AuthController extends BaseController
{

	protected $allowedProviders = ["google", "facebook"];
	protected $allowedServices = ["chat", "schedule"];

	public function index()
	{
		return redirect()->to('/land');
	}

	protected function getFacebookLoginData(string $current_provider = null, string $section = "default", array $params = [])
	{

		$paramsDefault = ["redir_url" => "/facebook/login", "client_id" => "", "client_secret" => ""];

		$p = CustomHelper::array_merge_recursive_distinct($paramsDefault, $params);

		if ($p["client_id"] == "" || $p["client_secret"] == "") {
			throw new Exception("No credentials informed in \$params parameter");
		}



		$stateCheck = $this->getSessionLoginInfo("state_check", $section, "facebook");

		if (!isset($stateCheck)) {
			$stateCheck = "xchk" . $this->getNewUUidString();
			$this->setSessionLoginInfo($stateCheck, $section, "facebook", "state_check");
		}

		$client = new \GuzzleHttp\Client;

		$fb = new \Facebook\Facebook([
			'app_id' => $p["client_id"],
			'app_secret' => $p["client_secret"],
			'http_client_handler' => new Guzzle6HttpClient($client),
			//'default_graph_version' => 'v2.10',
			//'default_graph_version' => 'v5',
			//'state' => $stateCheck
		]);

		$helper = $fb->getRedirectLoginHelper();

		$errors = [];
		$token = null;
		$refreshToken = null;

		if ((!isset($current_provider) || $current_provider == "facebook") && $this->getRequestParam("state")) {

			$_SESSION['FBRLH_state'] = $_GET['state'];

			$stateCheckURL = $this->getRequestParam("state");
			$stateCheck = $this->getSessionLoginInfo("state_check", $section, "google");

			try {
				$token = $helper->getAccessToken();
				$refreshToken = $token->getValue();

				$this->setSessionLoginInfo($token, $section, "facebook", "token");
				$this->setSessionLoginInfo($refreshToken, $section, "facebook", "refresh_token");

				try {

					$response = $fb->get('/me?fields=id,name,email,first_name,last_name,picture', $refreshToken);
					$userinfo = $response->getGraphUser();
					$user["raw"]["response"] = $response;
					$user["raw"]["GraphUser"] = $response->getGraphUser();
					$user["email"] = $userinfo["email"];
					$user["name"] = $userinfo["name"];
					$user["first_name"] = $userinfo["first_name"];
					$user["last_name"] = $userinfo["last_name"];
					$user["avatar"] = $userinfo["picture"]->getUrl();



					$this->setSessionLoginInfo($user, $section, "facebook", "user");
					$this->setSessionLoginInfo(true, $section, "facebook", "connected");
					$this->setSessionLoginInfo(true, $section, "connected");
				} catch (Facebook\Exceptions\FacebookResponseException $e) {
					$errors[] = ["Graph" =>  $e->getMessage()];
					redirect()->to("/land");

				} catch (Facebook\Exceptions\FacebookSDKException $e) {
					$errors[] = ["SDK" => $e->getMessage()];
					redirect()->to("/land");

				}
			} catch (Facebook\Exceptions\FacebookResponseException $e) {
				$errors[] = ["Graph" =>  $e->getMessage()];
				redirect()->to("/land");

			} catch (Facebook\Exceptions\FacebookSDKException $e) {
				$errors[] = ["SDK" => $e->getMessage()];
				redirect()->to("/land");

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

			//$permissions = []; // Permissões Opcionais
			$permissions = ['email']; // Permissões Opcionais
			$AuthUrl = $helper->getLoginUrl($p["redir_url"], $permissions); //Url de retorno onde iremos ter a validação do login. O domínio deverá ser configurado no seu app.
			$this->setSessionLoginInfo($AuthUrl, $section, "facebook", "auth_url");
		}

		return $this->getSessionLoginInfo("facebook", $section);
	}

	protected function getGoogleLoginData(string $current_provider = null, string $section = "default", array $params = ["force_renew" => false, "redir_url" => "/google/login", "client_id" => "", "client_secret" => ""])
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

			$user["raw"]["userinfo"] = $userinfo;
			$user["email"] = $userinfo["email"];
			$user["name"] = $userinfo["name"];
			$user["first_name"] = $userinfo["givenName"];
			$user["last_name"] = $userinfo["familyName"];
			$user["avatar"] = $userinfo["picture"];
			$user["avatar2"] = null;



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

		if ((!isset($current_provider) || $current_provider == "google") && $this->getRequestParam("code")) {

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

		$provider = null;
		$data = null;
		$section = "attendee";
		$service = null;

		$service = $this->getSessionLoginInfo("service");
		$provider = $this->getSessionLoginInfo("provider");

		if (in_array($key, $this->allowedServices) || in_array($key, $this->allowedProviders)) {


			if (in_array($key, $this->allowedServices)) {
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

			//if ($provider == null || $provider == "google") {
			$googleData = $this->getGoogleLoginData($provider, $section, [
				"force_renew" => false,
				"redir_url" => getenv("app.baseURL") . $section . "/login/google",
				"client_id" =>  getenv("app.google.client_id"),
				"client_secret" => getenv("app.google.client_secret"),
			]);
			//}

			//if ($provider == null || $provider == "facebook") {
			$facebookData = $this->getFacebookLoginData($provider, $section, [
				"force_renew" => false,
				"redir_url" => getenv("app.baseURL") . $section . "/login/facebook",
				"client_id" =>  getenv("app.facebook.client_id"),
				"client_secret" => getenv("app.facebook.client_secret"),
			]);
			//}

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

				

						$e->{$provider . "_avatar"} = $user["avatar"];
						$e->{$provider . "_token"} = serialize($token);
						$e->user_avatar = $user["avatar"];
						$e->user_name =  $user["name"] ;
						$e->updated_at = new Time('now');
						$model->save($e);
				
				} else {
					$e = new \App\Entities\ChatUserEntity();

					$e->chat_user_uid = $this->getNewUUidString();
					$e->user_type = "attendee";
					$e->{$provider . "_email"} = $user["email"];
					$e->{$provider . "_name"} =  $user["name"] ;
					$e->{$provider . "_token"} = serialize($token);
					$e->{$provider . "_avatar"} = $user["avatar"];
					$e->user_avatar = $user["avatar"];
					$e->user_name =  $user["name"] ;
					$e->is_guest = "N";
					$model->insert($e);
				}

				$this->setSessionLoginInfo($e, $section, "user");






				switch ($service) {
					case "chat":
						return redirect()->to("/" . $section . "/chat/identified");
						break;
					case "schedule":
						return redirect()->to("/" . $section . "/schedule/identified");
						break;
					default:
						return redirect()->to("/land");
						break;
				}
			} else {

				$data = [
					"page_title" => "Login",
					"layout" => "layouts/layout_bootstrap_clear_noresize",
					"service" => $service,
					"google" => $googleData,
					"facebook" => $facebookData,
				];

				return $this->view("content/chat/chat_attendee_login_view",  $data);
			}
		} elseif (isset($_GET["error_code"])) {
			if (isset($service)) {
				return redirect()->to("/attendee/login/" . $service);
			}
		}

		return redirect()->to("/land");
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
			"page_title" => "Login",
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

	public function redirectProviderLogin($service, $provider, $auth_uri_param)
	{

		if (in_array($service, $this->allowedServices)) {
			$this->setSessionLoginInfo($service, "service");
		} else return redirect()->to("/land");

		if (in_array($provider, $this->allowedProviders)) {
			$this->setSessionLoginInfo($provider, "provider");
		} else return redirect()->to("/land");

		$session_auth_url = $this->getSessionLoginInfo("auth_url", "attendee", $provider);
		$auth_uri = base64_decode(str_replace(array('-', '_'), array('+', '/'), $auth_uri_param));


		if ($auth_uri == $session_auth_url) {
			return redirect()->to($session_auth_url);
		} else return redirect()->to("/land");
	}
}
