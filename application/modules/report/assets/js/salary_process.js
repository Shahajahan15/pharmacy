$(document).on('click','.process-now',function(){
	var month=$('.month').val();
	if(month==""){
		return false;
	}
	var target_url=siteURL+'salary_process/hrm/proccess';
	var sendData={
		month:month,
		ci_csrf_token:$('input[name="ci_csrf_token"]').val()
	}
	$('body').append('<div class="loader-only"></div>');
	$.post(target_url,sendData,function(data){
		$('#employee-salary-table').html(data);
	}).always(function(){
    	$('.loader-only').remove();
    })
})

$(document).ready(function(){
	$('.process-now').trigger('click');
})

$(document).on('click','.sheet_print',function(){
	var target_url =$(this).attr('href');
	var sendData = {
		month: $('#month').val(),
		ci_csrf_token:$('input[name="ci_csrf_token"]').val()
	}
	$.post(target_url,sendData,function(data){
		print_view(data);
	})
})

$(document).on('click','.pay-slip-print',function(data){
	var sendData={
		emp_id:$(this).attr('emp_id'),
		month:$(this).attr('month')
	}
	var target_url=siteURL+'salary_process/hrm/getPayslip';
	$.get(target_url,sendData,function(data){
		print_view(data);
	})
})