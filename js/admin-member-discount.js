jQuery(function($) {
	$(document).on("click", ".save-sub", function() {
		let dataId=$(this).closest("tr").attr("data-id");
		let disCount=$(this).closest("td").find("input").val();
		$.ajax({
            type: 'POST',
            data: {action:"update_member_discount",dataId: dataId,disCount:disCount},
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