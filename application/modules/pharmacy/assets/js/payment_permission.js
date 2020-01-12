	
	/* approve check all */
	$(".approve-all").click(function(){
    if(!$(this).is(':checked')){
	    $("table tbody .pp_approve").removeAttr('checked');
    } else {
		$("table tbody .pp_approve").prop('checked', true);	
    }
});

   $(".app_done").click(function(e){

   		e.preventDefault();

   		//var sendData = $("form").serialize();
        var sendData = $('form').find(':input:checkbox:checked').closest('tr').find(':input').serialize();

      sendData += '&ci_csrf_token=' +$('form').find('input[name="ci_csrf_token"]').val();
      //alert(sendData);return;
   		var targetUrl = siteURL +"payment_permission/pharmacy/approve";
   		$.ajax({
        url: targetUrl,
        type: "POST",
        data: sendData,
        dataType: "json",
        success: function (response) {
        	if (response.success == true) {
   				//window.location.href = siteURL +"purchase_requisition_approve/pharmacy/show_list";
                showMessages(response.message, 'success');

                $('form table tbody input:checked').each(function(){
                    $(this).closest('tr').remove();
                })
                $('#approve_total_price').html('');
                $('#approve_total_qnty').html('');

            } else {
            	//window.location.href = siteURL +"purchase_requisition_approve/pharmacy/show_list";
                showMessages(response.message, 'error');
            }
        }, error: function (jqXHR) {
        	console.log(jqXHR);
            showMessages('Unknown Error!!!', 'error');
        }
    });
   });

   $(document).on('change keyup','.pp_approve,.approve-all',function(){
   	 var tot_qnty=0.00;
      var tot_price=0.00;

      $('form table tbody input:checked').each(function(){
        tot_qnty+=parseFloat($(this).closest('tr').find('.qnty').text());
        tot_price+=parseFloat($(this).closest('tr').find('.price').text());

        
      })
      $('#tot_qnty').html(Math.round(tot_qnty));
      $('#tot_price').html(Math.round(tot_price));


   })

  