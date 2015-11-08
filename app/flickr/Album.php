<?php

namespace gplusFlickr\flickr;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class Album {
	/** @var int */
	private $id;
	/** @var int */
	private $flickrId;
	/** @var string */
	private $title;
	/** @var string */
	private $description;

	public function __construct($flickrId, $title, $description, $id = null) {
		$this->flickrId = $flickrId;
		$this->title = $title;
		$this->description = $description;
		$this->id = $id;
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
	public function getFlickrId() {
		return $this->flickrId;
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
	public function getDescription() {
		return $this->description;
	}
}
