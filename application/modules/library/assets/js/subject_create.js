// Duplicate Subject check 
function getSubjectName(event){	
	
	var _this 			= event;		
	var subject 		= $(_this).val();
	
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	
	if(subject != ""){		
	$("#checkDuplicateSubjectName").show();
	var targetUrl = "subject_create/library/checkSubjectNameAjax";
	var sendData  = { 
						subject:subject,						
						ci_csrf_token:ci_csrf_token 
					};
	
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#checkDuplicateSubjectName");	
	}
	else
	{
		$("#checkDuplicateSubjectName").hide();		
	}
}