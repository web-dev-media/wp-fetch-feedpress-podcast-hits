<?php

namespace WebDevMedia\WpFetchFeedpressPodcastHits;

class HitsToPosts {

	/**
	 * @var array
	 */
	private $posts;

	/**
	 * fetched hits from feedpress
	 * @var array
	 */
	private $hits;

	/**
	 * Start up
	 */
	public function __construct() {
		$this->setPosts();
		$this->setHits();

		if(!empty($this->posts) && !empty($this->hits)){
			$this->hitsToPost();
		}
	}


	private function hitsToPost(){
		/** @var \WP_Post $post **/
		foreach ($this->posts as $post){
			$hits = 0;

			foreach ($this->hits as $hitGroupName =>$hitGroup){
				if(!empty($hitGroup) && $hitGroupName != 'timestamp') {
					foreach ($hitGroup as $hit) {
						if ($post->post_title === $hit->title) {
							$hits = $hits + $hit->hits;

						}
					}
				}
			}

			update_post_meta($post->ID, 'feedpress_hits', $hits);
		}
	}

	private function setPosts() {
		$posts = get_posts([
			                    'numberposts'      => -1,
			                    'post_type'        => 'dipo_podcast',
			                    'post_status'      => 'publish,'
		                    ]);

		if(!empty($posts)){
			$this->posts = $posts;
		}
	}

	private function setHits( ) {
		$this->hits = (new Query\Hits())->get_items();
	}


}