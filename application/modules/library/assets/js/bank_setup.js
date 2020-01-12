// Duplicate salary Head name check 
function getBankName(event){	
	
	var _this 			= event;		
	var bankName 		= $(_this).val();
	
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	
	if(bankName != ""){		
	$("#checkDuplicateBankName").show();
	var targetUrl = "bank_setup/library/checkBankNameAjax";
	var sendData  = { 
						bankName:bankName,						
						ci_csrf_token:ci_csrf_token 
					};
	
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#checkDuplicateBankName");	
	}
	else
	{
		$("#checkDuplicateBankName").hide();		
	}
}