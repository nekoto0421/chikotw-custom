var subId,mainId;
jQuery(function($) {
	$(document).on("click", ".btn-del-main", function() {
		let delId=$(this).closest("li").attr("data-id");
		$.ajax({
            type: 'POST',
            data: {action:"del_main_data",ID:delId},
            dataType: 'json',
            url: Chiko_vars.ajaxurl,
          }).done(function(data) {
              if(data.success){
                  Swal.fire({
                    icon: 'success',
                    title: data.success,
                    showConfirmButton: false,
                    timer: 1500
                  })
                  $("li[data-id='"+delId+"']").remove();
              }
              else{
                  Swal.fire({
                    icon: 'error',
                    text: data.error,
                  })
              }
          }).fail(function(response, textStatus, errorThrown) {
              console.log('fail', response);
          });
	})
	$(document).on("click", ".btn-edit-main", function() {
		mainId = $(this).closest("li").attr("data-id");
		$.ajax({
	        type: 'POST',
	        data: {action:"get_main_data",ID:mainId},
	        dataType: 'json',
	        url: Chiko_vars.ajaxurl,
	      }).done(function(data) {
	      	  console.log(data);
	      	  $("#main-title-nm").val(data.mainTitle);
	          $("#mainDetailModal").modal("toggle");
	      }).fail(function(response, textStatus, errorThrown) {
	          console.log('fail', response);
	      });
	})
	$(document).on("click", ".btn-add-main", function() {
		mainId="";
		$("#main-title-nm").val("");
		$("#mainDetailModal").modal("toggle");
	})
	$(document).on("click", ".save-main", function() {
		let mainTitle = $("#main-title-nm").val();
		$.ajax({
            type: 'POST',
            data: {action:"update_main_data",ID:mainId,mainTitle: mainTitle},
            dataType: 'json',
            url: Chiko_vars.ajaxurl,
          }).done(function(data) {
              if(data.success){
                  Swal.fire({
                    icon: 'success',
                    title: data.success,
                    showConfirmButton: false,
                    timer: 1500
                  })
                  location.reload();
              }
              else{
                  Swal.fire({
                    icon: 'error',
                    text: data.error,
                  })
              }
          }).fail(function(response, textStatus, errorThrown) {
              console.log('fail', response);
          });
	})

	$(document).on("click", ".btn-edit-sub", function() {
		subId = $(this).closest("li").attr("data-id");

		$.ajax({
	        type: 'POST',
	        data: {action:"get_sub_data",ID:subId},
	        dataType: 'json',
	        url: Chiko_vars.ajaxurl,
	      }).done(function(data) {
	      	  $("#sub-title-nm").val(data.subTitle);
	          tinymce.get("sub-title-dtl").setContent(data.editorContent);
	          $("#subDetailModal").modal("toggle");
	      }).fail(function(response, textStatus, errorThrown) {
	          console.log('fail', response);
	      });

	})
	$(document).on("click", ".btn-add-sub", function() {
		subId="";
		mainId=$(this).closest("li").attr("data-id");
		$("#sub-title-nm").val("");
	    tinymce.get("sub-title-dtl").setContent("");
		$("#subDetailModal").modal("toggle");
	})
	$(document).on("click", ".save-sub", function() {
		let subTitle = $("#sub-title-nm").val();
		let editorContent = tinymce.get("sub-title-dtl").getContent();
		$.ajax({
            type: 'POST',
            data: {action:"update_sub_data",ID:subId,mainId:mainId,subTitle: subTitle,editorContent:editorContent},
            dataType: 'json',
            url: Chiko_vars.ajaxurl,
          }).done(function(data) {
              if(data.success){
                  Swal.fire({
                    icon: 'success',
                    title: data.success,
                    showConfirmButton: false,
                    timer: 1500
                  })
                  if($("li[data-id='"+subId+"']").size()>0){
                  	$("li[data-id='"+subId+"']").find(".sub-title").text(data.title);
                  }
                  else{
                  	location.reload();
                  }
              }
              else{
                  Swal.fire({
                    icon: 'error',
                    text: data.error,
                  })
              }
          }).fail(function(response, textStatus, errorThrown) {
              console.log('fail', response);
          });
	})
	$(document).on("click", ".btn-del-sub", function() {
		let delId=$(this).closest("li").attr("data-id");
		$.ajax({
            type: 'POST',
            data: {action:"del_sub_data",ID:delId},
            dataType: 'json',
            url: Chiko_vars.ajaxurl,
          }).done(function(data) {
              if(data.success){
                  Swal.fire({
                    icon: 'success',
                    title: data.success,
                    showConfirmButton: false,
                    timer: 1500
                  })
                  $("li[data-id='"+delId+"']").remove();
              }
              else{
                  Swal.fire({
                    icon: 'error',
                    text: data.error,
                  })
              }
          }).fail(function(response, textStatus, errorThrown) {
              console.log('fail', response);
          });
	})
})