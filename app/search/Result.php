<?php

namespace gplusFlickr\search;

use gplusFlickr\flickr\Photo as FlickrPhoto,
	gplusFlickr\google\picasa\Photo as PicasaPhoto;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class Result {
	/** @var int */
	private $picasaId;
	/** @var int */
	private $flickrId;
	/** @var PicasaPhoto */
	private $picasaPhoto;
	/** @var FlickrPhoto */
	private $flickrPhoto;
	/** @var bool */
	private $valid;

	public function __construct(
		$picasaId,
		$flickrId,
		$valid = true
	) {
		$this->picasaId = $picasaId;
		$this->flickrId = $flickrId;
		$this->valid = $valid;
	}

	/**
	 * @return int
	 */
	public function getPicasaId() {
		return $this->picasaId;
	}

	/**
	 * @param int $flickrId
	 */
	public function setFlickrId($flickrId) {
		$this->flickrId = $flickrId;
	}

	/**
	 * @return int
	 */
	public function getFlickrId() {
		return $this->flickrId;
	}

	/**
	 * @param PicasaPhoto $picasaPhoto
	 * @return Result
	 */
	public function setPicasaPhoto($picasaPhoto) {
		$this->picasaPhoto = $picasaPhoto;
		return $this;
	}

	/**
	 * @return PicasaPhoto
	 */
	public function getPicasaPhoto() {
		return $this->picasaPhoto;
	}

	/**
	 * @param FlickrPhoto $flickrPhoto
	 * @return Result
	 */
	public function setFlickrPhoto($flickrPhoto) {
		$this->flickrPhoto = $flickrPhoto;
		return $this;
	}

	/**
	 * @return FlickrPhoto
	 */
	public function getFlickrPhoto() {
		return $this->flickrPhoto;
	}

	/**
	 * @return bool
	 */
	public function isValid() {
		return $this->valid;
	}
}
