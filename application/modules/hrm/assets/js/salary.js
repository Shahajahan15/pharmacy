// enable calculate button if salary rule is select 
$('#salary_rule,#employee_name,#salary_amount').change(function(){
	
	var salaryRule 		= $("#salary_rule option:selected" ).val();	
	var employeeId 		= $("#employee_name" ).val();
	var salary_amount 	= $("#salary_amount" ).val();
		
    if (salaryRule>0 && employeeId != '' && salary_amount != '' ){
        $('#calculate').prop('disabled', false);			       
    } else {
        $('#calculate').prop('disabled', true);  		
    }
	
});



/* 
/* when calculate button is click function will be fired on. 
/* Salary break down processing depending on which salary rule is selected. 
*/
$('#calculate').on('click',function(e)
{
	var salaryRuleId 	= $("#salary_rule option:selected" ).val();
	var salary_amount 	= $("#salary_amount" ).val();
	
	if(salaryRuleId > 0){
		
		var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
		var targetUrl 		= "salary_info/hrm/processingSalaryRule";
		var sendData  		= {			
								salaryRuleId:salaryRuleId,	
								salary_amount:salary_amount,	
								ci_csrf_token:ci_csrf_token							
							};							  				
		$.ajax({
			type: "POST",
			url: siteURL+targetUrl, //calling method in controller
			data: sendData,
			dataType:'json',
			success: function (response) 
			{				 
				eval(response.evalData);
				$("#detailsContainer").html(response.detailsHtml);
				grossTotal();	
			},
			error: function(){ alert('Ooops ... Purchase Order No Did Not Match.'); }
		});  
		
	} else {
		alert('Please Select Salary Rule, Employee Name, and fill up salary amount.')
	}
		
});


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
		$(_this).closest('span').find('input[name="percentage_value[]"]').prop('disabled', true);
		$(_this).closest('span').find('input[name="percentage_value[]"]').val('');
		$(_this).closest('span').find('input[name="fixed_value[]"]').prop('disabled', false);
	}else if(type==2){			
		$(_this).closest('span').find('input[name="fixed_value[]"]').prop('disabled', true);
		$(_this).closest('span').find('input[name="fixed_value[]"]').val('');
		$(_this).closest('span').find('input[name="percentage_value[]"]').prop('disabled', false);
	}else{			
		//$(_this).closest('span').find('input[name="percentage_value[]"]').prop('disabled', false);
		//$(_this).closest('span').find('input[name="fixed_value[]"]').prop('disabled', false);			
	}
}


// if fixed has value 
function calculativeFixedValue()
{			
	$('input[name^="fixed_value[]"]').keyup(function(e) 
	{						
		var fixedValue = $(this).val();	
			
		if(fixedValue)
		{
			$(this).closest('span').find('input[name="calculative_value[]"]').val(fixedValue);
		} 
		
		grossTotal();
		
	});
}



// if percentage has value
function calculativePercentageValue()
{	
	$('input[name^="percentage_value[]"]').keyup(function(e) {
				
		var salaryAmount	 = $('#salary_amount').val();
		var percentageValue	 = $(this).val();		
		var parentageAmount  = 	(percentageValue/100)*salaryAmount;
		
		if(percentageValue)
		{
			$(this).closest('span').find('input[name="calculative_value[]"]').val(parentageAmount);						
		} 
	
		grossTotal();
		
	});			
}

function grossTotal()
{
	var total = 0;
	$( ".sum" ).each( function()
	{
	  total += parseFloat( $( this ).val() ) || 0;
	});
	
	if(total)
	{
		$('#totalSalry').html('Gross Salary : ' + 'tk' + total);
	}
} 

function saveSalaryInfoData(){
	
	var employeeId		= $('#employee_name').val();
	var salaryAmount	= $('#salary_amount').val();
	var salaryRule	 	= $('#salary_rule').val();
	
	var mstId 			= $('#master_id').val(); // for updating record 
	var detailsId 		= $('#details_id').val(); // for updating record 

	var total = 0;
	$( ".sum" ).each( function()
	{
	  total += parseFloat( $( this ).val() ) || 0;
	});
	
	
	if(total==salaryAmount)
	{
		var salaryHhead 	 = [];
		var amountType  	 = [];
		var fixedValue  	 = [];
		var percentageValue  = [];	
		var calCulativeValue = [];
		
		alert(calCulativeValue);
			
		$.each($('select[name="manual_salary_head[]"]'),function(){salaryHhead.push($(this).val());});
		$.each($('select[name="amount_type[]"]'),function(){amountType.push($(this).val());});	
		$.each($('input[name="fixed_value[]"]'),function(){fixedValue.push($(this).val());});	
		$.each($('input[name="percentage_value[]"]'),function(){percentageValue.push($(this).val());});	
		$.each($('input[name="calculative_value[]"]'),function(){calCulativeValue.push($(this).val());});
		

		var ci_csrf_token 		= $("input[name='ci_csrf_token']").val();	
		var targetUrl 			= "salary_info/hrm/saveSalaryDataAjax";

		var sendData  		= { 
							employeeId:employeeId,
							salaryAmount:salaryAmount,	
							salaryRule:salaryRule,	
							salaryHhead:salaryHhead,
							amountType:amountType,
							fixedValue:fixedValue,
							percentageValue:percentageValue,
							calCulativeValue:calCulativeValue,
							mstId:mstId,
							detailsId:detailsId,
							ci_csrf_token:ci_csrf_token						
						  };
		var isJSON=0;
		
		if(employeeId != '' && salaryHhead != ''  && calCulativeValue != '')
		{
			$.post(siteURL+targetUrl,sendData).success(function(respons)
			{
				if(respons=true)
				{
					resetSalaryInfoData();
				}
			});
		}			
		
		
	}
	else 
	{
		alert("Salary amount and break down salary amount should be equal");					
	}
	
} // end function


function resetSalaryInfoData()
{	
	$('#salary_details')[0].reset();
	$('#manual_salary_head').val('');
	$('#fixed_value').val('');
	$('#percentage_value').val('');
	$('#calculative_value').val('');
	$('#detailsContainer span').not(':first').remove();		
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

function addAbsentRow(event)
{		
	var _clone = $(event).closest("span").clone();
	_clone.find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
	_clone.find("a").removeClass("btn-primary").addClass("btn-danger");
	_clone.find("a").removeAttr("onclick").attr("onclick", "removeAbsentTr(this); grossTotal();");	

	$("#detailsContainer").append("<span class='row'>"+_clone.html()+"<span>");	
}

function removeAbsentTr(event){
	$(event).closest('span').remove();	
}



