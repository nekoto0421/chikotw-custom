<?php
global $wpdb;
$sql = "SELECT a.ID,a.post_title,b.discount FROM `{$wpdb->prefix}posts` a
left join `{$wpdb->prefix}chiko_discount` b 
on a.ID = b.member_plan_id 
WHERE a.post_type='hp_membership_plan'";
$results = $wpdb->get_results($sql);
?>
<div class="wrap" style="max-width: 500px;margin:50px;">
	<table class="table table-striped">
	  <thead>
	    <tr>
	      <th scope="col">會員計畫名稱</th>
	      <th scope="col">會員折扣(%)</th>
	    </tr>
	  </thead>
	  <tbody>
	  	<?php
	  		if(isset($results)){
				foreach ($results as $result) {
					echo '<tr data-id="'.$result->ID.'">
					      	<td>'.$result->post_title.'</td>
					      	<td>
					      		<input type="number" value="'.$result->discount.'"/>
					      		<button type="button" class="btn btn-primary save-discount">儲存</button>
					      	</td>
					      </tr>';
				}
			}
	  	?>
	  </tbody>
	</table>
</div>