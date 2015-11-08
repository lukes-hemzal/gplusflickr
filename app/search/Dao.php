<?php

namespace gplusFlickr\search;

use Nette\Database\Context,
	gplusFlickr\db\Info;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class Dao {
	/** @var Context */
	private $database;
	/** @var Factory */
	private $factory;

	/** @var string */
	private $table = Info::GOOGLE_FLICKR_SEARCH;


	public function __construct(
		Context $database,
		Factory $factory
	) {
		$this->database = $database;
		$this->factory = $factory;
	}

	/**
	 * Save new
	 *
	 * @param Result $searchResult
	 * @return Result
	 */
	public function saveResult(Result $searchResult) {
		$data = [
			'googlePhotoId' => $searchResult->getPicasaPhoto()->getId(),
			'flickrPhotoId' => $searchResult->getFlickrPhoto()->getId()
		];
		$this->database->query("INSERT INTO {$this->table}", $data);
		return $searchResult;
	}

	/**
	 * @param int $picasaId
	 * @param bool $validOnly
	 * @return Result[]
	 */
	public function getResults($picasaId, $validOnly = true) {
		$sql = "SELECT * FROM {$this->table} WHERE googlePhotoId = ?";

		if ($validOnly) {
			$sql .= " AND valid = 1";
		}
		$result = $this->database->query(
			$sql,
			$picasaId
		);

		$results = [];
		while ($row = $result->fetch()) {
			$results[] = $this->factory->createFromRow($row);
		}
		return $results;
	}

	/**
	 * @param int $picasaId
	 * @param int $flickrId
	 * @return Result
	 */
	public function getResult($picasaId, $flickrId) {
		$result = $this->database->query(
			"SELECT * FROM {$this->table} WHERE googlePhotoId = ? AND flickrPhotoId = ?",
			$picasaId, $flickrId
		);

		if ($row = $result->fetch()) {
			return $this->factory->createFromRow($row);
		}
	}

	/**
	 * @param int $picasaId
	 * @param int $flickrId
	 * @param bool $valid
	 */
	public function setValidState($picasaId, $flickrId, $valid) {
		$this->database->query(
			"UPDATE {$this->table} SET valid = ? WHERE googlePhotoId = ? AND flickrPhotoId = ?",
			$valid ? 1 : 0, $picasaId, $flickrId
		);
	}

}
