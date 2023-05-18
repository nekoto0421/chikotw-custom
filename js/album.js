var updateAlbum="";
var oldAlbum="";
jQuery(function($) {
  $( document ).ready(function() {
    if ($(window).width() >= 850) {
      resetAlbum();
    }
  })
  function resetAlbum(){
    let i=1;
    $(".hp-grid__item img").each(function(){
      if(i<=5){
        if($(this).attr("src")!=""){
          $("#image"+i).attr("src",$(this).attr("src"));
        }
        else{
          $("#image"+i).attr("src","https://si.chikotw.com/wp-content/uploads/2023/05/社區預設圖.jpg");
        }
      }
      i++;
    });
    
    $(".custom-img").each(function(){
      if($(this).attr("src")==""){
        $(this).attr("src","https://si.chikotw.com/wp-content/uploads/2023/05/社區預設圖.jpg");
      }
    });
    
    $(".hp-listing__images").remove();
    $(".hp-page__topbar").after($(".custom-track-out").html());
  }
})