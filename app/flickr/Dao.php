<?php

namespace gplusFlickr\flickr;

use Nette\Database\Context,
	gplusFlickr\db\Info;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class Dao {
	/** @var Context */
	private $database;
	/** @var Factory */
	private $factory;

	/** @var string */
	private $table  = Info::FLICKR_PHOTO;
	/** @var string */
	private $albumTable = Info::FLICKR_ALBUM;
	/** @var string */
	private $relationTable = Info::FLICKR_RELATION;

	public function __construct(
		Context $database,
		Factory $factory
	) {
		$this->database = $database;
		$this->factory = $factory;
	}

	/**
	 * @param int $id
	 * @return Photo
	 */
	public function getPhoto($id) {
		$result = $this->database->query("SELECT * FROM {$this->table} WHERE [id] = ?", $id);
		if ($row = $result->fetch()) {
			return $this->factory->createPhotoFromRow($row);
		}
	}

	/**
	 * @param int $id
	 * @return Photo
	 */
	public function getPhotoByFlickrId($id) {
		$result = $this->database->query(
			"SELECT * FROM {$this->table} WHERE [flickrId] = ?", $id
		);

		if ($row = $result->fetch()) {
			return $this->factory->createPhotoFromRow($row);
		}
	}

	/**
	 * @param int[] $ids
	 * @return Photo[]
	 */
	public function getPhotos(array $ids) {
		$photos = [];
		$result = $this->database->query(
			"SELECT * FROM {$this->table} WHERE id IN(?)", $ids
		);

		while ($row = $result->fetch()) {
			$photo = $this->factory->createPhotoFromRow($row);
			$photos[$photo->getId()] = $photo;
		}
		return $photos;
	}

	/**
	 * @param Photo $photo
	 * @return Photo
	 */
	public function savePhoto(Photo $photo) {
		$data = $this->preparePhotoData($photo);
		$this->database->query("INSERT INTO {$this->table}", $data);
		$photo->setId($this->database->getInsertId());
		return $photo;
	}

	/**
	 * @param Photo $photo
	 */
	public function updatePhoto(Photo $photo) {
		$data = $this->preparePhotoData($photo);
		$this->database->query(
			"UPDATE {$this->table} SET ? WHERE id = ?", $data, $photo->getId()
		);
	}

	/**
	 * @param Photo $photo
	 * @return array
	 */
	private function preparePhotoData(Photo $photo) {
		return [
			'flickrId' => $photo->getFlickrId(),
			'owner' => $photo->getOwner(),
			'secret' => $photo->getSecret(),
			'server' => $photo->getServer(),
			'farm' => $photo->getFarm(),
			'title' => $photo->getTitle(),
			'description' => $photo->getDescription(),
			'ispublic' => $photo->ispublic(),
			'isfriend' => $photo->isfriend(),
			'isfamily' => $photo->isfamily(),
			'url' => $photo->getOriginalUrl(),
			'width' => $photo->getWidth(),
			'height' => $photo->getHeight()
		];
	}

	/**
	 * @param int $id
	 * @return Album
	 */
	public function getAlbum($id) {
		return $this->getAlbumBy('id', $id);
	}

	/**
	 * @param int $id
	 * @return Album
	 */
	public function getAlbumByFlickrId($id) {
		return $this->getAlbumBy('flickrId', $id);
	}

	/**
	 * @param string $property
	 * @param mixed $value
	 * @return Album
	 */
	private function getAlbumBy($property, $value) {
		$result = $this->database->query(
			"SELECT * FROM {$this->albumTable} WHERE [{$property}] = ?", $value
		);

		if ($row = $result->fetch()) {
			return $this->factory->createAlbumFromRow($row);
		}

	}

	/**
	 * @param Album $album
	 * @return Album
	 */
	public function saveAlbum(Album $album) {
		$data = [
			'flickrId' => $album->getFlickrId(),
			'title' => $album->getTitle(),
			'description' => $album->getDescription()
		];
		$this->database->query("INSERT INTO {$this->albumTable}", $data);
		$album->setId($this->database->getInsertId());
		return $album;
	}

	/**
	 * @param int $albumId
	 * @param int $photoId
	 * @return bool
	 */
	public function isPhotoInAlbum($albumId, $photoId) {
		$result = $this->database->query(
			"SELECT * FROM {$this->relationTable} WHERE albumId = ? AND photoId = ?",
			$albumId, $photoId
		);
		if ($row = $result->fetch()) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @param int $albumId
	 * @param int $photoId
	 */
	public function saveRelation($albumId, $photoId) {
		$data = [
			'albumId' => $albumId,
			'photoId' => $photoId
		];

		$this->database->query(
			"INSERT INTO {$this->relationTable}", $data
		);
	}
}
