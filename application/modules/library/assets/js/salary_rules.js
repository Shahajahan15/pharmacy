/*
* get amount type 
if type = 1  fixed field is disable and percentage field is enable 
if type = 2  fixed field is enable and percentage field is disable  
*/ 
function getAmountType(event)
{	
	var _this = event;
	var type = $(_this).val();	
	if(type==1){
		$(_this).closest('span').find('input[name="percentage_amount[]"]').prop('disabled', false);
		$(_this).closest('span').find('input[name="fixed_amount[]"]').val('');
		$(_this).closest('span').find('input[name="fixed_amount[]"]').prop('disabled', true);
	}else if(type==2){
		$(_this).closest('span').find('input[name="fixed_amount[]"]').prop('disabled', false);			
		$(_this).closest('span').find('input[name="percentage_amount[]"]').prop('disabled', true);
		$(_this).closest('span').find('input[name="percentage_amount[]"]').val('');				
	}else{			
		//$(_this).closest('span').find('input[name="percentage_value[]"]').prop('disabled', false);
		//$(_this).closest('span').find('input[name="fixed_value[]"]').prop('disabled', false);			
	}
}

// Add row how many need click on '+' button
function addRow(event)
{		
	var _clone = $(event).closest("span").clone();
	_clone.find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
	_clone.find("a").removeClass("btn-primary").addClass("btn-danger");
	_clone.find("a").removeAttr("onclick").attr("onclick", "removeRow(this);");	

	$("#detailsContainer").append("<span class='row'>"+_clone.html()+"<span>");	
}
// Remove a specific row click on '-' button  
function removeRow(event){
	$(event).closest('span').remove();	
}


//================ Show Salary Rule 
function showSalaryRule()
{				
	var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
	
	var targetUrl 		= "salary_rules/library/showRuleList";
	var sendData  		= { 
							ci_csrf_token:ci_csrf_token						
						  };
	var isJSON=0;
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#salaryRuleInnerHTML", "", isJSON, 0);
}

// get all salary rule information and send them to the controller.
function saveSalaryRule()
{
	var salaryRuleName		= $('#rule_name').val();
	var ruleDescription		= $('#rule_description').val();
	var status	 			= $('#status').val();
	var mstId 				= $('#master_id').val(); // for updating master table records 
	
	var detailsId			= []; // For updating details table records
	
	var salaryHeadId 	 	= [];
	var amountType  	 	= [];
	var percentageValue  	= [];
	var fixedValue  	 	= [];
	
	/*
	// === duplicate checking 
	var myarray = [];
	myarray.push("2");
			
	if(jQuery.inArray(1, myarray) !== -1) {
		console.log("is in array");
	} else {		
		console.log("is NOT in array");
	}
	// duplicate checking end 		
	*/
	
	$.each($('input[name="details_id[]"]'),function(){detailsId.push($(this).val());});	
	$.each($('select[name="salary_head[]"]'),function(){salaryHeadId.push($(this).val());});
	$.each($('select[name="amount_type[]"]'),function(){amountType.push($(this).val());});
	$.each($('input[name="percentage_amount[]"]'),function(){percentageValue.push($(this).val());});		
	$.each($('input[name="fixed_amount[]"]'),function(){fixedValue.push($(this).val());});
	
	var ci_csrf_token 		= $("input[name='ci_csrf_token']").val();	
	var targetUrl 			= "salary_rules/library/salaryRuleAjax";
		
	var sendData  			={ 	
								salaryRuleName:salaryRuleName,	
								ruleDescription:ruleDescription,
								status:status,
								salaryHeadId:salaryHeadId,								
								percentageValue:percentageValue,
								fixedValue:fixedValue,
								mstId:mstId,
								detailsId:detailsId,
								ci_csrf_token:ci_csrf_token						
							};

	if(salaryRuleName != '' && salaryHeadId != ''  && (percentageValue != '' || fixedValue !=''))
	{
		$.post(siteURL+targetUrl,sendData).success(function(respons){
			
			if(respons != false){				
				resetSalaryRule();
			}
			showSalaryRule();	
		});
	}else 
	{
		alert("Please fill up * mark fields before click on submit button.");					
	}
	
}

// Delete Salary Rule 
function deleteSalaryRule(id)
{	
	var targetMultipleId=[];
	
	$.each($('input:checked[name="checked[]"]'), function() {targetMultipleId.push($(this).val());});

	var salaryRuleMstId	= id;		
	var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
	var targetUrl 		= "salary_rules/library/deleteTargetSalaryRule";
	
	var sendData  		= { 						
							salaryRuleMstId:salaryRuleMstId,	
							targetMultipleId:targetMultipleId,	
							ci_csrf_token:ci_csrf_token
						  };
	var isJSON=0;
	if((targetMultipleId!=''))
	{	
		setInnerHTMLAjax(siteURL+targetUrl, sendData, "#salaryRuleInnerHTML", "", isJSON, 0);
		//resetSalaryHead()
	}
}

// Edit Salary Rule
function editSalaryRule(id)
{
	var masterID = id;		
	
	if(masterID > 0)
	{		
		var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
		var targetUrl 		= "salary_rules/library/getSalaryRuleInfo";		
		var sendData  		= {masterID:masterID,ci_csrf_token:ci_csrf_token};	
									
		$.ajax(
		{
			type: "POST",
			url: siteURL+targetUrl, //calling method in controller
			data: sendData,
			dataType:'json',
			success: function (response) 
			{				
				eval(response.evalData);
				$("#detailsContainer").html(response.detailsHtml);				
			},
			error: function(){ alert('Ooops ...something wrong.'); }
		});  		
	} 
	else 
	{
		resetSalaryRule();
	}		
}

// Reset Salary Rule 
function resetSalaryRule()
{
	$('#salary_rule_form')[0].reset();
	$('#details_table_mst_id').val('');
	$('#salary_head').val(1);
	$('#fixed_amount').val('');
	$('#percentage_amount').val('');
	$('#detailsContainer span').not(':first').remove();										
}

/*
	Check All Feature
*/
$(".check-all").click(function(){
    if(!$(this).is(':checked')){
	    $("table tbody input[type=checkbox]").removeAttr('checked');
    } else {
		$("table tbody input[type=checkbox]").prop('checked', true);	
        //$("table tbody input[type=checkbox]").attr('checked', true);
    }
});