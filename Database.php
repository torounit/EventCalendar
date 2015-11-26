<?php
namespace Torounit\EventCalendar;

interface Database {


	/**
	 * @param $year
	 * @param $monthnum
	 *
	 * @return array
	 */
	public function load( $year );

    /**
     * @param string
     */
	public function set_format( $format );
}