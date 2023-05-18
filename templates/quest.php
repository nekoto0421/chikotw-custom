<div class="row">
<div class="col-4">
<div id="accordion">
<?php
  global $wpdb;
  $sql="SELECT * FROM `{$wpdb->prefix}qa_title` WHERE parent_id is null";
  $titles=$wpdb->get_results($sql);
  if(!empty($titles)){
      foreach($titles as $title){
        $sql="SELECT * FROM `{$wpdb->prefix}qa_title` WHERE parent_id = '".$title->ID."'";
        $subtitles=$wpdb->get_results($sql);
        if(!empty($subtitles)){
          $subhtml="";
          $i=1;
          foreach($subtitles as $subtitle){
            if($i==1){
              $subhtml.='<a class="nav-link active" id="v-pills-'.$subtitle->parent_id.'-'.$subtitle->ID.'-tab" data-toggle="pill" href="#v-pills-'.$subtitle->parent_id.'-'.$subtitle->ID.'" role="tab" aria-controls="v-pills-'.$subtitle->parent_id.'-'.$subtitle->ID.'" aria-selected="true">'.$subtitle->content.'</a>';
            }
            else{
              $subhtml.='<a class="nav-link" id="v-pills-'.$subtitle->parent_id.'-'.$subtitle->ID.'-tab" data-toggle="pill" href="#v-pills-'.$subtitle->parent_id.'-'.$subtitle->ID.'" role="tab" aria-controls="v-pills-'.$subtitle->parent_id.'-'.$subtitle->ID.'" aria-selected="false">'.$subtitle->content.'</a>';
            }
            $i++;
          }
        }
        echo '<div class="card">
            <div class="card-header" id="heading-'.$title->ID.'">
              <h5 class="mb-0">
                <button class="btn btn-link" data-toggle="collapse" data-target="#collapse-'.$title->ID.'" aria-expanded="true" aria-controls="collapse-'.$title->ID.'">
                  '.$title->content.'
                </button>
              </h5>
            </div>
            <div id="collapse-'.$title->ID.'" class="collapse show" aria-labelledby="heading-'.$title->ID.'" data-parent="#accordion">
              <div class="card-body">

                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                  '.$subhtml.'
                </div>
              </div>
            </div>
          </div>';
      }
  }
?>
</div>
</div>
<div class="col-8">
  <div class="tab-content" id="v-pills-tabContent">
    <?php
      $sql="SELECT * FROM {$wpdb->prefix}qa_content";
      $contents = $wpdb->get_results($sql);
      $contentCount = 1;
      $contentHtml = "";
      foreach ($contents as $content) {
        $sql="SELECT ID,parent_id FROM {$wpdb->prefix}qa_title WHERE ID='".$content->qa_id."'";
        $title=$wpdb->get_row($sql);
        if($contentCount==1){
            $contentHtml.='<div class="tab-pane fade show active" id="v-pills-'.$title->parent_id.'-'.$title->ID.'" role="tabpanel" aria-labelledby="v-pills'.$title->parent_id.'-'.$title->ID.'">'.$content->content.'</div>';
        }
        else{
          $contentHtml.='<div class="tab-pane fade" id="v-pills-'.$title->parent_id.'-'.$title->ID.'" role="tabpanel" aria-labelledby="v-pills'.$title->parent_id.'-'.$title->ID.'">'.$content->content.'</div>';
        }
        $contentCount++;
      }
      echo $contentHtml;
    ?>
  </div>
</div>
</div>
