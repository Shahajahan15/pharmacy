
function gradeCheck(){
	
	var gradeName = $.trim($('#library_grade_name').val());	
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	
	if(gradeName != ""){
		
	$("#checkName").show();
	var targetUrl = "grade_info/library/checkGradeNameAjax";
	
	var sendData  = { 
						gradeName:gradeName,					
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
