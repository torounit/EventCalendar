<?php
/**
 * Created by PhpStorm.
 * User: torounit
 * Date: 15/02/23
 * Time: 12:55
 */

namespace Torounit\EventCalendar;


class Google_Holiday_Calendar implements Database {

	public static $api_key;

	public static function set_api_key( $key ) {
		self::$api_key = $key;
	}

	/** @var string date format. */
	private $format = 'Y-m-d';

	public function set_format( $format ) {
		$this->format;
	}

	/**
	 * @param int $year
	 *
	 * @return array
	 */
	public function load( $year ) {

		$first_day = mktime( 0, 0, 0, 1, 1, $year );
		$last_day  = strtotime( '-1 day', mktime( 0, 0, 0, 1, 1, $year + 1 ) );

		//$holidays_id = 'outid3el0qkcrsuf89fltf7a4qbacgt9@import.calendar.google.com';  // mozilla.org版
		$holidays_id = 'japanese__ja@holiday.calendar.google.com';  // Google 公式版日本語
		//$holidays_id = 'japanese@holiday.calendar.google.com';  // Google 公式版英語

		$holidays_url = sprintf(
			'https://www.googleapis.com/calendar/v3/calendars/%s/events?' .
			'key=%s&timeMin=%s&timeMax=%s&maxResults=%d&orderBy=startTime&singleEvents=true',
			$holidays_id,
			self::$api_key,
			date( $this->format, $first_day ) . 'T00:00:00Z',  // 取得開始日
			date( $this->format, $last_day ) . 'T00:00:00Z',   // 取得終了日
			31            // 最大取得数
		);

		$results = file_get_contents( $holidays_url );

		return $this->format_to_array( $results );
	}

	/**
	 * @param $json
	 *
	 * @return array
	 */
	public function format_to_array( $json ) {

		$holidays = array();

		if ( $json ) {
			$results = json_decode( $json );

			foreach ( $results->items as $item ) {
				$date  = strtotime( (string) $item->start->date );
				$title = (string) $item->summary;

				$holidays[] = [
					'date' => date( $this->format, $date ),
					'name' => $title
				];
			}
		}

		return $holidays;
	}


}