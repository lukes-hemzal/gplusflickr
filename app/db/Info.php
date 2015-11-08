<?php

namespace gplusFlickr\db;

use Nette\Database\Context;

/**
 * Helper class for database info
 *
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class Info {
	/** @var Context */
	private $db;

	const GOOGLE_ALBUM = 'googleAlbum';
	const GOOGLE_PHOTO = 'googlePhoto';
	const GOOGLE_RELATION = 'googleRelation';
	const FLICKR_ALBUM = 'flickrAlbum';
	const FLICKR_PHOTO = 'flickrPhoto';
	const FLICKR_RELATION = 'flickrRelation';
	const GOOGLE_FLICKR_ALBUM = 'googleFlickrAlbum';
	const GOOGLE_FLICKR_SEARCH = 'googleFlickrSearch';

	public function __construct(
		Context $db
	) {
		$this->db = $db;
	}

	/**
	 * Create/empty sqlite database
	 */
	public function create() {
		$googleAlbumTable = self::GOOGLE_ALBUM;
		$googlePhotoTable = self::GOOGLE_PHOTO;
		$googleRelationTable = self::GOOGLE_RELATION;
		$flickrAlbumTable = self::FLICKR_ALBUM;
		$flickrPhotoTable = self::FLICKR_PHOTO;
		$flickrRelationTable = self::FLICKR_RELATION;
		$googleFlickrAlbumTable = self::GOOGLE_FLICKR_ALBUM;
		$googleFlickrSearchTable = self::GOOGLE_FLICKR_SEARCH;

		// create albums
		$this->db->query(
			"CREATE TABLE IF NOT EXISTS {$googleAlbumTable}(
				[id] INTEGER PRIMARY KEY,
				[googleId] TEXT UNIQUE, -- google id is string?
				[userId] INTEGER,
				[title] TEXT,
				[published] TEXT,
				[updated] TEXT
			)"
		);
		// create photos
		$this->db->query(
			"CREATE TABLE IF NOT EXISTS {$googlePhotoTable}(
				[id] INTEGER PRIMARY KEY,
				[googleId] TEXT UNIQUE,-- google id is url
				[title] TEXT,
				[summary] TEXT,
				[source] TEXT,
				[published] TEXT,
				[updated] TEXT
			)"
		);
		// create relation table
		$this->db->query(
			"CREATE TABLE IF NOT EXISTS {$googleRelationTable}(
				[id] INTEGER PRIMARY KEY,
				[albumId] INTEGER,
				[photoId] INTEGER,
				[order] INTEGER,
				FOREIGN KEY([albumId]) REFERENCES {$googleAlbumTable}([id])
				FOREIGN KEY([photoId]) REFERENCES {$googlePhotoTable}([id])
				UNIQUE ([albumId], [photoId])
				UNIQUE ([albumId], [order])
			)"
		);
		// create flickr photos
		$this->db->query(
			"CREATE TABLE IF NOT EXISTS {$flickrPhotoTable} (
				[id] INTEGER PRIMARY KEY,
				[flickrId] INTEGER UNIQUE,
				[owner] TEXT,
				[secret] TEXT,
				[server] INTEGER,
				[farm] INTEGER,
				[title] TEXT,
				[description] TEXT,
				[ispublic] INTEGER,
				[isfriend] INTEGER,
				[isfamily] INTEGER,
				[url] TEXT, -- original url
				[width] INTEGER, -- original width
				[height] INTEGER -- original height
			)"
		);
		// create flickr albums
		$this->db->query(
			"CREATE TABLE IF NOT EXISTS {$flickrAlbumTable} (
				[id] INTEGER PRIMARY KEY,
				[flickrId] INTEGER UNIQUE,
				[title] TEXT,
				[description] TEXT
			)"
		);
		// create relation table
		$this->db->query(
			"CREATE TABLE IF NOT EXISTS {$flickrRelationTable}(
				[id] INTEGER PRIMARY KEY,
				[albumId] INTEGER,
				[photoId] INTEGER,
				[order] INTEGER,
				FOREIGN KEY([albumId]) REFERENCES {$flickrAlbumTable}([id])
				FOREIGN KEY([photoId]) REFERENCES {$flickrPhotoTable}([id])
				UNIQUE ([albumId], [photoId])
				UNIQUE ([albumId], [order])
			)"
		);
		// create search table
		$this->db->query(
			"CREATE TABLE IF NOT EXISTS {$googleFlickrSearchTable}(
				[id] INTEGER PRIMARY KEY,
				[googlePhotoId] INTEGER,
				[flickrPhotoId] INTEGER,
				[valid] INTEGER DEFAULT 1,
				FOREIGN KEY([googlePhotoId]) REFERENCES {$googlePhotoTable}([id])
				FOREIGN KEY([flickrPhotoId]) REFERENCES {$flickrPhotoTable}([id])
				UNIQUE ([googlePhotoId], [flickrPhotoId])
			)"
		);
		// create album connection table
		$this->db->query(
			"CREATE TABLE IF NOT EXISTS {$googleFlickrAlbumTable}(
				[id] INTEGER PRIMARY KEY,
				[googleAlbumId] INTEGER,
				[flickrAlbumId] INTEGER,
				[created] TEXT,
				[updated] TEXT,
				FOREIGN KEY([googleAlbumId]) REFERENCES {$googleAlbumTable}([id])
				FOREIGN KEY([flickrAlbumId]) REFERENCES {$flickrAlbumTable}([id])
				UNIQUE ([googleAlbumId], [flickrAlbumId])
			)"
		);

		// empty tables
		$this->db->query("DELETE FROM {$googleFlickrAlbumTable}");
		$this->db->query("DELETE FROM {$googleFlickrSearchTable}");
		$this->db->query("DELETE FROM {$flickrRelationTable}");
		$this->db->query("DELETE FROM {$flickrPhotoTable}");
		$this->db->query("DELETE FROM {$flickrAlbumTable}");
		$this->db->query("DELETE FROM {$googleRelationTable}");
		$this->db->query("DELETE FROM {$googlePhotoTable}");
		$this->db->query("DELETE FROM {$googleAlbumTable}");
		$this->db->query("VACUUM");
	}
}
