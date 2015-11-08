<?php

namespace gplusFlickr\presenter;

use gplusFlickr\flickr\Api;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class FlickrPresenter extends BasePresenter{
	/**
	 * Redirects to flickr auth url
	 */
	public function actionAuth() {
		$this->redirectUrl($this->flickrAuth->getAuthUrl());
	}

	/**
	 * Flickr Oauth callback
	 */
	public function actionCallback() {
		$this->flickrAuth->authenticate(
			$this->getHttpRequest()->getQuery()
		);
		$this->redirect('Homepage:default');
	}

	/**
	 * Logouts from Flickr
	 */
	public function actionLogout() {
		$this->flickrAuth->logout();
		$this->redirect('Homepage:default');
	}
}
