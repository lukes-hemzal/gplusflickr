<?php

namespace gplusFlickr\upload;

use gplusFlickr\flickr\Dao as FlickrDao,
	gplusFlickr\flickr\Album as FlickrAlbum,
	gplusFlickr\google\picasa\Album as PicasaAlbum,
	gplusFlickr\google\picasa\Photo as PicasaPhoto;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class Repository {
	/** @var Dao */
	private $dao;

	public function __construct(
		Dao $dao
	) {
		$this->dao = $dao;
	}

	/**
	 * @param PicasaAlbum $picasaAlbum
	 * @return AlbumConnection
	 */
	public function getAlbumConnection(PicasaAlbum $picasaAlbum) {
		return $this->dao->getAlbumConnectionByGoogleId($picasaAlbum->getId());
	}

	/**
	 * @param AlbumConnection $connection
	 * @return AlbumConnection
	 */
	public function addAlbumConnection(AlbumConnection $connection) {
		if ($savedConnection = $this->getAlbumConnection($connection->getPicasaAlbum())) {
			return $savedConnection;
		} else {
			// TODO add albums
			return $this->dao->saveAlbumConnection($connection);
		}
	}
}
