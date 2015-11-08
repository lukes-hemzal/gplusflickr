<?php

namespace gplusFlickr\flickr;

use Nette\Database\IRow;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class Factory {
	/**
	 * @param IRow $row
	 * @return Photo
	 */
	public function createPhotoFromRow(IRow $row) {
		return new Photo(
			$row->flickrId, $row->owner, $row->secret, $row->server, $row->farm,
			$row->title, $row->description, $row->ispublic, $row->isfriend, $row->isfamily,
			$row->url, $row->width, $row->height, $row->id
		);
	}

	/**
	 * @param IRow $row
	 * @return Album
	 */
	public function createAlbumFromRow(IRow $row) {
		return new Album(
			$row->flickrId, $row->title, $row->description, $row->id
		);
	}
}
