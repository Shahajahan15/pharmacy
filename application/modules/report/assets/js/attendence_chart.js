$(document).on('click','.process-now',function(){
	var month=$('.month').val();
	if(month==""){
		return false;
	}
	var target_url=siteURL+'attendence_chart_report/report/proccess';
	var sendData={
		month:month,
		ci_csrf_token:$('input[name="ci_csrf_token"]').val()
	}
	$('body').append('<div class="loader-only"></div>');
	$.post(target_url,sendData,function(data){
		$('#attendence-chart').html(data);
	}).always(function(){
    	$('.loader-only').remove();
    })
})

$(document).ready(function(){
	$('.process-now').trigger('click').change();
})