<?php
namespace Torounit\EventCalendar;

class Calendar {


	/** @const string date format. */
	const DATE_FORMAT = 'Y-m-d';

	/** @var array */
	private $holidays = [ ];


	/** @var Database */
	private $db;

	/** @var  Storage */
	private $storage;

	/** @var int */
	public $year;


	public function __construct( $year ) {
		$this->set_date( $year );
	}


	public function init() {
		$this->holidays = $this->get_days();
	}


	/**
	 * @param $year
	 */
	private function set_date( $year ) {
		$this->year = intval( $year );
	}

	/**
	 * @param Database $db
	 */
	public function set_db( Database $db ) {
		$this->db = $db;
		$this->db->set_format( self::DATE_FORMAT );
	}


	/**
	 * @param Storage $storage
	 */
	public function set_storage( Storage $storage ) {
		$this->storage = $storage;
	}


	/**
	 * @return array
	 */
	public function get_days() {
		$data = array();
		if ( ! empty( $this->storage ) ) {
			$data = $this->storage->get( $this->year );
		}

		if ( empty( $data ) ) {
			$data = $this->array_format( $this->db->load( $this->year ) );

			if ( ! empty( $this->storage ) ) {
				$this->storage->set( $this->year, $data );
			}
		}

		return $data;
	}

	/**
	 * @param array $array
	 *
	 * @return array
	 */
	private function array_format( Array $array ) {

		$dates = array_map( function ( $data ) {
			return $data['date'];
		}, $array );

		$names = array_map( function ( $data ) {
			return $data['name'];
		}, $array );

		return array_combine( $dates, $names );
	}


	/**
	 * @param $day
	 *
	 * @return bool|string
	 */
	public function get_day_name( $monthnum, $day ) {
		$timestamp = mktime( 0, 0, 0, $monthnum, $day, $this->year );
		$key       = date( self::DATE_FORMAT, $timestamp );

		if ( isset( $this->holidays[ $key ] ) ) {
			return $this->holidays[ $key ];
		}

		return false;
	}

	/**
	 * @param $monthnum
	 * @param $day
	 *
	 * @return bool
	 */
	public function is_holiday( $monthnum, $day ) {
		return ! ! $this->get_day_name( $monthnum, $day );
	}


}