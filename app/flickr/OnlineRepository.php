<?php

namespace gplusFlickr\flickr;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class OnlineRepository {
	/** @var Api */
	private $api;

	public function __construct(
		Api $api
	) {
		$this->api = $api;
	}

	/**
	 * @param int $userId
	 * @param string $title
	 * @return Photo[]
	 */
	public function getPhotosOfUserWithTitle($userId, $title) {
		// https://www.flickr.com/services/api/flickr.photos.search.html
		$arguments = [
			'user_id' => $userId,
			'text' => $title,
			'content_type' => 1,
			'extras' => 'o_dims,url_o'
		];
		$result = $this->api->getClient()->callApi('flickr.photos.search', $arguments);

		$photos = [];
		if ($result) {
			// TODO check if properties are set
			foreach ($result->photos->photo as $data) {
				$photos[] = $this->createPhotoFromOnlineData($data);
			}
		}
		return $photos;
	}

	/**
	 * @param int $userId
	 * @param string $title
	 * @return Photo[]
	 */
	public function getPhotosOfUserByTime($userId, $time) {
		// https://www.flickr.com/services/api/flickr.photos.search.html
		$arguments = [
				'user_id' => $userId,
				'text' => $title,
				'content_type' => 1,
				'extras' => 'o_dims,url_o'
		];
		$result = $this->api->getClient()->callApi('flickr.photos.search', $arguments);

		$photos = [];
		if ($result) {
			// TODO check if properties are set
			foreach ($result->photos->photo as $data) {
				$photos[] = $this->createPhotoFromOnlineData($data);
			}
		}
		return $photos;
	}

	/**
	 * @param object $data
	 * @return Photo
	 */
	private function createPhotoFromOnlineData($data) {
		return new Photo(
			$data->id, $data->owner, $data->secret, $data->server, $data->farm,
			$data->title, isset($data->description) ? $data->description : null,
			$data->ispublic, $data->isfriend, $data->isfamily,
			$data->url_o, $data->width_o, $data->height_o
		);
	}

	/**
	 * @param Photo $photo
	 * @param string $description
	 * @return bool
	 */
	public function setDescription(Photo $photo, $description) {
		// https://www.flickr.com/services/api/flickr.photos.setMeta.html
		$result = $this->api->getClient()->callApi(
			'flickr.photos.setMeta',
			[
				'photo_id' => $photo->getFlickrId(),
				'title' => $photo->getTitle(),
				'description' => $description
			]
		);

		if ($result->stat == 'ok') {
			$photo->setDescription($description);
			return true;
		}
		return false;
	}
}
