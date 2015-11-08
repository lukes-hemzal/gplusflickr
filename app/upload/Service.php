<?php

namespace gplusFlickr\upload;

use gplusFlickr\google\picasa\Album as PicasaAlbum,
	gplusFlickr\flickr\Photo as FlickrPhoto,
	gplusFlickr\flickr\Repository as FlickrRepository;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class Service {
	/** @var Uploadr */
	private $uploadr;
	/** @var Repository */
	private $repository;
	/** @var FlickrRepository */
	private $flickrRepository;
	/** @var Factory */
	private $factory;

	public function __construct(
		Uploadr $uploadr,
		Repository $repository,
		FlickrRepository $flickrRepository,
		Factory $factory
	) {
		$this->uploadr = $uploadr;
		$this->repository = $repository;
		$this->flickrRepository = $flickrRepository;
		$this->factory = $factory;
	}

	/**
	 * TODO photo summary
	 *
	 * @param PicasaAlbum $album
	 * @param FlickrPhoto[] $photos
	 * @return \gplusFlickr\flickr\Album
	 */
	public function addAlbum(PicasaAlbum $album, array $photos) {
		// TODO outside?
		if ($flickrAlbum = $this->getFlickrVersion($album)) {
			return $flickrAlbum;
		} else {
			$primaryPhoto = array_shift($photos);
			if ($flickrAlbum = $this->uploadr->createAlbum($album->getTitle(), 'G+ sync', $primaryPhoto)) {
				// save album
				$savedAlbum = $this->flickrRepository->addAlbum($flickrAlbum);
				// TODO photo summary
				$this->flickrRepository->addPhotoToAlbum($flickrAlbum, $primaryPhoto);
				$connection = $this->factory->createAlbumConnection($album, $savedAlbum);
				// save connection
				$this->repository->addAlbumConnection($connection);
				foreach ($photos as $photo) {
					if ($this->uploadr->addPhoto($savedAlbum, $photo)) {
						$this->flickrRepository->addPhotoToAlbum($flickrAlbum, $photo);
					}
				}
				return $savedAlbum;
			}
		}
	}

	/**
	 * @param PicasaAlbum $album
	 * @return \gplusFlickr\flickr\Album
	 */
	public function getFlickrVersion(PicasaAlbum $album) {
		if ($connection = $this->repository->getAlbumConnection($album)) {
			return $this->flickrRepository->getAlbum($connection->getFlickrId());
		}
	}
}
