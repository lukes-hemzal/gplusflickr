<?php

namespace gplusFlickr\search; // somewhere else?

use gplusFlickr\google\picasa\Photo as PicasaPhoto,
	gplusFlickr\flickr\OnlineRepository;


/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class ImageSeeker {
	/** @var OnlineRepository */
	private $onlineRepository;
	/** @var Factory */
	private $factory;

	public function __construct(
		OnlineRepository $onlineRepository,
		Factory $factory
	) {
		$this->onlineRepository = $onlineRepository;
		$this->factory = $factory;
	}

	/**
	 * @param int $userId
	 * @param PicasaPhoto $picasaPhoto
	 * @return Result[]
	 */
	public function findImage($userId, PicasaPhoto $picasaPhoto) {
		$title = $picasaPhoto->getTitle();
		$expressions = [
			'{\\.[^.\\s]+$}', // cut extension
			'{_.*$}', // cut part after underscore (underscore included)
			'{ *(-|–) *.*$}', // cut part after hyphen (hyphen and surrounding spaces included)
		];
		$photos = [];
		$results = [];

		foreach ($expressions as $expression) {
			$title = preg_replace($expression, '', $title);
			if ($photos = $this->onlineRepository->getPhotosOfUserWithTitle($userId, $title)) {
				// TODO add somehow the expression to results?
				break;
			}
		}

		foreach ($photos as $photo) {
			$results[] = $this->factory->createResult($picasaPhoto, $photo);
		}
		return $results;
	}
}
