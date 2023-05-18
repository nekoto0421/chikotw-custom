<?php
if(!class_exists('chiko_Main')):
class chiko_Main{
	public static $_instance=NULL;
	function __construct() {
		add_shortcode('chiko', array($this, 'ChikoShortCode'));
		add_filter('the_content', array($this,'add_shortcode_to_listing_pages'));
		wp_register_script('jquery','https://code.jquery.com/jquery-3.6.0.js');
		wp_enqueue_script('jquery');
      
      	$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	    if (strpos($current_url, 'listing') !== false) {
	       wp_register_script('album_script',CHIKO_URL.'/js/album.js');
		   wp_enqueue_script('album_script');
	    }
		

		wp_register_script('display_script',CHIKO_URL.'/js/display.js');
		wp_enqueue_script('display_script');

		if(is_admin()){
			add_action('admin_menu', array($this, 'AdminMenu'), 1);
		}

		add_action( 'woocommerce_cart_calculate_fees', array($this,'chiko_member_discount_price'));

		add_action('wp_ajax_update_member_discount', array($this, 'update_member_discount'));

		add_action('wp_ajax_update_sub_data', array($this, 'update_sub_data'));

		add_action('wp_ajax_get_sub_data', array($this, 'get_sub_data'));

		add_action('wp_ajax_del_sub_data', array($this, 'del_sub_data'));

		add_action('wp_ajax_get_main_data', array($this, 'get_main_data'));

		add_action('wp_ajax_update_main_data', array($this, 'update_main_data'));

		add_action('wp_ajax_del_main_data', array($this, 'del_main_data'));
	}
	function del_main_data(){
		global $wpdb;
		$sql = "SELECT * FROM `{$wpdb->prefix}qa_title` WHERE parent_id = '".$_POST['ID']."'";
		$datas = $wpdb->get_results($sql);
		$sql="DELETE FROM `{$wpdb->prefix}qa_title` WHERE ID='".$_POST['ID']."' OR parent_id = '".$_POST['ID']."'";
		$wpdb->query($sql);
		foreach($datas as $data){
			$sql="DELETE FROM `{$wpdb->prefix}qa_content` WHERE qa_id='".$data->ID."'";
			$wpdb->query($sql);
		}
		echo json_encode(array('success'=>'刪除成功'));
		exit();
	}
	function get_main_data(){
		global $wpdb;
		$sql="SELECT content FROM {$wpdb->prefix}qa_title where ID='".$_POST['ID']."'";
		$maindata = $wpdb->get_row($sql);
		echo json_encode(array('mainTitle'=>$maindata->content));
		exit();
	}
	function update_main_data(){
		global $wpdb;
		if(empty($_POST['ID'])){
			$sql="INSERT INTO `{$wpdb->prefix}qa_title`(`content`) VALUES ('".$_POST['mainTitle']."')";
			$wpdb->query($sql);
		}
		else{
			$sql="UPDATE `{$wpdb->prefix}qa_title` SET `content`='".$_POST['mainTitle']."' WHERE `ID`='".$_POST['ID']."'";
			$wpdb->query($sql);
		}
		echo json_encode(array('success'=>'更新成功','title'=>$_POST['mainTitle']));
		exit();
	}
	function del_sub_data(){
		global $wpdb;
		$sql="DELETE FROM `{$wpdb->prefix}qa_title` WHERE ID='".$_POST['ID']."'";
		$wpdb->query($sql);
		$sql="DELETE FROM `{$wpdb->prefix}qa_content` WHERE qa_id='".$_POST['ID']."'";
		$wpdb->query($sql);
		echo json_encode(array('success'=>'刪除成功'));
		exit();
	}
	function get_sub_data(){
		global $wpdb;
		$sql="SELECT a.content as title,b.content as editorContent  FROM {$wpdb->prefix}qa_title a
		left join {$wpdb->prefix}qa_content b 
		on a.ID = b.qa_id 
		where a.ID='".$_POST['ID']."'";
		$subdata = $wpdb->get_row($sql);
		echo json_encode(array('subTitle'=>$subdata->title,'editorContent'=>$subdata->editorContent));
		exit();
	}

	function update_sub_data(){
		global $wpdb;
		$editID=$_POST['ID'];
		if(empty($_POST['ID'])){
			$sql="INSERT INTO `{$wpdb->prefix}qa_title`(`parent_id`,`content`) VALUES ('".$_POST['mainId']."','".$_POST['subTitle']."')";
			$wpdb->query($sql);
			$sql="SELECT `ID` FROM `{$wpdb->prefix}qa_title` WHERE `parent_id`='".$_POST['mainId']."' and content = '".$_POST['subTitle']."'";
			$ID=$wpdb->get_var($sql);
			$editID=$ID;
		}
		else{
			$sql="UPDATE `{$wpdb->prefix}qa_title` SET `content`='".$_POST['subTitle']."' WHERE `ID`='".$editID."'";
			$wpdb->query($sql);
		}
		$sql= "SELECT count(*) FROM {$wpdb->prefix}qa_content WHERE qa_id='".$editID."'";
		$count = $wpdb-> get_var($sql);
		if($count == 0){
			$sql="INSERT INTO `{$wpdb->prefix}qa_content`(`qa_id`, `content`) VALUES ('".$editID."','".$_POST['editorContent']."')";
		}
		else{
			$sql="UPDATE `{$wpdb->prefix}qa_content` SET `content`='".$_POST['editorContent']."' WHERE `qa_id`='".$editID."'";
		}
		$wpdb->query($sql);
		echo json_encode(array('success'=>'更新成功','title'=>$_POST['subTitle']));
		exit();
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

		add_menu_page('QA設定', __('QA設定'), __('manage_options'), 'qa-class_main',array($this,'QAPage'),'dashicons-editor-ul', 56);
	}

	function QAPage(){
		global $wpdb;
		wp_register_script('sweetalert_script','https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.9/sweetalert2.all.min.js');
		wp_enqueue_script('sweetalert_script');
		wp_register_script('bootstrap_script','https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js');
		wp_register_script('bootstrap_script','https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js');
		wp_enqueue_script('bootstrap_script');
		wp_register_style('bootstrap_style', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css');
		wp_enqueue_style('bootstrap_style');

		wp_register_script('admin_qa_script', CHIKO_URL.'js/admin-qa.js');
		wp_enqueue_script('admin_qa_script');
		wp_localize_script(	
			'admin_qa_script', 
			'Chiko_vars',
			array(
				'ajaxurl' => admin_url('admin-ajax.php'),
			)
		);
		include CHIKO_DIR."/templates/admin-qa.php";
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
