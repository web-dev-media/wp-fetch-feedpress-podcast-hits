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

	/**
	 * Start up
	 */
	public function __construct($hits) {
		$this->setPosts();
		$this->setHits($hits);

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

	private function setHits($hits) {
		$this->hits = $hits;
	}


}