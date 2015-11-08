<?php

namespace gplusFlickr\presenter;

use gplusFlickr\google\Api as GoogleApi,
	gplusFlickr\flickr\Auth as FlickrAuth,
	Nette\Application\UI\Presenter;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
abstract class BasePresenter extends Presenter {
	/** @var GoogleApi */
	protected $googleApi;
	/** @var FlickrAuth */
	protected $flickrAuth;

	/**
	 * @param GoogleApi $googleApi
	 * @internal
	 */
	public function injectGoogleApi(GoogleApi $googleApi) {
		$this->googleApi = $googleApi;
	}

	/**
	 * @param FlickrAuth $flickrAuth
	 * @internal
	 */
	public function injectFlickrAuth(FlickrAuth $flickrAuth) {
		$this->flickrAuth = $flickrAuth;
	}

	/**
	 * @return void
	 */
	public function startup() {
		$this->flickrAuth->init();
		parent::startup();
	}

	/**
	 * @return string
	 */
	private function getTemplateDir() {
		return \gplusFlickr\App::getDir('resources');
	}

	/**
	 * @return array
	 */
	public function formatTemplateFiles() {
		$name = $this->getName();
		$presenter = substr($name, strrpos(':' . $name, ':'));
		$dir = $this->getTemplateDir();
		return [
			"$dir/templates/$presenter/$this->view.latte",
			"$dir/templates/$presenter.$this->view.latte",
		];
	}


	/**
	 * Formats layout template file names.
	 * @return array
	 */
	public function formatLayoutTemplateFiles() {
		$name = $this->getName();
		$presenter = substr($name, strrpos(':' . $name, ':'));
		$layout = $this->layout ? $this->layout : 'layout';
		$dir = $this->getTemplateDir();
		$list = [
			"$dir/templates/$presenter/@$layout.latte",
			"$dir/templates/$presenter.@$layout.latte",
		];
		do {
			$list[] = "$dir/templates/@$layout.latte";
			$dir = dirname($dir);
		} while ($dir && ($name = substr($name, 0, strrpos($name, ':'))));
		return $list;
	}

}
