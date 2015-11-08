<?php

namespace gplusFlickr\search;

use gplusFlickr\google\picasa\Photo as PicasaPhoto,
	gplusFlickr\flickr\Dao as FlickrDao;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class Repository {
	/** @var Dao */
	private $dao;
	/** @var FlickrDao */
	private $flickrDao;

	public function __construct(
		Dao $dao,
		FlickrDao $flickrDao
	) {
		$this->dao = $dao;
		$this->flickrDao = $flickrDao;
	}

	/**
	 * @param Result $result
	 * @return Result
	 */
	public function addResult(Result $result) {
		if ($savedResult = $this->dao->getResult($result->getPicasaId(), $result->getFlickrId())) {
			return $savedResult;
		} else {
			return $this->dao->saveResult($result);
		}
	}

	/**
	 * @param PicasaPhoto $picasaPhoto
	 * @param bool $validOnly
	 * @return Result[]
	 */
	public function getResults(PicasaPhoto $picasaPhoto, $validOnly = true) {
		$results = $this->dao->getResults($picasaPhoto->getId(), $validOnly);
		$flickrIds = [];
		foreach ($results as $result) {
			$result->setPicasaPhoto($picasaPhoto);
			$flickrIds[] = $result->getFlickrId();
		}

		$photos = $this->flickrDao->getPhotos($flickrIds);
		foreach ($results as $result) {
			if (isset($photos[$result->getFlickrId()])) {
				$result->setFlickrPhoto($photos[$result->getFlickrId()]);
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
		$result = $this->dao->getResult($picasaId, $flickrId);
		// TODO add photos
		return $result;
	}

	/**
	 * @param Result $result
	 */
	public function invalidateResult(Result $result) {
		$this->dao->setValidState($result->getPicasaId(), $result->getFlickrId(), false);
	}

	/**
	 * @param Result $result
	 */
	public function validateResult(Result $result) {
		$this->dao->setValidState($result->getPicasaId(), $result->getFlickrId(), true);
	}
}
