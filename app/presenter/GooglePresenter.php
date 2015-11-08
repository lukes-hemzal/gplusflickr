<?php

namespace gplusFlickr\presenter;

use gplusFlickr\google\Api;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class GooglePresenter extends BasePresenter {

	/**
	 * Redirects to google auth url
	 */
	public function actionAuth() {
		$this->redirectUrl($this->googleApi->getAuthUrl());
	}

	public function actionLogout() {
		$this->googleApi->logout();
		$this->redirect('Homepage:default');
	}

	public function actionCallback() {
		$this->googleApi->authenticate($this->getHttpRequest()->getQuery('code'));
		// TODO parametrize
		$this->redirect('Homepage:default');
	}
}
