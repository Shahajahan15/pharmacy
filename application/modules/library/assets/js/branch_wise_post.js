
function postNumberCheck(){
	
	var sex 			= $.trim($('#library_sex').val());	
	var designatioId 	= $.trim($('#library_branch_designation').val());	
	var departmentId 	= $.trim($('#library_branch_department').val());
	var branchId 		= $.trim($('#library_branch_branch').val());
	
	if(sex != "" & designatioId != "" & departmentId != "" & branchId != ""){		
		var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();
		var targetUrl 		= "branch_wise_post/library/checkNumberOfpostAjax";
		
		var sendData  = { 						
						sex:sex,
						designatioId:designatioId,
						departmentId:departmentId,
						branchId:branchId,					
						ci_csrf_token:ci_csrf_token 
						};
					
		$.post(siteURL+targetUrl, sendData).success(function(response){
			
			if(response != false){				
				alert(' Number Of Post Already Created With Same Designation !');
				$('#library_number_of_post').val('');
				$('#library_sex').val('');
				$('#library_branch_designation').val('');
				$('#library_branch_department').val('');
				$('#library_branch_branch').val('');
				
			}
		});		
	
	}
	else
	{
		alert('Select * Marks Fields !');
	}
}
// Nasir  create = 22_9_15  modify =04-10-15   end email========
