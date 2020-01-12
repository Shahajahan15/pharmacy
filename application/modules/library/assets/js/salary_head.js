// Duplicate salary Head name check 
function salaryHeadNameChk(){	
	var salary_head_name = $.trim($('#salary_head_name').val()); 
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	
	if(salary_head_name != ""){		
	$("#checkDuplicateSalaryHeadName").show();
	var targetUrl = "salary_head/library/checkSalaryHeadNameAjax";	
	var sendData  = { 
						salary_head_name:salary_head_name,					
						ci_csrf_token:ci_csrf_token 
					};	
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#checkDuplicateSalaryHeadName");	
	}
	else
	{
		$("#checkDuplicateSalaryHeadName").hide();
		
	}
}

//================ Show Salary Head 
function showSalaryHeadList()
{				
	var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
	var targetUrl 		= "salary_head/library/salaryHeadAjax";
	var sendData  		= { 
							ci_csrf_token:ci_csrf_token,									
						  };
	var isJSON=0;
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#testTypeInnerHTML", "", isJSON, 0);
}

//================ Add Salary head 
function addSalaryHead()
{		
	var salary_head_id   	= $.trim($("#salary_head_id").val());
	var salary_head_name  	= $.trim($("#salary_head_name").val());
	var description   	    = $.trim($("#description").val());
	var status  	    	=  $.trim($("#status").val());		
	var ci_csrf_token 		= $("input[name='ci_csrf_token']").val();	
	var targetUrl 			= "salary_head/library/salaryHeadAjax";
	
	var sendData  		= { 
							salary_head_id:salary_head_id,
							salary_head_name:salary_head_name,
							description:description,
							status:status,
							ci_csrf_token:ci_csrf_token							
						  };
	var isJSON=0;
	
	if((salary_head_name!='')){
		$.post(siteURL+targetUrl, sendData).success(function(response){
			showSalaryHeadList();
			if(response != false){				
				resetSalaryHead();				
			}
		});
						
	}else{		
		alert('Please fill up * mark fields properly before Click on save Button');
	}	
}

// Edit Salary head
function editSalaryHead(id)
{			
	var salary_head_id  = id;		
	if(salary_head_id > 0)
	{		
		var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
		var targetUrl 		= "salary_head/library/getSalaryHead";
		var sendData  = {salary_head_id:salary_head_id,ci_csrf_token:ci_csrf_token};  	
		var result = evalDataByAjax(siteURL+targetUrl, sendData);			
	}else{		
		$('#salary_head_name').val('');	
		$('#description').val('');	
		$('#status').val('');	
		$('#salary_head_id').val('');		
	}	
}

// Delete Salary Head
function deleteSalaryHead(id)
{	
	var targetMultipleId=[];
	
	$.each($('input:checked[name="checked[]"]'), function() {targetMultipleId.push($(this).val());});

	var salary_head_id  = id;		
	var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
	var targetUrl 		= "salary_head/library/deleteTargetSalaryHead";
	
	var sendData  		= { 						
							salary_head_id:salary_head_id,	
							targetMultipleId:targetMultipleId,	
							ci_csrf_token:ci_csrf_token,
						  };
	var isJSON=0;
	if( (targetMultipleId!='') ){	
		setInnerHTMLAjax(siteURL+targetUrl, sendData, "#testTypeInnerHTML", "", isJSON, 0);
		//resetSalaryHead()
	}

}

function resetSalaryHead(){	
	$("#salary_head_id").val('');
	$("#salary_head_name").val('');	
	$('#description').val('');
	$('#status').val(1);
	//$('input[type=checkbox]').attr('checked',false);
	//$('#showListForm')[0].reset();
	//$('input[name="checked[]"]').val("");	
}

