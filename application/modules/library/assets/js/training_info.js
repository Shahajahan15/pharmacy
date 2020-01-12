
function trainingTypeCheck(){	
	var trainingType = $.trim($('#library_training_type').val()); 
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	
	if(trainingType != ""){		
	$("#checkTypeName").show();
	var targetUrl = "training_type/library/checkTypeNameAjax";	
	var sendData  = { 
						trainingType:trainingType,					
						ci_csrf_token:ci_csrf_token 
					};	
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#checkTypeName");
	
	}
	else
	{
		$("#checkTypeName").hide();
		
	}
}
//================ Show Training Type
function showTrainingType()
{				
	var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
	var targetUrl 		= "training_type/library/trainingTypeAjax";
	var sendData  		= { 
							ci_csrf_token:ci_csrf_token,		
							
						  };
	var isJSON=0;
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#employeeTrainingInnerHTML", "", isJSON, 0);

}

//================ Add Training Type
function addTrainingType()
{		
	var trainingTypeId   			= $.trim($("#TRAINING_TYPE_ID").val());
	var training_type_name  		= $.trim($("#library_training_type").val());
	var training_type_name_bengali  = $.trim($("#training_type_name_bengali").val());
	var description   	    		= $.trim($("#library_training_type_description").val());
	var type_description_bengali   	= $.trim($("#type_description_bengali").val());
	var library_training_type_type  = $.trim($("#library_training_type_type").val());
	var status  	    			=  $.trim($("#library_training_status").val());		
	var ci_csrf_token 				= $("input[name='ci_csrf_token']").val();	
	var targetUrl 					= "training_type/library/trainingTypeAjax";
	
	var sendData  					= { 
										trainingTypeId:trainingTypeId,
										training_type_name:training_type_name,
										training_type_name_bengali:training_type_name_bengali,
										description:description,
										type_description_bengali:type_description_bengali,
										library_training_type_type:library_training_type_type,
										status:status,
										ci_csrf_token:ci_csrf_token								
									};
	var isJSON=0;
	if( (training_type_name!=''))
	{
		setInnerHTMLAjax(siteURL+targetUrl, sendData, "#employeeTrainingInnerHTML", "", isJSON, 0);
		resetTrainingTypeSetupForm();	
	}
}

function resetTrainingTypeSetupForm()
{	
	$('#TRAINING_TYPE_ID').val('');
	$('#training_type_setup_form')[0].reset();
}


// Edit training type
function editTrainingType(typeId)
{			
	var typeId  = typeId;						  
		if(typeId > 0){	
		
		var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
		var targetUrl 		= "training_type/library/getTrainingType";
		var sendData  = { 						
				typeId:typeId,											
				ci_csrf_token:ci_csrf_token,
			  };  	
	var result = evalDataByAjax(siteURL+targetUrl, sendData);	
		
	}
	else
	{		
		resetTrainingTypeSetupForm();			
	}	
}

// Delete Employment History
function deleteTrainingType(type_id)
{			
	var type_id  = type_id;		
	var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
	var targetUrl 		= "training_type/library/deleteTrainingType";
	
	var sendData  		= { 						
							type_id:type_id,											
							ci_csrf_token:ci_csrf_token,
						  };
	var isJSON=0;
	if( (type_id!='') ){	
		setInnerHTMLAjax(siteURL+targetUrl, sendData, "#employeeTrainingInnerHTML", "", isJSON, 0);
			
	}

}

