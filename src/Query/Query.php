<?php # -*- coding: utf-8 -*-

namespace WebDevMedia\WpFetchFeedpressPodcastHits\Query;

use WebDevMedia\WpFetchFeedpressPodcastHits\Helper;

/**
 * Abstract Query class
 * Provides methods to query data
 *
 * @package WebDevMedia\WpFetchFeedpressPodcastHits\Query
 */
abstract class Query {

	public $items;
	public $arguments;
	public $transient;

	/**
	 * @param array                   $args
	 * @param Helper\TransientHandler $transient
	 *
	 * @return array
	 */
	public function set_items( array $args, Helper\TransientHandler $transient): array {
		$optionStorageHandler = new Helper\OptionStorageHandler($args['option_name']);
		$items = $optionStorageHandler->get();

		$cacheTime = 5 * HOUR_IN_SECONDS;

		if (empty($items) || (time() - $transient->get() > $cacheTime)) {
			$transient->set( time(), $cacheTime ); // cache response for 5 hours
			new Helper\Request( $args );
			$items = $optionStorageHandler->get();
		}

		return $items;
	}

	/**
	 * get the ibes
	 */
	public function get_items() {

		$this->items = $this->set_items($this->arguments);

		return $this->items;

	}

	/**
	 * get a ibe by there id
	 */
	public function get_item( $id ) {
		$ibe = FALSE;

		if (!empty($id)) {
			$items = $this->get_items();

			foreach ($items as $item) {
				if (!empty($item->id) && $item->id == $id) {
					$ibe = $item;
					break;
				}
			}
		}

		return $ibe;

	}

}