<?php

namespace gplusFlickr\google\picasa;

use gplusFlickr\oauth\Oauth2Builder;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class Client {
	/** @var Oauth2Builder */
	private $builder;

	public function __construct(
		Oauth2Builder $builder
	) {
		$this->builder = $builder;
	}

	/**
	 * TODO return something better
	 *
	 * @param string $path
	 * @return \SimpleXMLElement
	 */
	public function callApi($path) {
		$response = $this->builder->getClient()->get($path);
		if ($response) {
			return new \SimpleXMLElement($response->getBody()->getContents());
		}

	}
}
