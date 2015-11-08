<?php

namespace gplusFlickr\presenter;

use gplusFlickr\google\picasa\Service as PicasaService,
	gplusFlickr\google\picasa\Repository;

	/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class DownloadPresenter extends BasePresenter {
	/** @var PicasaService */
	private $picasaService;
	/** @var Repository */
	private $repository;

	/**
	 * @param PicasaService $picasaService
	 * @internal
	 */
	public function injectPicasaService(PicasaService $picasaService) {
		$this->picasaService = $picasaService;
	}

	/**
	 * @param Repository $repository
	 * @internal
	 */
	public function injectRepository(Repository $repository) {
		$this->repository = $repository;
	}

	/**
	 * Download picasa photos
	 */
	public function actionAlbums() {
		if (!$this->googleApi->isAuthenticated()) {
			$this->redirect('Google:auth');
		} else {
			if ($id = $this->googleApi->getUserId()) {
				$albums = $this->picasaService->getUserAlbums($id);
				// TODO batch?
				foreach ($albums as $album) {
					if ($photos = $this->picasaService->getPhotos($id, $album->getIdAsNumber())) {
						$album = $this->repository->addAlbum($album);
						$this->repository->addPhotos($album, $photos);
					}
				}
			}
			$this->redirect('Photo:albums');
		}
	}
}
