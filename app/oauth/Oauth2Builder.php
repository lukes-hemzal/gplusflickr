<?php

namespace gplusFlickr\oauth;

use GuzzleHttp\Client,
	CommerceGuys\Guzzle\Oauth2\AccessToken,
	CommerceGuys\Guzzle\Oauth2\Oauth2Client;


/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class Oauth2Builder extends ClientBuilder {
	/** @var AccessToken */
	private $accessToken;

	/**
	 * @param array|AccessToken $accessToken
	 */
	public function setAccessToken($accessToken = null) {
		if (!$accessToken instanceof AccessToken) {
			$accessToken = new AccessToken($accessToken['access_token'], $accessToken['token_type'], $accessToken);
		}
		$this->accessToken = $accessToken;
	}

	/**
	 * @return Client
	 */
	public function getClient() {
		$config = $this->config(['auth' => 'oauth2']);
		$client = new Oauth2Client($config);
		if ($this->accessToken) {
			$client->setAccessToken($this->accessToken);
		}
		return $client;
	}
}
