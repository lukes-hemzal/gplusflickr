<?php

namespace gplusFlickr\oauth;

/**
 * TODO BuilderFactory?
 *
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class ClientFactory {
	/** @var mixed */
	private $verify;

	public function __construct($verify) {
		$this->verify = $verify;
	}

	/**
	 * @param string $consumerKey
	 * @param string $consumerSecret
	 * @return OauthBuilder
	 */
	public function createOauthClient($consumerKey, $consumerSecret) {
		$builder = new OauthBuilder($consumerKey, $consumerSecret);
		$builder->setVerify($this->verify);
		return $builder;
	}

	/**
	 * @return Oauth2Builder
	 */
	public function createOauth2Client() {
		$builder = new Oauth2Builder();
		$builder->setVerify($this->verify);
		return $builder;
	}
}
