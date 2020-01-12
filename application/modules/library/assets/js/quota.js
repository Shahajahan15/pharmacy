
function quotaNameCheck(){
	
	var quotaName = $.trim($('#library_quata_name').val());	
	
	if(quotaName != ""){		
	$("#checkName").show();
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	var targetUrl = "quata_info/library/checkQuotaNameAjax";
	
	var sendData  = { 
						quotaName:quotaName,					
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
