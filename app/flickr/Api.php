<?php

namespace gplusFlickr\flickr;

use gplusFlickr\oauth\ClientFactory;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class Api {
	/** @var string */
	private $key;
	/** @var string */
	private $secret;
	/** @var ClientFactory */
	private $clientFactory;

	/** @var array */
	private $accessToken;

	/**
	 * Api constructor.
	 *
	 * @param string $key
	 * @param string $secret
	 * @param ClientFactory $clientFactory
	 */
	public function __construct($key, $secret, ClientFactory $clientFactory) {
		$this->key = $key;
		$this->secret = $secret;
		$this->clientFactory = $clientFactory;
	}

	/**
	 * @return Client
	 */
	public function getClient() {
		$builder = $this->clientFactory->createOauthClient($this->key, $this->secret)
			->setBaseUri('https://www.flickr.com/services/');

		if ($token = $this->getAccessToken()) {
			$builder->setToken($token['oauth_token'])
				->setTokenSecret($token['oauth_token_secret']);
		}

		return new Client($builder);
	}

	/**
	 * @return array
	 */
	private function getAccessToken() {
		return $this->accessToken;
	}

	/**
	 * @param array $accessToken
	 */
	public function setAccessToken(array $accessToken) {
		$this->accessToken = $accessToken;
	}

	/**
	 * Removes access token
	 */
	public function removeAccessToken() {
		$this->accessToken = null;
	}
}
