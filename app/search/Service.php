<?php

namespace gplusFlickr\search;

use gplusFlickr\google\picasa\Photo as PicasaPhoto,
	gplusFlickr\flickr\Repository as FlickrRepository;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class Service {
	/** @var Repository */
	private $repository;
	/** @var FlickrRepository */
	private $flickrRepository;
	/** @var ImageSeeker */
	private $imageSeeker;

	public function __construct(
		Repository $repository,
		FlickrRepository $flickrRepository,
		ImageSeeker $imageSeeker
	) {
		$this->repository = $repository;
		$this->flickrRepository = $flickrRepository;
		$this->imageSeeker = $imageSeeker;
	}

	/**
	 * @param int $userId
	 * @param PicasaPhoto $picasaPhoto
	 * @param bool $validOnly
	 * @return Result[]
	 */
	public function getFlickrEquivalent($userId, PicasaPhoto $picasaPhoto, $validOnly = true) {
		// first try locally
		if (!($results = $this->repository->getResults($picasaPhoto, $validOnly))) {
			// try to find them
			$foundResults = $this->imageSeeker->findImage($userId, $picasaPhoto);
			foreach ($foundResults as $result) {
				$flickrPhoto = $this->flickrRepository->addPhoto($result->getFlickrPhoto());
				$result->setFlickrId($flickrPhoto->getId());
				$result->setFlickrPhoto($flickrPhoto);
				$savedResult = $this->repository->addResult($result);
				if ($savedResult->isValid()) {
					$results[] = $savedResult;
				}
			}
		}
		return $results;
	}

	/**
	 * @param int $picasaId
	 * @param int $flickrId
	 * @return Result
	 */
	public function getResult($picasaId, $flickrId) {
		return $this->repository->getResult($picasaId, $flickrId);
	}

	/**
	 * @param Result $result
	 */
	public function invalidate(Result $result) {
		$this->repository->invalidateResult($result);
	}

	/**
	 * @param Result $result
	 */
	public function validate(Result $result) {
		$this->repository->validateResult($result);
	}
}
