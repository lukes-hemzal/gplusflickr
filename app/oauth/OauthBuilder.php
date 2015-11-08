<?php

namespace gplusFlickr\oauth;

use GuzzleHttp\Client,
	GuzzleHttp\HandlerStack,
	GuzzleHttp\Subscriber\Oauth\Oauth1;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class OauthBuilder extends ClientBuilder{
	/** @var string */
	protected $consumerKey;
	/** @var string */
	protected $consumerSecret;
	/** @var string */
	protected $token;
	/** @var string */
	protected $tokenSecret;


	public function __construct($consumerKey, $consumerSecret) {
		$this->consumerKey = $consumerKey;
		$this->consumerSecret = $consumerSecret;
	}

	/**
	 * @return Client
	 */
	public function getClient() {
		$stack = HandlerStack::create();
		$stack->push($this->getOauth());

		$config = $this->config(
			[
				'handler' => $stack,
				'auth' => 'oauth'
			]
		);

		return new Client($config);
	}

	/**
	 * @param string $token
	 * @return OauthBuilder
	 */
	public function setToken($token) {
		$this->token = $token;
		return $this;
	}

	/**
	 * @param string $tokenSecret
	 * @return OauthBuilder
	 */
	public function setTokenSecret($tokenSecret) {
		$this->tokenSecret = $tokenSecret;
		return $this;
	}

	/**
	 * @param string $verifier
	 * @return OauthBuilder
	 */
	public function setVerifier($verifier) {
		$this->verifier = $verifier;
		return $this;
	}

	/**
	 * @return Oauth1
	 */
	protected function getOauth() {
		$config = [
			'consumer_key' => $this->consumerKey,
			'consumer_secret' => $this->consumerSecret,
			'token' => $this->token,
			'token_secret' => $this->tokenSecret,
			'verifier' => $this->verifier
		];

		return new Oauth1($config);
	}
}
