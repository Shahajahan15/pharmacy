
$(document).on("click",".payment",function(){
	var id = $(this).attr('id');
	$('#commonModalTitle').html("<b style='text-align:center'>Payment</b>");
	$('#commonModalFooter').remove();
	$('#commonModalBody').html('<div class="loader"></div>');
	$('#commonModal').modal('show');
	
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	var sendData  = {id:id,ci_csrf_token:ci_csrf_token};
	var targetUrl = siteURL +"payment/pharmacy/payment";
   	$.post( targetUrl, sendData).done( function( data ) { 
    	$('#commonModalBody').html(data);
	});	
});



$(document).on("change","#payment_type",function(){
	var id = $(this).val();
     if (id == 1) {
	 	$(".branch, .check_date").hide();
	 	$(".branch_id, .c_date").removeAttr('required');
	 } else {
	 	$(".branch, .check_date").show();
	 	$(".branch_id, .c_date").attr('required', 'required');
	 }
	});

   	$(document).on("submit",".payment_form",function(e){
   		e.preventDefault();
   		var sendData = $(".payment_form").serialize();
   		var master_id = $('.payment').attr('id');
   		var targetUrl = siteURL +"payment/pharmacy/save";
   		var _this = $("table.store_payment [index="+master_id+"]").closest('tr');
   		$.ajax({
        url: targetUrl,
        type: "POST",
        data: sendData,
        dataType: "json",
        success: function (response) {
        	//console.log(response);
        	if (response.success == true) {
                showMessages(response.message, 'success');
               if (response.status == true) {
        			$(_this).fadeOut(1000, function () {
	    			$(_this).remove();
	    		});
	    		}
	    		$('#commonModal').modal('hide');
            } else {
                showMessages(response.message, 'error');
            }
        }, error: function (jqXHR) {
        	console.log(jqXHR);
            showMessages('Unknown Error!!!', 'error');
        }
    });
   });

   $(document).on('keyup','.store_payment',function(){
		var tot_p_paid = parseFloat($(".store_total_c_paid").val());
		var tot_payment = parseFloat($(this).val());
		if (tot_p_paid >= tot_payment){
			 $('.store_due').val(tot_p_paid-tot_payment);
		} else {
			$(this).val('');
			$('.store_due').val('0');
		}
     

   })

  