<?php

namespace gplusFlickr\flickr;

use Nette\Http\Url,
	Nette\Http\Session;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class Auth {
	/** @var Api */
	private $api;
	/** @var Session */
	private $session;

	/** @var string */
	private static $authorizeUrl = 'https://www.flickr.com/services/oauth/authorize';
	/** @var string */
	private static $requestTokenUrl = 'oauth/request_token';
	/** @var string */
	private static $accessTokenUrl = 'oauth/access_token';

	public function __construct(
		Api $api,
		Session $session
	) {
		$this->api = $api;
		$this->session = $session;
	}

	/**
	 * Initialize
	 */
	public function init() {
		if ($token = $this->getAccessToken()) {
			$this->api->setAccessToken($token);
		}
	}

	/**
	 * @return string
	 */
	public function getAuthUrl() {
		if ($token = $this->requestToken(self::$requestTokenUrl)) {
			$this->saveRequestToken($token);
			$property = 'oauth_token';
			// url z api ?
			$url = new Url(self::$authorizeUrl);
			$url->setQueryParameter($property, $token[$property])
				->setQueryParameter('perms', 'write');
			return $url->getAbsoluteUrl();
		}
	}

	/**
	 * Authenticates with query from flickr
	 *
	 * @param array $query
	 * @return bool
	 */
	public function authenticate(array $query) {
		if (($token = $query['oauth_token']) && ($verifier = $query['oauth_verifier'])) {
			// we need token secret
			$requestToken = $this->getRequestToken();
			if ($requestToken['oauth_token'] === $token) {
				$accessToken = $this->requestToken(
					self::$accessTokenUrl, $token, $requestToken['oauth_token_secret'], $verifier
				);

				if ($accessToken) {
					$this->saveAccessToken($accessToken);
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * @return bool
	 */
	public function isAuthenticated() {
		return !!$this->getAccessToken();
	}

	/**
	 * Logouts user from flickr
	 */
	public function logout() {
		$this->removeAccessToken();
	}

	/**
	 * @return int
	 */
	public function getUserId() {
		if ($token = $this->getAccessToken()) {
			return $token['user_nsid'];
		}
	}

	/**
	 * Send request for token
	 *
	 * @param string $url
	 * @param string $tokenKey
	 * @param string $tokenSecret
	 * @param string $verifier
	 * @return array
	 */
	private function requestToken($url, $tokenKey = null, $tokenSecret = null, $verifier = null) {
		$client = $this->api->getClient()->setToken($tokenKey)
			->setTokenSecret($tokenSecret)
			->setVerifier($verifier);

		$tokenString = $client->call($url)->getBody()->getContents();

		if ($tokenString) {
			$token = [];
			parse_str($tokenString, $token);
			return $token;
		}
	}

	/**
	 * @return \Nette\Http\SessionSection
	 */
	private function getSession() {
		return $this->session->getSection('flickr');
	}

	private function saveRequestToken(array $token) {
		$session = $this->getSession();
		$session->requestToken = $token;
	}

	/**
	 * @return array
	 */
	private function getRequestToken() {
		$session = $this->getSession();
		if (isset($session->requestToken)) {
			return $session->requestToken;
		}
	}

	private function saveAccessToken(array $token) {
		$session = $this->getSession();
		$session->accessToken = $token;
		$this->api->setAccessToken($token);
	}

	/**
	 * @return array
	 */
	private function getAccessToken() {
		$session = $this->getSession();
		if (isset($session->accessToken)) {
			return $session->accessToken;
		}
	}

	private function removeAccessToken() {
		$this->api->removeAccessToken();
		$session = $this->getSession();
		unset($session->accessToken);
	}
}
