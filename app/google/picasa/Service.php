<?php

namespace gplusFlickr\google\picasa;

use gplusFlickr\google\Api as GoogleApi;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class Service {
	/** @var GoogleApi */
	private $api;
	/** @var Parser */
	private $parser;

	/** @var array */
	private $accessToken;

	public function __construct(
		GoogleApi $api,
		Parser $parser
	) {
		$this->api = $api;
		$this->parser = $parser;
	}

	/**
	 * @return Client
	 */
	private function getClient() {
		return $this->api->getPicasa();
	}

	/**
	 * @param string $userId
	 * @return Album[]
	 */
	public function getUserAlbums($userId) {
		if ($response = $this->getClient()->callApi("user/$userId")) {
			return $this->parser->parseAlbums($response);
		}
	}

	/**
	 * @param int $userId
	 * @param int $albumId
	 * @return Photo[]
	 */
	public function getPhotos($userId, $albumId) {
		if ($response = $this->getClient()->callApi("user/$userId/albumid/$albumId")) {
			return $this->parser->parsePhotos($response);
		}
	}
}
