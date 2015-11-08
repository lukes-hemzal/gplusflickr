<?php

namespace gplusFlickr\upload;

use gplusFlickr\flickr\Album as FlickrAlbum,
	gplusFlickr\google\picasa\Album as PicasaAlbum;

/**
 * Connection of Picasa and Flickr albums
 *
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class AlbumConnection {
	/** @var int */
	private $picasaId;
	/** @var int */
	private $flickrId;
	/** @var PicasaAlbum */
	private $picasaAlbum;
	/** @var FlickrAlbum */
	private $flickrAlbum;
	/** @var \DateTime */
	private $created;
	/** @var \DateTime */
	private $updated;

	public function __construct(
		$picasaId, $flickrId, \DateTime $created, \DateTime $updated
	) {
		$this->picasaId = $picasaId;
		$this->flickrId = $flickrId;
		$this->created = $created;
		$this->updated = $updated;
	}

	/**
	 * @return int
	 */
	public function getPicasaId() {
		return $this->picasaId;
	}

	/**
	 * @return int
	 */
	public function getFlickrId() {
		return $this->flickrId;
	}

	/**
	 * @param PicasaAlbum $picasaAlbum
	 * @return AlbumConnection
	 */
	public function setPicasaAlbum(PicasaAlbum $picasaAlbum) {
		$this->picasaAlbum = $picasaAlbum;
		return $this;
	}

	/**
	 * @return PicasaAlbum
	 */
	public function getPicasaAlbum() {
		return $this->picasaAlbum;
	}

	/**
	 * @param FlickrAlbum $flickrAlbum
	 * @return AlbumConnection
	 */
	public function setFlickrAlbum(FlickrAlbum $flickrAlbum) {
		$this->flickrAlbum = $flickrAlbum;
		return $this;
	}

	/**
	 * @return FlickrAlbum
	 */
	public function getFlickrAlbum() {
		return $this->flickrAlbum;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreated() {
		return $this->created;
	}

	/**
	 * @return \DateTime
	 */
	public function getUpdated() {
		return $this->updated;
	}
}
