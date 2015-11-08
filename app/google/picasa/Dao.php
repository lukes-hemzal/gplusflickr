<?php

namespace gplusFlickr\google\picasa;

use Nette\Database\Context,
	Nette\Database\IRow,
	gplusFlickr\db\Info,
	gplusFlickr\db\Utils;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class Dao {
	/** @var Context */
	private $database;
	/** @var Utils */
	private $utils;
	/** @var string */
	private $photoTable = Info::GOOGLE_PHOTO;
	/** @var string */
	private $albumTable = Info::GOOGLE_ALBUM;
	/** @var string */
	private $relationTable = Info::GOOGLE_RELATION;

	public function __construct(
		Context $database,
		Utils $utils
	) {
		$this->database = $database;
		$this->utils = $utils;
	}

	/**
	 * @param int $userId
	 * @return array
	 */
	public function getAlbumsOfUser($userId) {
		$result = $this->database->query("SELECT * FROM {$this->albumTable} WHERE userId = ?", $userId);
		$albums = [];
		while ($row = $result->fetch()) {
			$albums[] = $this->createAlbum($row);
		}
		return $albums;
	}

	/**
	 * @param int $id
	 * @return Album
	 */
	public function getAlbum($id) {
		$result = $this->database->query(
			"SELECT * FROM {$this->albumTable} WHERE id = ?", $id
		);

		if ($row = $result->fetch()) {
			return $this->createAlbum($row);
		}
	}

	/**
	 * @param int $id
	 * @return Album
	 */
	public function getAlbumByGoogleId($id) {
		$result = $this->database->query(
			"SELECT * FROM {$this->albumTable} WHERE googleId = ?", $id
		);

		if ($row = $result->fetch()) {
			return $this->createAlbum($row);
		}
	}

	/**
	 * @param int $id
	 * @return Photo
	 */
	public function getPhoto($id) {
		$result = $this->database->query(
			"SELECT * FROM {$this->photoTable} WHERE id = ?", $id
		);

		if ($row = $result->fetch()) {
			return $this->createPhoto($row);
		}
	}

	/**
	 * @param int $albumId
	 * @return Photo[]
	 */
	public function getPhotosOfAlbum($albumId) {
		$result = $this->database->query(
			"SELECT photo.* FROM {$this->photoTable} photo
				JOIN {$this->relationTable} relation on relation.photoId = photo.id
				WHERE relation.albumId = ?
				ORDER BY [relation].[order]",
			$albumId
		);

		$photos = [];
		while ($row = $result->fetch()) {
			$photos[] = $this->createPhoto($row);
		}
		return $photos;
	}

	/**
	 * @param Album $album
	 * @return Album
	 */
	public function saveAlbum(Album $album) {
		$data = [
			'googleId' => $album->getGoogleId(),
			'userId' => $album->getUserId(),
			'title' => $album->getTitle(),
			'published' => $this->utils->timeToFieldValue($album->getPublished()),
			'updated' => $this->utils->timeToFieldValue($album->getUpdated())
		];
		$this->database->query("INSERT INTO {$this->albumTable}", $data);
		$album->setId($this->database->getInsertId());
		return $album;
	}

	/**
	 * @param Photo $photo
	 * @return Photo
	 */
	public function savePhoto(Photo $photo) {
		$data = [
			'googleId' => $photo->getGoogleId(),
			'title' => $photo->getTitle(),
			'summary' => $photo->getSummary(),
			'source' => $photo->getSource()
		];

		$this->database->query("INSERT INTO {$this->photoTable}", $data);
		$photo->setId($this->database->getInsertId());
		return $photo;
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
	public function saveRelation($albumId, $photoId, $order) {
		$data = [
			'albumId' => $albumId,
			'photoId' => $photoId,
			'order' => $order
		];

		$this->database->query(
			"INSERT INTO {$this->relationTable}", $data
		);
	}

	/**
	 * @param int $id
	 * @return Photo
	 */
	public function getPhotoByGoogleId($id) {
		$result = $this->database->query(
			"SELECT * FROM {$this->photoTable} WHERE googleId = ?", $id
		);

		if ($row = $result->fetch()) {
			return $this->createPhoto($row);
		}
	}

	/**
	 * Create album from table row
	 *
	 * @param IRow $row
	 * @return Album
	 */
	private function createAlbum(IRow $row) {
		return new Album(
			$row->googleId, $row->title,
			$this->utils->fieldValueToTime($row->published),
			$this->utils->fieldValueToTime($row->updated),
			$row->id
		);
	}

	/**
	 * Create album from table row
	 *
	 * @param IRow $row
	 * @return Photo
	 */
	private function createPhoto(IRow $row) {
		return new Photo(
			$row->googleId, $row->title, $row->summary, $row->source,
			$this->utils->fieldValueToTime($row->published),
			$this->utils->fieldValueToTime($row->updated),
			$row->id
		);
	}
}
