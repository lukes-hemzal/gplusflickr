<?php

namespace gplusFlickr\google;

use gplusFlickr\oauth\ClientFactory,
	Nette\Application\LinkGenerator,
	Nette\Http\Session;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class Api {
	/** @var string */
	private $id;
	/** @var string */
	private $secret;
	/** @var LinkGenerator */
	private $generator;
	/** @var Session */
	private $session;
	/** @var ClientFactory */
	private $clientFactory;

	/** @var \Google_Client */
	private $client;

	public function __construct(
		$id, $secret,
		LinkGenerator $generator,
		Session $session,
		ClientFactory $clientFactory
	) {
		$this->id = $id;
		$this->secret = $secret;
		$this->generator = $generator;
		$this->session = $session;
		$this->clientFactory = $clientFactory;
	}

	/**
	 * @return string
	 */
	public function getAuthUrl() {
		return $this->getClient()->createAuthUrl();
	}

	/**
	 * @return bool
	 */
	public function isAuthenticated() {
		return !$this->getClient()->isAccessTokenExpired();
	}

	/**
	 * @return string
	 */
	public function getUserId() {
		if ($person = $this->getPlus()->people->get('me')) {
			return $person->getId();
		}
	}

	/**
	 * @param string $code
	 */
	public function authenticate($code) {
		$this->getClient()->authenticate($code);
		$this->saveAccessToken($this->getClient()->getAccessToken());
	}

	public function logout() {
		$this->removeAccessToken();
	}


	/**
	 * @return \Google_Service_Plus
	 */
	public function getPlus() {
		return new \Google_Service_Plus($this->getClient());
	}

	/**
	 * @return picasa\Client
	 */
	public function getPicasa() {
		$accessToken = (array) json_decode($this->getAccessToken());
		$builder = $this->clientFactory->createOauth2Client();
		$builder->setBaseUri('https://picasaweb.google.com/data/feed/api/');
		if ($accessToken) {
			$builder->setAccessToken($accessToken);
		}
		return new picasa\Client($builder);

	}

	/**
	 * @param string $token
	 */
	private function saveAccessToken($token) {
		$session = $this->getSession();
		$session->accessToken = $token;
	}

	/**
	 * @return string
	 */
	private function getAccessToken() {
		$session = $this->getSession();
		if (isset($session->accessToken)) {
			return $session->accessToken;
		}
	}

	private function removeAccessToken() {
		$session = $this->getSession();
		unset($session->accessToken);
	}

	/**
	 * @return \Nette\Http\SessionSection
	 */
	private function getSession() {
		return $this->session->getSection('google');
	}

	/**
	 * @return \Google_Client
	 */
	private function getClient() {
		if (!$this->client) {
			$this->client = $this->prepareClient();
		}
		return $this->client;
	}

	/**
	 * @return \Google_Client
	 */
	private function prepareClient() {
		$client = new \Google_Client();

		$client->setClientId($this->id);
		$client->setClientSecret($this->secret);
		$client->setRedirectUri($this->generator->link('Google:callback'));
		$client->setScopes(
			[
				'https://www.googleapis.com/auth/plus.me',
				'https://picasaweb.google.com/data/'
			]
		);
		$client->setAccessType('online');
		$client->setApprovalPrompt('auto');

		if ($token = $this->getAccessToken()) {
			$client->setAccessToken($token);
		}
		return $client;
	}
}
