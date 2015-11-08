<?php

namespace gplusFlickr\google\picasa;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class Parser {
	/**
	 * @param \SimpleXMLElement $content
	 * @return Album[]
	 */
	public function parseAlbums(\SimpleXMLElement $content) {
		dump($content);
		die();
		$albums = [];
		foreach ($content->entry as $albumElement) {
			$data = [];
			foreach (['id', 'title', 'published', 'updated'] as $property) {
				$data[$property] = (string) $albumElement->{$property};
			}

			foreach (['published', 'updated'] as $key) {
				$data[$key] = new \DateTime($data[$key]);
			}
			$albums[] = new Album($data['id'], $data['title'], $data['published'], $data['updated']);
		}
		return $albums;
	}

	/**
	 * @param \SimpleXMLElement $content
	 * @return Photo[]
	 */
	public function parsePhotos(\SimpleXMLElement $content) {
		$photos = [];
		foreach ($content->entry as $photoElement) {
			$photos[] = new Photo(
				(string) $photoElement->id,
				(string) $photoElement->title,
				(string) $photoElement->summary,
				(string) $photoElement->content['src'],
				new \DateTime((string) $photoElement->published),
				new \DateTime((string) $photoElement->updated)
			);
		}
		return $photos;
	}
}
