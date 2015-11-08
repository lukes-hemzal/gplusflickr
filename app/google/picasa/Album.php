<?php

namespace gplusFlickr\google\picasa;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class Album {
	/** @var int */
	private $id;
	/** @var string */
	private $googleId;
	/** @var string */
	private $title;
	/** @var \DateTime */
	private $published;
	/** @var \DateTime */
	private $updated;

	public function __construct($googleId, $title, \DateTime $published, \DateTime $updated, $id = null) {
		$this->googleId = $googleId;
		$this->title = $title;
		$this->published = $published;
		$this->updated = $updated;
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getGoogleId() {
		return $this->googleId;
	}

	/**
	 * @param int $id
	 * @return Album
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return int
	 */
	public function getIdAsNumber() {
		if ($parsed = $this->parseId()) {
			return $parsed['albumId'];
		}
	}

	/**
	 * @return int
	 */
	public function getUserId() {
		if ($parsed = $this->parseId()) {
			return $parsed['userId'];
		}
	}

	/**
	 * Return array [userId: '...', albumId: '...']
	 *
	 * @return array
	 */
	private function parseId() {
		$pattern = '{https://picasaweb.google.com/data/entry/api/user/(\d+)/albumid/(\d+)}';
		$matches = [];

		if (preg_match($pattern, $this->getGoogleId(), $matches)) {
			list ($match, $userId, $albumId) = $matches;
			return ['userId' => $userId, 'albumId' => $albumId];
		}
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @return \DateTime
	 */
	public function getPublished() {
		return $this->published;
	}

	/**
	 * @return \DateTime
	 */
	public function getUpdated() {
		return $this->updated;
	}
}
