<?php
namespace Torounit\EventCalendar;

class Factory {

	/** @var Calendar[] */
	private static $calendars = [ ];

	/**
	 * @param $year
	 * @param $monthnum
	 *
	 * @return Calendar
	 */
	public static function create( $year ) {

		$key = intval( $year );

		if ( ! isset( self::$calendars[ $key ] ) ) {

			$calendar = new Calendar( $year );
			Google_Calendar::set_api_key( GOOGLE_API_KEY );
			$calendar->set_db( new Google_Calendar() );
			$calendar->init();

			self::$calendars[ $key ] = $calendar;
		}

		return self::$calendars[ $key ];
	}


}
