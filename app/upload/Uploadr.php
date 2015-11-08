<?php

namespace gplusFlickr\upload;

use gplusFlickr\flickr\Api,
	gplusFlickr\flickr\Album,
	gplusFlickr\flickr\Photo;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class Uploadr {
	/** @var Api */
	private $api;

	public function __construct(
		Api $api
	) {
		$this->api = $api;
	}

	/**
	 * @param string $title
	 * @param string $description
	 * @param Photo $primaryPhoto
	 * @return Album
	 */
	public function createAlbum($title, $description, Photo $primaryPhoto) {
		// https://www.flickr.com/services/api/flickr.photosets.create.html
		$result = $this->api->getClient()->callApi(
			'flickr.photosets.create',
			[
				'title' => $title,
				'description' => $description,
				'primary_photo_id' => $primaryPhoto->getFlickrId()
			]
		);

		if ($result->stat == 'ok') {
			$flickrId = $result->photoset->id;
			return new Album($flickrId, $title, $description);
		}
	}

	/**
	 * @param Album $album
	 * @param Photo $photo
	 * @return bool
	 */
	public function addPhoto(Album $album, Photo $photo) {
		// https://www.flickr.com/services/api/flickr.photosets.addPhoto.html
		$result = $this->api->getClient()->callApi(
			'flickr.photosets.addPhoto',
			[
				'photoset_id' => $album->getFlickrId(),
				'photo_id' => $photo->getFlickrId()
			]
		);

		if ($result->stat == 'ok') {
			return true;
		}
		return false;
	}

	// flickr.photosets.reorderPhotos
}
