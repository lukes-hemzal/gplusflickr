<?php

namespace gplusFlickr\presenter;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class HomepagePresenter extends BasePresenter {

	public function renderDefault() {
		$this->template->googleAuth = $this->googleApi->isAuthenticated();
		$this->template->flickrAuth = $this->flickrAuth->isAuthenticated();
	}
}
