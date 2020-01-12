

$(document).on('change','.employee-auto-complete',function(){
	var emp_id=$(this).val();
	var target=siteURL+'common/hrm/getEmployeeById/'+emp_id;

	$.get(target,function(data){
		console.log(data);
		$('#emp_name').val(data.emp_name);
		$('#emp_mobile').val(data.mobile);
		$('#designation').val(data.designation_name);
		$('#emp_department').val(data.department_name);
	},'json')
})


$(document).on('keyup change','.per_month_repaid_amount , .payment_month',function(){
	var per_month_repaid_amount=$('.per_month_repaid_amount').val();
	var payment_month=$('.payment_month').val();
	var advance_amount=$('.advance_amount').val();
	if(advance_amount=="" || isNaN(advance_amount)){
		$(this).val('');
		alertMessage('Input Advance Amount First');
	}

	if($(this).attr('name')=="per_month_repaid_amount"){
		$('.payment_month').val(Math.ceil(advance_amount/per_month_repaid_amount));
	}else if($(this).attr('name')=="payment_month"){
		$('.per_month_repaid_amount').val(Math.ceil(advance_amount/payment_month));
	}
})

$(document).on('keyup','.advance_amount',function(){

	var per_month_repaid_amount=$('.per_month_repaid_amount').val();
	var payment_month=$('.payment_month').val();
	var advance_amount=$('.advance_amount').val();
	
	if(per_month_repaid_amount=="" || payment_month==""){

	}else{
		if(per_month_repaid_amount==""){
			$('.per_month_repaid_amount').val(Math.ceil(advance_amount/payment_month));
		}else{
			$('.payment_month').val(Math.ceil(advance_amount/per_month_repaid_amount));
		}
	}
})