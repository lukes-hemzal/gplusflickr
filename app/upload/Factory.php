<?php

namespace gplusFlickr\upload;

use gplusFlickr\flickr\Album as FlickrAlbum,
	gplusFlickr\google\picasa\Album as PicasaAlbum;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class Factory {
	/**
	 * @param PicasaAlbum $picasaAlbum
	 * @param FlickrAlbum $flickrAlbum
	 * @param \DateTime|null $created
	 * @param \DateTime|null $updated
	 * @return AlbumConnection
	 */
	public function createAlbumConnection(
		PicasaAlbum $picasaAlbum, FlickrAlbum $flickrAlbum,
		\DateTime $created = null, \DateTime $updated = null
	) {
		if (!$created) {
			$created = new \DateTime();
		}
		if (!$updated) {
			$updated = new \DateTime();
		}
		$connection = new AlbumConnection($picasaAlbum->getId(), $flickrAlbum->getId(), $created, $updated);
		$connection->setPicasaAlbum($picasaAlbum)
			->setFlickrAlbum($flickrAlbum);
		return $connection;
	}
}
