<?php

namespace gplusFlickr\db;

/**
 * @author Lukes Hemzal <lukes@hemzal.com>
 */
class Utils {
	/** @var string */
	private static $timeFormat = 'Y-m-d\TH:i:s.u T';

	/**
	 * @param string $value
	 * @return \DateTime
	 */
	public function fieldValueToTime($value) {
		return new \DateTime($value);
	}

	/**
	 * @param \DateTime $time
	 * @return string
	 */
	public function timeToFieldValue(\DateTime $time) {
		return $time->format(static::$timeFormat);
	}
}
