<?php

namespace gplusFlickr\google\picasa;

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
	 * @param int $userId
	 * @return Album[]
	 */
	public function getAlbums($userId) {
		return $this->dao->getAlbumsOfUser($userId);
	}

	/**
	 * @param int $id
	 * @return Album
	 */
	public function getAlbum($id) {
		return $this->dao->getAlbum($id);
	}

	/**
	 * @param int $albumId
	 * @return Photo[]
	 */
	public function getPhotos($albumId) {
		return $this->dao->getPhotosOfAlbum($albumId);
	}

	/**
	 * @param int $id
	 * @return Photo
	 */
	public function getPhoto($id) {
		return $this->dao->getPhoto($id);
	}

	/**
	 * Add album to repository
	 *
	 * @param Album $album
	 * @return Album
	 */
	public function addAlbum(Album $album) {
		if ($savedAlbum = $this->dao->getAlbumByGoogleId($album->getGoogleId())) {
			return $savedAlbum;
		} else {
			return $this->dao->saveAlbum($album);
		}
	}

	/**
	 * Add array of photos to repository
	 *
	 * @param Album $album
	 * @param Photo[] $photos
	 * @return Photo[]
	 */
	public function addPhotos(Album $album, array $photos) {
		$savedPhotos = [];
		foreach ($photos as $id => $photo) {
			$savedPhoto = $this->addPhoto($photo);
			$this->addPhotoToAlbum($album, $savedPhoto, $id);
			$savedPhotos[] = $savedPhoto;
		}
		return $savedPhotos;
	}

	/**
	 * @param Photo $photo
	 * @return Photo
	 */
	public function addPhoto(Photo $photo) {
		if ($savedPhoto = $this->dao->getPhotoByGoogleId($photo->getGoogleId())) {
			return $savedPhoto;
		} else {
			return $this->dao->savePhoto($photo);
		}
	}

	/**
	 * @param Album $album
	 * @param Photo $photo
	 * @param int $order
	 */
	public function addPhotoToAlbum(Album $album, Photo $photo, $order) {
		if (!$this->dao->isPhotoInAlbum($album->getId(), $photo->getId())) {
			$this->dao->saveRelation($album->getId(), $photo->getId(), $order);
		}
	}
}
