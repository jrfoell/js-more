<?php
/*
Plugin Name: Javascript More
Plugin URI: http://9seeds.com/plugins/
Description: View contents in post after &lt;!--more--&gt; without leaving page
Author: 9seeds.com
Version: 1.0
Author URI: http://9seeds.com/
*/

class JS_More {

	private $has_more = false;
	private $js_output = false;
	private $more_text;

	public function onInit() {
		add_filter( 'the_content', array( $this, 'filterContent' ), 7 ); // BEFORE wpautop() (9) and autoembed() (8)
		add_filter( 'the_content_more_link', array( $this, 'filterMore' ), 10, 2 );
		wp_enqueue_script( 'jquery' );
	}

	public function filterContent( $content ) {
		//return $content;
		if ( $this->has_more ) {
			global $page, $pages, $post;
			//restore the content to it's former glory (code from post-template.php)
			if ( $page > count($pages) ) // if the requested page doesn't exist
				$page = count($pages); // give them the highest numbered page that DOES exist
			$content = $pages[$page-1];

			$permalink = get_permalink();
			$content = preg_replace( '/<!--more(.*?)?-->/', "<a href='{$permalink}' id='show-more-{$post->ID}'>{$this->more_text}</a><div id='more-{$post->ID}' style='display: none;'>", $content );
			$content .= "<div><a href='' id='show-less-{$post->ID}'>&larr; Summary Only</a></div></div>";

			if ( ! $this->js_output )
				$content .= '<script src="'.plugins_url( 'js-more.js', __FILE__ ).'" type="text/javascript"></script>';

			//reset for next entry
			$this->js_output = true;
			$this->has_more = false;
		}
		return $content;
	}

	public function filterMore( $link, $text ) {		
		$this->has_more = true;
		$this->more_text = $text;
		return $link;
	}
	
}

$js_more = new JS_More();

add_action( 'init', array( $js_more, 'onInit' ) );
