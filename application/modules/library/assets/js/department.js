
function departmentCheck(){
	var departmentName = $.trim($('#lib_department_department_name').val());	
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	
	if(departmentName != ""){
		
	$("#checkName").show();
	var targetUrl = "department/library/checkDepartmentNameAjax";
	
	var sendData  = { 
						departmentName:departmentName,					
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
