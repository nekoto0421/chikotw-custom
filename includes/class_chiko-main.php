<?php
if(!class_exists('chiko_Main')):
class chiko_Main{
	public static $_instance=NULL;
	function __construct() {
		add_shortcode('chiko', array($this, 'ChikoShortCode'));
		add_filter('the_content', array($this,'add_shortcode_to_listing_pages'));
		wp_register_script('jquery','https://code.jquery.com/jquery-3.6.0.js');
		wp_enqueue_script('jquery');
		wp_register_script('album_script',CHIKO_URL.'/js/album.js');
		wp_enqueue_script('album_script');

		wp_register_script('display_script',CHIKO_URL.'/js/display.js');
		wp_enqueue_script('display_script');
	}

	public function ChikoShortCode($args){
		ob_start();
		switch($args['type']){
			case 'member-buy':
				$this->MemberBuyPage();
				break;
			case 'quest':
				$this->QuestPage();
				break;
			case 'album':
				$this->AlbumPage();
				break;
		}
		$output =ob_get_clean();
		return $output;
	}

	function add_shortcode_to_listing_pages($content) {
		$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	    if (strpos($current_url, 'listing') !== false) {
	        $shortcode = '[chiko type="album"]';
	        $content .= do_shortcode($shortcode);
	    }
	    return $content;
	}

	function MemberBuyPage(){
		include CHIKO_DIR.'/templates/custom-member.php';
	} 
	function AlbumPage(){
		include CHIKO_DIR.'/templates/custom-album.php';
	}
	function QuestPage(){
		wp_register_script('bootstrap_script','https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js');
		wp_enqueue_script('bootstrap_script');
		wp_register_style('bootstrap_style', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css');
		wp_enqueue_style('bootstrap_style');
		include CHIKO_DIR.'/templates/quest.php';
	}
	public static function instance(){
		if(is_null(self::$_instance))self::$_instance=new self();
		return self::$_instance;
	}
}
endif;
