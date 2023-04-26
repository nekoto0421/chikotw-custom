var updateAlbum="";
var oldAlbum="";
jQuery(function($) {
        $( document ).ready(function() {
            if ($(window).width() >= 850) {
                setTimeout(resetAlbum, 500);
            }
        })
        function resetAlbum(){
          let i=1;
          $(".slick-track img").each(function(){
              $("#image"+i).attr("src",$(this).attr("src"));
              //console.log($(this).attr("src"))
              i++;
          });
          oldAlbum = $(".slick-track").html();
          updateAlbum = $(".custom-track").html();
          $(".slick-track").html(updateAlbum);
          //console.log($(".custom-track").html());
          $(".slick-track").css("display","flex");
          $(".slick-track").css("margin-top","50px");
        }
        $(window).resize(function() {
          if ($(window).width() >= 850) {
            resizeAlbum();
          }
          
        });
        function resizeAlbum(){
            setTimeout(fixAlbum, 500);
        }
        function fixAlbum(){
          $(".slick-track").html(updateAlbum);
          $(".slick-track").css("display","flex");
          $(".slick-track").css("margin-top","50px");
          if($(".slick-track").css("width")=="0px"){
            $(".slick-list").css("width","100%");
            $(".slick-track").css("width","100%");
          }
        }
  })