<?php

namespace WebDevMedia\WpFetchFeedpressPodcastHits\Helper;

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

	private $runtime;
	
	/**
	 * Start up
	 */
	public function __construct($hits) {
		if(empty($this->posts)) {
			$this->setPosts();
		

		if(!empty($this->posts) && !empty($hits)){
			$this->setHits($hits);
			$this->hitsToPost();

			echo '<pre>';
			print_r([
				        'DEBUG_LOCATION' => ['PATH' => dirname(__FILE__), 'FILE' => basename(__FILE__), 'FUNCTION' => __FUNCTION__ . ':' . __LINE__],
				        'DEBUG'          => [
					        '$this->posts' => 'fff',
				        ]
			        ]);
		}
		}
	}


	private function hitsToPost(){
		/** @var \WP_Post $post **/
		foreach ($this->posts as $post){
			$hits = 0;

			foreach ($this->hits as $hitGroupName =>$hitGroup){
				if(!empty($hitGroup) && $hitGroupName != 'timestamp') {
					foreach ($hitGroup as $hit) {
						$mediafiles = [
							get_post_meta($post->ID, '_dipo_mediafile1', true),
							get_post_meta($post->ID, '_dipo_mediafile2', true),
							get_post_meta($post->ID, '_dipo_mediafile3', true),
						];

						foreach($mediafiles as $mediafile){
							if ($mediafile['medialink'] === $hit->url ) {
								$hits = $hits + $hit->hits;
							}
						}
					}
				}
			}

			update_post_meta($post->ID, 'feedpress_hits', $hits);
		}


		$this->runtime = md5(json_encode($this->posts));
	}

	private function setPosts() {
		$posts = get_posts([
			                   'numberposts' => - 1,
			                   'post_type'   => 'dipo_podcast',
			                   'post_status' => 'publish,'
		                   ]);

		if (!empty($posts)) {
			$this->posts = $posts;
		}
	}

	private function setHits($hits) {
		$this->hits = $hits;
	}


}