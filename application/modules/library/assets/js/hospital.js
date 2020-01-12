// $(document).on('blur','#hospital_name',function(e){	
	
// 	var hospitalName = $.trim($('#hospital_name').val());	
// 	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	
// 	if(hospitalName != ""){
// 	var targetUrl = siteURL+"hospital_setup/library/checkHospitalNameAjax";
	
// 	var sendData  = { 
// 						hospitalName:hospitalName,					
// 						ci_csrf_token:ci_csrf_token 
// 					};	
// 	//setInnerHTMLAjax(siteURL+targetUrl, sendData, "#checkName");
// 	$.post(targetUrl,sendData,function(data){
// 		if(data.status==1){
// 			alertMessage(data.message);
// 			$('#hospital_name').val('');
// 		}
		
// 	},'json')
	
// 	}

// })
function hospitalCheck(){

	var hospitalName = $.trim($('#hospital_name').val());	
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	
	if(hospitalName != ""){
		
	$("#checkName").show();
	var targetUrl = "hospital_setup/library/checkHospitalNameAjax";
	
	var sendData  = { 
						hospitalName:hospitalName,					
						ci_csrf_token:ci_csrf_token 
					};	
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#checkName");
	
	}
	else
	{
		$("#checkName").hide();
		
	}
}