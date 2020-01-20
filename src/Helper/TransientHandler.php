<?php # -*- coding: utf-8 -*-

namespace WebDevMedia\WpFetchFeedpressPodcastHits\Helper;

/**
 * Handel transients
 * set, create and delete transietn
 *
 * @package wallstreetonline\stockquotes\\Service
 */
class TransientHandler implements TransientInterface {

	/**
	 * The current state of a transient
	 *
	 * @var bool
	 */
	public $status = FALSE;

	/**
	 * The transient key
	 *
	 * @var string
	 */
	private $key = FALSE;

	/**
	 * @param $option_name
	 */
	public function __construct( $prefix ){

		$this->key = $prefix . '_transient';

	}

	/**
	 * Set transient
	 *
	 * @param string $value The value to save
	 * @param int $expire   Time until expiration in seconds. 0 (no expiration).
	 *
	 * @return bool
	 */
	public function set( $value = true, $expire = 0 ) {

		$this->status = set_transient( $this->key, $value, $expire );

		return $this->status;

	}

	/**
	 * Get transient
	 *
	 * @return mixed|void
	 */
	public function get(){

		$this->status = get_transient( $this->key );

		return $this->status;

	}

	/**
	 * Delete transient
	 *
	 * @return bool
	 */
	public function delete(){

		delete_transient( $this->key );

		$this->status = FALSE;

	}

}

