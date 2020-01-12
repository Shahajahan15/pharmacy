$(document).on('click','.submit-op-balance',function(){
	$this=$(this);
	var product_id=$(this).closest('tr').find('.product_id').val();
	var qnty=$(this).closest('tr').find('.qnty').val();
	if(qnty==''){qnty=0};
	if(qnty==0){
		$(this).closest('tr').find('.qnty-message').html('Quantity Required');
		return false;
	}else{
		$(this).closest('tr').find('.qnty-message').html('');
	}
	var ci_csrf_token=$('input[name="ci_csrf_token"]').val();

	var target_url=siteURL+'/opening_balance/pharmacy/opening_balance_add';
	var sendData={
		product_id:product_id,
		qnty:qnty,
		pharmacy_id:$('input[name="pharmacy_id"]').val(),
		ci_csrf_token:ci_csrf_token
	};

	$.post(target_url,sendData,function(data){
		if(data.status==true){
			$this.closest('tr').fadeOut(500);
			showMessages(data.message,'success');
		}else{
			showMessages(data.message,'error');
		}
	},'json')
});










$(document).on('click','.op-submit-all',function(){
	var sendData=$(this).closest('form').serialize();
	var target_url=siteURL+'/opening_balance/pharmacy/opening_balance_multi_add';

	$.post(target_url,sendData,function(data){
		//console.log(sendData);
		$('.qnty').each(function(){
			if($(this).val()>0){
				$(this).closest('tr').fadeOut(500);
		}
		$('#search').trigger('click');
		})
	},'json')
});

$(document).ready(function(){
	var pharmacy_name_id_sp=$('#pharmacy_name_id_sp').val();
	if(pharmacy_name_id_sp==''){		
		$('#pharmacy_name_id_sp').val(200);
	}

});

$(document).on('click','.submit-op-balance-update',function(){

	$this = $(this);
	var op_id = $(this).closest('tr').find('.op_id').val();
	var updated_qnty = $(this).closest('tr').find('.updated_qnty').val();
	var product_id=$(this).closest('tr').find('.product_id').val();
	var qnty=$(this).closest('tr').find('.qnty').val();
	var qnty_c=$(this).closest('tr').find('.qnty_c').val();
	var pharmacy_id=$(this).closest('tr').find('.pharmacy_id').val();

	if(qnty==''){qnty=0};
	if(qnty==0){
		$(this).closest('tr').find('.qnty-message').html('Quantity Required');
		return false;
	}else{
		$(this).closest('tr').find('.qnty-message').html('');
	}
	var ci_csrf_token=$('input[name="ci_csrf_token"]').val();

	var target_url=siteURL+'/opening_balance/pharmacy/opening_balance_update';
	var sendData={
		id:op_id,
		product_id:product_id,
		updated_qnty:updated_qnty,
		qnty:qnty,
		qnty_c:qnty_c,
		pharmacy_id:pharmacy_id,
		ci_csrf_token:ci_csrf_token
	};


	$.post(target_url,sendData,function(data){
		

		if(data.status==true){

			//$this.closest('tr').fadeOut(500);
			alert(data.message);
			location.reload();
		}else{
			alert(data.message);
			location.reload();
		}
	},'json')
});