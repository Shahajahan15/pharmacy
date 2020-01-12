
function branchCheck(){

	var branchName = $.trim($('#library_branch_branch').val()); 
	var branchCompany = $.trim($('#library_branch_company').val()); 
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	
	if(branchName != ""){
		
	$("#checkBranchName").show();
	var targetUrl = "branch_info/library/checkBranchNameAjax";
	
	var sendData  = { 
						branchName:branchName,
						branchCompany:branchCompany,
						ci_csrf_token:ci_csrf_token 
					};	
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#checkBranchName");
	
	}
	else
	{
		$("#checkBranchName").hide();
		
	}
}
// Nasir  create = 22_9_15  modify =04-10-15   end email========


$(document).on('blur','#library_branch_account_no',function(e){
	var bankName=$.trim($('#bank_id').val());
	var accountNo=$.trim($('#library_branch_account_no').val());

	var ci_csrf_token=$("input[name='ci_csrf_token']").val();

	if(accountNo!=''){
		var targetUrl=siteURL+"branch_setup/library/checkAccountNameAjax";
		var sendData={
			bankName:bankName,
			accountNo:accountNo,
			ci_csrf_token:ci_csrf_token
		};
		$.post(targetUrl,sendData,function(data){
			if(data.status==1){
				alertMessage(data.message);
				$('#').val('bank_id');
			    $('#').val('library_branch_account_no');
				
			}
		},'json')

	}

})
$(document).on('blur','#branch_id',function(e){
	var branchName=$.trim($('#branch_id').val());
	var bankId=$.trim($('#bank_id').val());
	var ci_csrf_token=$("input[name='ci_csrf_token']").val();

	if(branchName!=''){
		var targetUrl=siteURL+"branch_setup/library/checkBranchNameAjax";
		var sendData={
			branchName:branchName,
			bankId:bankId,
			ci_csrf_token:ci_csrf_token
		};
		$.post(targetUrl,sendData,function(data){
			if(data.status==1){
				alertMessage(data.message);
				$('#').val('#branch_id');
				$('#').val('#bank_id');
			}
		},'json')
	}
})

