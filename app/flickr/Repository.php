<?php

namespace gplusFlickr\flickr;

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
	 * @param int $id
	 * @return Photo
	 */
	public function getPhoto($id) {
		return $this->dao->getPhoto($id);
	}

	/**
	 * @param int[] $ids
	 * @return Photo[]
	 */
	public function getPhotos(array $ids) {
		return $this->dao->getPhotos($ids);
	}

	/**
	 * @param Photo $photo
	 * @return Photo
	 */
	public function addPhoto(Photo $photo) {
		if ($savedPhoto = $this->dao->getPhotoByFlickrId($photo->getFlickrId())) {
			return $savedPhoto;
		} else {
			return $this->dao->savePhoto($photo);
		}
	}

	/**
	 * @param Photo $photo
	 */
	public function updatePhoto(Photo $photo) {
		// if we don't know it, we can't update it
		if ($savedPhoto = $this->dao->getPhoto($photo->getId())) {
			// TODO examine changes?
			$this->dao->updatePhoto($photo);
		}
	}

	/**
	 * @param Album $album
	 * @return Album
	 */
	public function addAlbum(Album $album) {
		if ($savedAlbum = $this->dao->getAlbumByFlickrId($album->getFlickrId())) {
			return $savedAlbum;
		} else {
			return $this->dao->saveAlbum($album);
		}
	}

	/**
	 * @param int $id
	 * @return Album
	 */
	public function getAlbum($id) {
		return $this->dao->getAlbum($id);
	}

	/**
	 * @param Album $album
	 * @param Photo $photo
	 */
	public function addPhotoToAlbum(Album $album, Photo $photo) {
		if (!$this->dao->isPhotoInAlbum($album->getId(), $photo->getId())) {
			$this->dao->saveRelation($album->getId(), $photo->getId());
		}
	}
}
