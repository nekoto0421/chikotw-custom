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

		if(is_admin()){
			add_action('admin_menu', array($this, 'AdminMenu'), 1);
		}

		add_action( 'woocommerce_cart_calculate_fees', array($this,'chiko_member_discount_price'));

		add_action('wp_ajax_update_member_discount', array($this, 'update_member_discount'));
	}
	function update_member_discount(){
		global $wpdb;
		$sql="SELECT count(*) FROM {$wpdb->prefix}chiko_discount WHERE `member_plan_id`='".$_POST['dataId']."'";

		$check=$wpdb->get_var($sql);
		if($check>0){
			$sql="UPDATE `{$wpdb->prefix}chiko_discount` SET `discount`='".$_POST['disCount']."' WHERE `member_plan_id`='".$_POST['dataId']."'";
		}
		else{
			$sql="INSERT INTO `{$wpdb->prefix}chiko_discount`(`member_plan_id`, `discount`) VALUES ('".$_POST['dataId']."','".$_POST['disCount']."')";
		}
		$result=$wpdb->query($sql);
		if($result){
			echo json_encode(array('success'=>'更新成功'));
			exit();
		}
		else{
			echo json_encode(array('success'=>'更新失敗'));
			exit();
		}
	}

	function chiko_member_discount_price(){
		global $woocommerce,$wpdb;

		$items = $woocommerce->cart->get_cart();
		$regularPrice=0;

		foreach($items as $item => $values) {
			$sql="SELECT `post_parent` FROM `{$wpdb->prefix}posts` WHERE `ID` = '".$values['product_id']."'";
			$parentId=$wpdb->get_var($sql);
			if(isset($parentId)){
				$sql="SELECT `ID` FROM `{$wpdb->prefix}posts` WHERE `ID` = '".$parentId."' and `post_type` = 'hp_listing'";
				$listId=$wpdb->get_var($sql);
				if(isset($listId)){
					$price = $values['data']->get_price();
    				$subtotal = $values['quantity'] * $price;
    				$regularPrice+=$subtotal;
				}
			}
		}
		$discount=0;
		//todo:從自己的資料表撈取折扣
		$sql="SELECT `post_parent`,`ID` FROM `{$wpdb->prefix}posts` WHERE post_type = 'hp_membership' and `post_author` = '".get_current_user_id()."'";
		$result = $wpdb->get_row($sql);
		if(isset($result)){
			$memberId = $result->post_parent;
			$postId =  $result->ID;
			$sql="SELECT meta_value FROM `{$wpdb->prefix}postmeta` where post_id = '".$postId."' and meta_key = 'hp_expired_time'";
			$expireTime=$wpdb->get_var($sql);

			$sql="SELECT discount FROM {$wpdb->prefix}chiko_discount WHERE member_plan_id = '".$memberId."'";

			if(isset($expireTime)){
				date_default_timezone_set('Asia/Taipei');
				$timestamp = time();
				if($expireTime>$timestamp){
					$discount = $wpdb->get_var($sql);
				}
			}
			else{
				$discount = $wpdb->get_var($sql);
			}
		}

		if($discount!=0){
			$discount_price = $regularPrice * $discount / 100;
		}
		else{
			$discount_price = 0;
		}


		if(!empty($discount_price)&&$discount_price!=0){
			$woocommerce->cart->add_fee('會員折抵', -$discount_price, true, 'standard');
		}
	}

	function AdminMenu(){
		add_menu_page('HivePress會員折扣', __('會員折扣'), __('manage_options'), 'chiko-class_main',array($this,'AdminMainPage'),'dashicons-admin-users', 56);
	}
	function AdminMainPage(){
		global $wpdb;
		wp_register_script('sweetalert_script','https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.9/sweetalert2.all.min.js');
		wp_enqueue_script('sweetalert_script');
		wp_register_script('bootstrap_script','https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js');
		wp_enqueue_script('bootstrap_script');

        wp_register_style('bootstrap_style', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css');
		wp_enqueue_style('bootstrap_style');

		wp_register_script('admin_member_discount_script', CHIKO_URL.'js/admin-member-discount.js');
		wp_enqueue_script('admin_member_discount_script');
		wp_localize_script(	
			'admin_member_discount_script', 
			'Chiko_vars',
			array(
				'ajaxurl' => admin_url('admin-ajax.php'),
			)
		);
		
		include CHIKO_DIR."/templates/admin-member-discount.php";
	}

	function list_member(){
		global $wpdb;
		$sql="SELECT * FROM `{$wpdb->prefix}posts` WHERE post_type ='hp_membership_plan'";
		$results = $wpdb->get_results($sql);
		return $results;
	}

	function check_user_member(){
		global $wpdb;
		$sql="SELECT `ID` FROM `{$wpdb->prefix}posts` WHERE post_author='".get_current_user_id()."' post_type ='hp_membership'";
		return $wpdb->get_var($sql);
	}
	function get_user_discount(){
		global $wpdb;
		$memberId = $this->check_user_member();
		$percent = 0;
		if(isset($memberId)){
			$sql="SELECT `meta_value` FROM `{$wpdb->prefix}postmeta` where `post_id` = '".$memberId."' and meta_key = 'hp_expired_time'";
			$expireTime = $wpdb->get_var($sql);
			if(isset($expireTime)){
				$current_timestamp = time();
				if ($expireTime < $current_timestamp) {
				    return 0;
				} 
			}
		}
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
			case 'post-wall':
				$this->PostWallPage();
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

	function PostWallPage(){
		wp_register_script('postermywall_script','https://d1csarkz8obe9u.cloudfront.net/plugins/editor/postermywall-editor-v3.js');
		wp_enqueue_script('postermywall_script');

		wp_register_script('extension_script','chrome-extension://fnjhmkhhmkbjkkabndcnnogagogbneec/in-page.js');
		wp_enqueue_script('extension_script');

		wp_register_script('postermywall_cus_script',CHIKO_URL.'/js/post-my-wall.js');
		wp_enqueue_script('postermywall_cus_script');

		wp_register_style('postermywall_style', CHIKO_URL.'/css/post-my-wall.css');
		wp_enqueue_style('postermywall_style');

		include CHIKO_DIR.'/templates/post-my-wall.php';
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
