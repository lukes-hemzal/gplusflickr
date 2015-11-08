<?php

namespace gplusFlickr\presenter;

use gplusFlickr\google\picasa\Repository,
	gplusFlickr\search\Service,
	gplusFlickr\upload\Service as UploadService,
	gplusFlickr\flickr\OnlineRepository,
	gplusFlickr\flickr\Repository as FlickrRepository,
	Nette\Application\Responses\JsonResponse;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class PhotoPresenter extends BasePresenter {
	/** @var Repository */
	private $repository;
	/** @var Service */
	private $searchService;
	/** @var UploadService */
	private $uploadService;
	/** @var OnlineRepository */
	private $onlineRepository;
	/** @var FlickrRepository */
	private $flickrRepository;

	/**
	 * @param Repository $repository
	 * @internal
	 */
	public function injectRepository(Repository $repository) {
		$this->repository = $repository;
	}

	/**
	 * @param Service $searchService
	 * @internal
	 */
	public function injectSearchService(Service $searchService) {
		$this->searchService = $searchService;
	}

	/**
	 * @param UploadService $uploadService
	 * @internal
	 */
	public function injectUploadService(UploadService $uploadService) {
		$this->uploadService = $uploadService;
	}

	/**
	 * @param OnlineRepository $onlineRepository
	 * @internal
	 */
	public function injectOnlineRepository(OnlineRepository $onlineRepository) {
		$this->onlineRepository = $onlineRepository;
	}

	/**
	 * @param FlickrRepository $flickrRepository
	 * @internal
	 */
	public function injectFlickrRepository(FlickrRepository $flickrRepository) {
		$this->flickrRepository = $flickrRepository;
	}

	public function actionAlbums() {
		if (!$this->googleApi->isAuthenticated()) {
			$this->redirect('Google:auth');
		}
	}

	public function renderAlbum($id) {
		$this->template->album = $this->repository->getAlbum($id);
		$this->template->photos = $this->repository->getPhotos($id);
	}

	public function actionUploadAlbum($id) {
		$userId = $this->flickrAuth->getUserId();
		if ($album = $this->repository->getAlbum($id)) {
			if (!$this->uploadService->getFlickrVersion($album)) {
				// TODO somewhere else
				$photos = $this->repository->getPhotos($id);
				$flickrPhotos = [];
				foreach ($photos as $order => $photo) {
					$photoResults = $this->searchService->getFlickrEquivalent($userId, $photo);
					// TODO check if there is only one result
					// TODO what to do with an empty result?
					if ($result = reset($photoResults)) {
						/* @var \gplusFlickr\search\Result $result */
						$photo = $result->getFlickrPhoto();
						$this->onlineRepository->setDescription($photo, $result->getPicasaPhoto()->getSummary());
						$this->flickrRepository->updatePhoto($photo);
						$flickrPhotos[] = $photo;
					}
				}
				// TODO stop if empty flickrPhotos?
				$this->uploadService->addAlbum($album, $flickrPhotos);
				$this->flashMessage('Album bylo nahráno');
			} else {
				// TODO try to update?
				$this->flashMessage('Album bylo již nahráno');
			}
		}
		$this->redirect('album', $id);
	}

	/**
	 * Show albums
	 */
	public function renderAlbums() {
		$albums = [];
		// TODO store id in session? in api?
		if ($id = $this->googleApi->getUserId()) {
			$albums = $this->repository->getAlbums($id);
		}
		$this->template->albums = $albums;
	}

	/**
	 * @param int $id
	 * @param bool $valid
	 */
	public function actionPhoto($id, $valid = true) {
		$userId = $this->flickrAuth->getUserId();
		$photo = $this->repository->getPhoto($id);

		$results = [];
		if ($userId && $photo) {
			$results = $this->searchService->getFlickrEquivalent($userId, $photo, $valid);
		}

		if ($this->isAjax()) {
			$urls = [];
			foreach ($results as $result) {
				$urls[] = $result->getFlickrPhoto()->getUrl();
			}
			$data = [
				'photos' => $urls,
			];
			$this->sendResponse(new JsonResponse($data));
		} else {
			$this->template->valid = $valid;
			$this->template->photo = $photo;
			$this->template->results = $results;
		}
	}

	/**
	 * @param int $id
	 * @param int $flickrId
	 */
	public function actionInvalidate($id, $flickrId) {
		if ($result = $this->searchService->getResult($id, $flickrId)) {
			$this->searchService->invalidate($result);
			$this->redirect('photo', $result->getPicasaId());
		}
	}

	/**
	 * @param int $id
	 * @param int $flickrId
	 */
	public function actionValidate($id, $flickrId) {
		if ($result = $this->searchService->getResult($id, $flickrId)) {
			$this->searchService->validate($result);
			$this->redirect('photo', $result->getPicasaId());
		}
	}
}
