<?php
namespace WebDevMedia\WpFetchFeedpressPodcastHits\Helper;

/**
 * Add hits rest api endpoint
 *
 * @package WebDevMedia\WpFetchFeedpressPodcastHits
 */
class HitsRestEndpoint {

	public function __construct() {
		add_action( 'rest_api_init', function () {
			$version = '1';
			$namespace = 'wpffph/v' . $version;
			$base = 'hits';
			register_rest_route( $namespace, '/' . $base, [
				'methods' => 'POST',
				'callback' => [$this, 'get_hits_by_post_ids'],
			] );
		} );
	}

	/**
	 * @param \WP_REST_Request $request
	 */
	public function get_hits_by_post_ids( \WP_REST_Request $request ): string {
		$post_ids = $request->get_body_params()['ids'];
		$values = [];

		if(!empty($post_ids)){
			foreach (explode(',', $post_ids) as $post_id) {
				$hits = get_post_meta($post_id, 'feedpress_hits', TRUE);

				if(!empty($hits)){
					$values[$post_id] = $hits;
				}
			}
		}

		return json_encode($values);
	}
}