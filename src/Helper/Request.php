<?php # -*- coding: utf-8 -*-

namespace WebDevMedia\WpFetchFeedpressPodcastHits\Helper;


/**
 * The request class authenticate with a token and
 * call data from the feesdpress API
 *
 * @package WebDevMedia\WpFetchFeedpressPodcastHits\Service
 */
class Request {

	/**
	 * simple the login data
	 *
	 * @var string | json
	 */

	/**
	 * The URL to the API
	 *
	 * @var string
	 */
	private $base_route = 'https://api.feed.press/';

	/**
	 * The option name where is used in wordpress to store
	 * data in the database
	 *
	 * @var bool
	 */
	private $option_name = FALSE;


	private $transient;
	private $feedpress_option;
	private $FeedTypes = ['mp3', 'ogg', 'm4a'];

	/**
	 * Set some information about the request.
	 *
	 * @param $args array [ option_name, endpoint ]
	 * @example get_items( [ 'option_name' => 'feedpress_hits', 'endpoint' => 'account.json' ] ] )
	 *
	 */
	public function __construct( array $args ) {

		$this->option_name = $args['option_name'];
		$this->endpoint = $args['endpoint'];

		$this->feedpress_option = get_option('feedpress_option');

		if(!empty($this->feedpress_option)) {
			$this->request();
		}

	}

	/**
	 * Prepare the requests
	 *
	 * @param bool|FALSE $action
	 *
	 * @return bool
	 */
	private function request( $action = FALSE ) {
		$reponse = $this->get_remote( $this->endpoint );
		
		if(!is_wp_error($reponse)) {
			$reponse['timestamp'] = time();
			$handle = new OptionStorageHandler( $this->option_name );
			$handle->update( $reponse );
		}

		return true;

	}

	/**
	 * Do a request
	 *
	 * @param $endpoint
	 * @param $args
	 *
	 * @return array|mixed|object|\WP_Error
	 */
	private function get_remote( string $endpoint ){
		$items = [];
		$args[ 'headers' ][ 'Content-type' ] = "application/json";

		foreach ($this->FeedTypes as $FeedType){
			$response = wp_remote_get(
				$this->base_route . $endpoint . '?key=' . $this->feedpress_option['api_key'] . '&token=' . $this->feedpress_option['api_token'] . '&feed=wpsofa-' . $FeedType,
				$args
			);

			$response_body = json_decode( wp_remote_retrieve_body( $response ) );

			if(!empty($response_body->items)){
				$items[$FeedType] = $response_body->items;
			}

			new HitsToPosts($items);
		}

		$WP_Error = new \WP_Error( 'no Data', "No data" );

		return !empty($items) ? $items : $WP_Error;

	}

}