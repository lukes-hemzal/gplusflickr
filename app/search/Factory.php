<?php

namespace gplusFlickr\search;

use gplusFlickr\google\picasa\Photo as PicasaPhoto,
	gplusFlickr\flickr\Photo as FlickrPhoto,
	Nette\Database\IRow;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class Factory {
	/**
	 * @param PicasaPhoto $picasaPhoto
	 * @param FlickrPhoto $flickrPhoto
	 * @param bool $valid
	 * @return Result
	 */
	public function createResult(PicasaPhoto $picasaPhoto, FlickrPhoto $flickrPhoto, $valid = true) {
		$result = $this->createFlatResult($picasaPhoto->getId(), $flickrPhoto->getId(), $valid);
		$result->setPicasaPhoto($picasaPhoto)
			->setFlickrPhoto($flickrPhoto);

		return $result;
	}

	/**
	 * Creates result without photo objects
	 *
	 * @param int $picasaId
	 * @param int $flickrId
	 * @param bool $valid
	 * @return Result
	 */
	public function createFlatResult($picasaId, $flickrId, $valid = true) {
		return new Result($picasaId, $flickrId, (bool) $valid);
	}

	/**
	 * @param IRow $row
	 * @return Result
	 */
	public function createFromRow(IRow $row) {
		return $this->createFlatResult($row->googlePhotoId, $row->flickrPhotoId, $row->valid);
	}
}
