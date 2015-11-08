<?php

namespace gplusFlickr\flickr;

use gplusFlickr\oauth\OauthBuilder;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class Client {
	/** @var OauthBuilder */
	private $builder;

	public function __construct(
		OauthBuilder $builder
	) {
		$this->builder = $builder;
	}

	/**
	 * @param string $url
	 * @param array $query
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function call($url, $query = []) {
		return $this->builder->getClient()->get($url, ['query' => $query]);
	}

	/**
	 * @param string method
	 * @param array $arguments
	 * @return mixed
	 */
	public function callApi($method, $arguments = []) {
		$arguments['method'] = $method;
		$arguments['format'] = 'json';
		$arguments['nojsoncallback'] = 1;

		if ($result = $this->call('rest', $arguments)) {
			return json_decode($result->getBody()->getContents());
		}
	}

	/**
	 * @param string $token
	 * @return Client
	 */
	public function setToken($token) {
		$this->builder->setToken($token);
		return $this;
	}

	/**
	 * @param string $secret
	 * @return Client
	 */
	public function setTokenSecret($secret) {
		$this->builder->setTokenSecret($secret);
		return $this;
	}

	/**
	 * @param string $verifier
	 * @return Client
	 */
	public function setVerifier($verifier) {
		$this->builder->setVerifier($verifier);
		return $this;
	}
}
