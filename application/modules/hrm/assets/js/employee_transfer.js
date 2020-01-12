
if( $('#employeeTransferForm').length > 0 ) {
	var employee_emp_id = $('#employee_emp_id').val();
	if(employee_emp_id > 0){
		setTimeout(function(){$('#employeeSearch').trigger('click');}, 1000);
	}
}



$('#employeeSearch').click(function (e){
		$('#employeeName').val('');
		$('#EMP_ID').val('');		
		$('#PRESENT_BRANCH_NAME').val('');	
		$('#BEFORE_BRANCH_ID').val('');
		$('#TRANSFER_BRANCH_ID').val('');
		$('#PRESENT_DEPARTMENT_NAME').val('');	
		$('#BEFORE_DEPARTMENT_ID').val('');
		$('#TRANSFER_DEPARTMENT_ID').val('');
		$('#DESIGNATION_NAME').val('');
		$('#BEFORE_DESIGNATION_ID').val('');			
		$('#TRANSFER_DESIGNATION_ID').val('');			
		$('#TRANSFER_LETTER_NO').val('');
		$('#JOINNING_DATE_FROM').val('');
		$('#JOINNING_DATE_TO').val('');				
		$('#TRANSFER_REASON').val('');				
		$('#TRANSFER_REMARKS').val('');	
		
		var employeeId  =$.trim($('#employee_emp_id').val());	
		
	if(employeeId!==''){
		var employeeId   	= employeeId;		
		var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
		var targetUrl 		= "employee_transfer/hrm/getEmployeeDataAjax";
		var sendData  		= {employeeId: employeeId, ci_csrf_token:ci_csrf_token };							  
		var result = evalDataByAjax(siteURL+targetUrl, sendData);				
	}else{
		$('#employeeName').val('');
		$('#EMP_ID').val('');		
			
	}
});
	
	
$('#TRANSFER_LETTER_NO').on('blur',function (e){

	var transferLetterNo = $.trim($('#TRANSFER_LETTER_NO').val()); 	
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	
	if(transferLetterNo != ""){
		
	$("#checkLetterNo").show();
	var targetUrl = "employee_transfer/hrm/checkLetterNoAjax";
	
	var sendData  = { transferLetterNo:transferLetterNo, ci_csrf_token:ci_csrf_token };	
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#checkLetterNo");
	
	}
	else
	{
		$("#checkLetterNo").hide();
		
	}
})
 


		
// absent tr add
function addTransferRow(event)
{	
	var _clone = $(event).closest("span").clone();
	_clone.find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
	_clone.find("a").removeClass("btn-primary").addClass("btn-danger");
	_clone.find("a").removeAttr("onclick").attr("onclick", "removeTransferTr(this)");	

	$(".detailsContainer").append("<span class='row'>"+_clone.html()+"<span>");

    $(".datepickerCommon").datepicker({
        format:'dd/mm/yyyy'
    });
}

function removeTransferTr(event){
	$(event).closest('span').remove();	
}
 

