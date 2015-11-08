<?php

namespace gplusFlickr\oauth;

use GuzzleHttp\Client;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
abstract class ClientBuilder {
	/** @var array */
	protected $config = [];
	/** @var string */
	protected $verifier;
	/**
	 * @return Client
	 */
	abstract public function getClient();

	/**
	 * @param mixed $verify
	 * @return OauthBuilder
	 */
	public function setVerify($verify) {
		$this->config['verify'] = $verify;
		return $this;
	}

	/**
	 * @param string $uri
	 * @return OauthBuilder
	 */
	public function setBaseUri($uri) {
		$this->config['base_uri'] = $uri;
		return $this;
	}

	/**
	 * @param array $data
	 * @return array
	 */
	protected function config(array $data = []) {
		$config = $this->config;
		foreach ($data as $key => $value) {
			$config[$key] = $value;
		}
		return $config;
	}
}
