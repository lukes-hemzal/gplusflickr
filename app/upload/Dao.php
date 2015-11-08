<?php

namespace gplusFlickr\upload;

use gplusFlickr\db\Info,
	gplusFlickr\db\Utils,
	Nette\Database\Context,
	Nette\Database\IRow;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class Dao {
	/** @var string */
	private $table = Info::GOOGLE_FLICKR_ALBUM;

	/** @var Context */
	private $database;
	/** @var Utils */
	private $utils;

	public function __construct(
		Context $database,
		Utils $utils
	) {
		$this->database = $database;
		$this->utils = $utils;
	}

	/**
	 * @param AlbumConnection $connection
	 * @return AlbumConnection
	 */
	public function saveAlbumConnection(AlbumConnection $connection) {
		$data = [
			'googleAlbumId' => $connection->getPicasaId(),
			'flickrAlbumId' => $connection->getFlickrId(),
			'created' => $this->utils->timeToFieldValue($connection->getCreated()),
			'updated' => $this->utils->timeToFieldValue($connection->getUpdated())
		];
		$this->database->query(
			"INSERT INTO {$this->table}", $data
		);
		return $connection;
	}

	/**
	 * @param int $id
	 * @return AlbumConnection
	 */
	public function getAlbumConnectionByGoogleId($id) {
		$result = $this->database->query("SELECT * FROM {$this->table} WHERE [googleAlbumId] = ?", $id);
		if ($row = $result->fetch()) {
			return $this->createConnection($row);
		}
	}

	/**
	 * @param IRow $row
	 * @return AlbumConnection
	 */
	private function createConnection(IRow $row) {
		return new AlbumConnection(
			$row->googleAlbumId, $row->flickrAlbumId,
			$this->utils->fieldValueToTime($row->created),
			$this->utils->fieldValueToTime($row->updated)
		);
	}
}
