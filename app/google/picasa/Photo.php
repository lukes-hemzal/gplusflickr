<?php

namespace gplusFlickr\google\picasa;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class Photo {
	/** @var int */
	private $id;
	/** @var string */
	private $googleId;
	/** @var string */
	private $title;
	/** @var string */
	private $summary;
	/** @var string */
	private $source;
	/** @var \DateTime */
	private $published;
	/** @var \DateTime */
	private $updated;

	public function __construct($googleId, $title, $summary, $source, \DateTime $published, \DateTime $updated, $id = null) {
		$this->googleId = $googleId;
		$this->title = $title;
		$this->summary = $summary;
		$this->source = $source;
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
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @return string
	 */
	public function getSummary() {
		return $this->summary;
	}

	/**
	 * @return mixed
	 */
	public function getSource() {
		return $this->source;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return Photo
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
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
