<?php

namespace gplusFlickr\presenter;

use gplusFlickr\db\Info;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class ServicePresenter extends BasePresenter {
	/** @var Info */
	private $info;

	/**
	 * @param Info $info
	 * @internal
	 */
	public function injectDB(Info $info) {
		$this->info = $info;
	}
	/**
	 * Create/empty db
	 */
	public function actionCreateDB() {
		$this->info->create();
		$this->redirect('Homepage:default');
	}
}
