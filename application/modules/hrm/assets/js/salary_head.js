// checking return type name exists or not 
function checkDuplicateSalaryHeadName() {
	
	var salary_head_name = $.trim($('#salary_head_name').val());
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	
	if(salary_head_name != ""){		
		$("#chkSalaryHeadNameExists").show();
		var targetUrl = "salary_head/hrm/checkSalaryHeadNameAjax";
		var sendData  = { salary_head_name:salary_head_name,ci_csrf_token:ci_csrf_token };	
		setInnerHTMLAjax(siteURL+targetUrl, sendData, "#chkSalaryHeadNameExists");	
	}
	else
	{
		$("#chkSalaryHeadNameExists").hide();		
	}
	 
}


