<?php
namespace Torounit\EventCalendar;

interface Storage {


	/**
	 * @param int $year
	 *
	 * @return array
	 */
	public function get( $year );


	/**
	 * @param int $year
	 * @param Array $data
	 *
	 */
	public function set( $year, Array $data );

}