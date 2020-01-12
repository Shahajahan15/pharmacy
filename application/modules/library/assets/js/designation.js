
function designationCheck(){
	
	var designationName = $.trim($('#library_designation_name').val());	
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	
	if(designationName != ""){
		
	$("#checkName").show();
	var targetUrl = "designation/library/checkDesignationNameAjax";
	
	var sendData  = { 
						designationName:designationName,					
						ci_csrf_token:ci_csrf_token 
					};	
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#checkName");
	
	}
	else
	{
		$("#checkName").hide();
		
	}
}
// Nasir  create = 22_9_15  modify =04-10-15   end email========
