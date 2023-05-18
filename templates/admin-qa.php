<div class="wrap" style="max-width: 500px;margin:50px;">
	<p>短代碼&nbsp;:&nbsp;[chiko type="quest"]</p>
	<?php
  		global $wpdb;
  		$sql="SELECT * FROM {$wpdb->prefix}qa_title WHERE parent_id is null";
  		$titles=$wpdb->get_results($sql);
  		$html="<ul class='list-group'>";
  		$html="<button type='button' class='btn btn-primary btn-add-main'>新增大項</button><br /><br />";
  		if(isset($titles)){
			foreach ($titles as $title) {
				$html.="<li class='list-group-item' data-id='".$title->ID."'>".$title->content."&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-edit-main'>修改名稱</button>&nbsp;&nbsp;<button type='button' class='btn btn-info btn-add-sub'>新增子項</button>&nbsp;&nbsp;<button type='button' class='btn btn-danger btn-del-main' data-toggle='modal'>刪除</button><ul class='list-group'><br />";
				$sql="SELECT * FROM {$wpdb->prefix}qa_title WHERE parent_id = '".$title->ID."'";
				$subtitles=$wpdb->get_results($sql);
				if(!empty($subtitles)){
					foreach($subtitles as $subtitle){
						$html.="<li class='list-group-item' data-id='".$subtitle->ID."'><span class='sub-title'>".$subtitle->content."</span>&nbsp;&nbsp;<button type='button' class='btn btn-success btn-edit-sub' data-toggle='modal'>修改</button>
							<button type='button' class='btn btn-danger btn-del-sub' data-toggle='modal'>刪除</button></li>";
					}
				}
				$html.="</ul></li>";
			}
		}
		$html.="</ul>";
		echo $html;
  	?>
</div>
<!-- 新增sub title model -->
<div class="modal fade" id="subDetailModal" tabindex="-1" role="dialog" aria-labelledby="subDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
		<h2 class="modal-title">更改子項</h2>
      </div>
      <div class="modal-body">
	  	<div class="form-group">
	  		<label for="sub-title-nm" style="font-weight:bold;font-size:1.5em;">子項目標題</label>
			<input type="text" class="form-control" id="sub-title-nm" placeholder="輸入子項目名稱">
			<br/>
			<label for="sub-title-dtl" style="font-weight:bold;font-size:1.5em;">詳細說明</label>
			<?php
				echo wp_editor("","sub-title-dtl",
					array(
				      'media_buttons' => true,
				      'textarea_rows' => 20,
				      'editor_height' => 425,
				      'teeny' => true,
				      'default_editor' => 'Quicktags',
				      'quicktags' => false,
				    )
				);
			?>
	  	</div>
      </div>
      <div class="modal-footer" style="float:right;">
		<button type='button' class='btn btn-primary save-sub' style="float:right;">儲存</button><ul class='list-group'>
      </div>
    </div>
  </div>
</div>
<!-- 新增main title model -->
<div class="modal fade" id="mainDetailModal" tabindex="-1" role="dialog" aria-labelledby="mainDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
		<h2 class="modal-title">更改主項</h2>
      </div>
      <div class="modal-body">
	  	<div class="form-group">
	  		<label for="main-title-nm" style="font-weight:bold;font-size:1.5em;">主項目標題</label>
			<input type="text" class="form-control" id="main-title-nm" placeholder="輸入主項目名稱">
	  	</div>
      </div>
      <div class="modal-footer" style="float:right;">
		<button type='button' class='btn btn-primary save-main' style="float:right;">儲存</button><ul class='list-group'>
      </div>
    </div>
  </div>
</div>