	
	/* approve check all */
	$(".approve-all").click(function(){
    if(!$(this).is(':checked')){
	    $("table tbody .pru_requi_approve").removeAttr('checked');
    } else {
		$("table tbody .pru_requi_approve").prop('checked', true);	
    }
});

   $(".app_done").click(function(e){
   		e.preventDefault();

   		//var sendData = $("form").serialize();
      var sendData = $('form').find(':input:checkbox:checked').closest('tr').find(':input').serialize();
      sendData += '&ci_csrf_token=' +$('form').find('input[name="ci_csrf_token"]').val();


   		var targetUrl = siteURL +"purchase_requisition_approve/pharmacy/purchaseRequisitionApprove";

   		$.post(targetUrl,sendData,function(response){
        if (response.success == true) {
                showMessages(response.message, 'success');
                $('form table tbody input:checked').each(function(){
                    $(this).closest('tr').remove();
                })
                $('#approve_total_price').html('');
                $('#approve_total_qnty').html('');
                $('.app_done').attr('disabled',true);

            } else {
                showMessages(response.message, 'error');
            }
      },'json').fail(function (jqXHR) {
          console.log(jqXHR);
            showMessages('Unknown Error!!!', 'error');
        })

      /*$.ajax({
        url: targetUrl,
        type: "POST",
        data: sendData,
        dataType: "json",
        success: function (response) {
          console.log(response);return false;
        	if (response.success == true) {
                showMessages(response.message, 'success');

                $('form table tbody input:checked').each(function(){
                    $(this).closest('tr').remove();
                })
                $('#approve_total_price').html('');
                $('#approve_total_qnty').html('');

            } else {
                showMessages(response.message, 'error');
            }
        }, error: function (jqXHR) {
        	console.log(jqXHR);
            showMessages('Unknown Error!!!', 'error');
        }
    });*/
   });

  $(document).on('keyup change','.approve_nty',function(){
    var qnty=parseFloat($(this).val());
    var last_price=parseFloat($(this).closest('tr').find('.last_price').text());
    $(this).closest('tr').find('.total_price').text(qnty*last_price);
   })

   $(document).on('change keyup','.pru_requi_approve,.approve-all,.approve_nty',function(){
    var approve_total_qnty=0.00;
      var approve_total_price=0.00;
      $('form table tbody input:checked').each(function(){
        approve_total_qnty+=parseFloat($(this).closest('tr').find('.approve_nty').val());
        approve_total_price+=parseFloat($(this).closest('tr').find('.total_price').text());        
        
      })

      if(approve_total_qnty>0){
        $('.app_done').attr('disabled',false);
      }else{
        $('.app_done').attr('disabled',true);
      }
      
      $('#approve_total_price').html(Math.round(approve_total_price));
      $('#approve_total_qnty').html(Math.round(approve_total_qnty));

   })

 