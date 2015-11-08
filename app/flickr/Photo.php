<?php

namespace gplusFlickr\flickr;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class Photo {
	/** @var int */
	private $id;
	/** @var int */
	private $flickrId;
	/** @var string */
	private $owner;
	/** @var string */
	private $secret;
	/** @var int */
	private $server;
	/** @var int */
	private $farm;
	/** @var string */
	private $title;
	/** @var string */
	private $description;
	/** @var bool */
	private $ispublic;
	/** @var bool */
	private $isfriend;
	/** @var bool */
	private $isfamily;
	/** @var string */
	private $url;
	/** @var int */
	private $width;
	/** @var int */
	private $height;

	public function __construct(
		$flickrId, $owner, $secret,
		$server, $farm, $title, $description,
		$ispublic, $isfriend, $isfamily,
		$url, $width, $height,
		$id = null
	) {
		$this->flickrId = $flickrId;
		$this->owner = $owner;
		$this->secret = $secret;
		$this->server = $server;
		$this->farm = $farm;
		$this->title = $title;
		$this->description = $description;
		$this->ispublic = $ispublic;
		$this->isfriend = $isfriend;
		$this->isfamily = $isfamily;
		$this->url = $url;
		$this->width = $width;
		$this->height = $height;
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getUrl() {
		return "https://farm{$this->farm}.staticflickr.com/{$this->server}/{$this->flickrId}_{$this->secret}.jpg";
	}

	/**
	 * @return string
	 */
	public function getPageUrl() {
		return "https://www.flickr.com/photos/{$this->owner}/{$this->flickrId}";
	}

	/**
	 * @return string
	 */
	public function getOriginalUrl() {
		return $this->url;
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
	public function getOwner() {
		return $this->owner;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $description
	 * @return Photo
	 */
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @return boolean
	 */
	public function ispublic() {
		return $this->ispublic;
	}

	/**
	 * @return boolean
	 */
	public function isfriend() {
		return $this->isfriend;
	}

	/**
	 * @return boolean
	 */
	public function isfamily() {
		return $this->isfamily;
	}

	/**
	 * @return string
	 */
	public function getSecret() {
		return $this->secret;
	}

	/**
	 * @return int
	 */
	public function getServer() {
		return $this->server;
	}

	/**
	 * @return int
	 */
	public function getFarm() {
		return $this->farm;
	}

	/**
	 * @return int
	 */
	public function getWidth() {
		return $this->width;
	}

	/**
	 * @return int
	 */
	public function getHeight() {
		return $this->height;
	}
}
