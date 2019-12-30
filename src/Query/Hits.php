<?php # -*- coding: utf-8 -*-
namespace WebDevMedia\WpFetchFeedpressPodcastHits\Query;

use WebDevMedia\WpFetchFeedpressPodcastHits\Service;

/**
 * Use abstract Query() method to set items for Hotels
 *
 * @package Wildcat\SearchApi\Query
 */
class Hits extends Query {

	public $items;

	public $arguments;

	public function __construct(){

		$this->arguments = [
			'option_name' => 'feedpress_hits',
			'endpoint'    => 'feeds/tracking/items.json',
		];

		$feedpress_option = get_option('feedpress_option');

		if(!empty($feedpress_option['api_key']) && !empty($feedpress_option['api_token'])) {
			$this->items = $this->set_items($this->arguments);
		}
	}
}