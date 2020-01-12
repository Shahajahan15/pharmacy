
// JavaScript Document

$(".phonenumber").inputmask({ "mask": "9", "repeat": '11' });

$('.policyCreateForm a').click(function (e) {
    e.preventDefault();

    var url = $(this).attr("data-url");
    var href = this.hash; 
    var pane = $(this);

    // ajax load from data-url
    $(href).load(url,function(result){
        pane.tab('show');

        $( ".datepickerCommon" ).datepicker({
            format:'dd/mm/yyyy'
        });

        $('.timepickerCommon').timepicker({
            defaultTime: false
        });

        $().ready(function(){
            $(".form-horizontal").validator();
        })
    });
});


$( document ).ready(function() 
{
    var href = $('#tab_active').val(); 
	var url = $('#tab_url').val(); 
	var id = $('#id').val(); 
	var url = siteURL+url+id;
    //var pane = $(this);

    // ajax load from data-url
    $(href).load(url,function(result)
	{
        $( ".datepickerCommon" ).datepicker({
			format:'dd/mm/yyyy'
		});
		
		$('.timepickerCommon').timepicker({
            defaultTime: false
        });
		
        $('.policyCreateForm a[href="'+href+'"]').tab('show');		
		 
		if(id){
			$( "li" ).removeClass( "disabled" );
			$( "a" ).removeClass( "tab-disabled" );
		}
		 
		$( "div" ).removeClass( "tab-pane active" ).addClass( "tab-pane" );
		$( href ).addClass( "tab-pane active" );
        $().ready(function(){
            $(".form-horizontal").validator();
        })
    });
});


// Leave Start

//  limit Type show and hide
function checkType(){
	
		var leave_type = $.trim($('#leave_type').val()); 	
	
		if(leave_type==6)
		{ 				
			$("#leave_limit_type").val(2);
			$("#leave_max_limit").attr('disabled',true);
		}
		else
		{
			$("#leave_limit_type").val('');
			$("#leave_max_limit").removeAttr('disabled');
			
		}
	}
	
	

// max limit show and hide
function checkLimit(){
	
		var limit_type = $.trim($('#leave_limit_type').val()); 	
	
		if(limit_type==2)
		{ 				
			$("#leave_max_limit").attr('disabled',true);
		}
		else
		{
			$("#leave_max_limit").removeAttr('disabled');
			
		}
	}
	
//Check leave plocy Name
function leavePolicyCheck(){	
	var leave_policy_name  = $.trim($('#leave_policy_name').val()); 	
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	
	if(leave_policy_name != ""){		
		$("#checkLeavePolicyName").show();
		var targetUrl = "policy/hrm/leavePolicyCheckAjax";
		
		var sendData  = { 
							leave_policy_name:leave_policy_name, 
							ci_csrf_token:ci_csrf_token 
						};	
		setInnerHTMLAjax(siteURL+targetUrl, sendData, "#checkLeavePolicyName");
	
	}
	else
	{
		$("#checkLeavePolicyName").hide();
		
	}
}

//================ Add Leave Policy History
function addmLeaveInfo()
{	
	
	var LEAVE_POLICY_MST_ID   			= $("#LEAVE_POLICY_MST_ID").val();
	var LEAVE_POLICY_DTLS_ID   			= $("#LEAVE_POLICY_DTLS_ID").val();	
	var leave_policy_name   			= $("#leave_policy_name").val();
	var leave_policy_status   			= $("#leave_policy_status").val();	
	

	var leaveType=[];
	var limitType=[];
	var maxLimit=[];
	var leaveFormula=[];
	//var calculationStart=[];
	var consecutivDay=[];
	var availableCriteria=[];
	var availableAfter=[];
	var fractionLeave=[];
	var ofDayCount=[];
	var accumulationLimit=[];	
	var fordwardAllow=[];	
	
	
	$.each($('select[name^="leave_type[]"]'), function() {leaveType.push($(this).val());});	
	$.each($('select[name^="leave_limit_type[]"]'), function() {limitType.push($(this).val());});	
	$.each($('input[name^="leave_max_limit[]"]'), function() {maxLimit.push($(this).val());});
	$.each($('select[name^="leave_formula[]"]'), function() {leaveFormula.push($(this).val());});	
	//$.each($('select[name^="leave_calculation_start[]"]'), function() {calculationStart.push($(this).val());});
	$.each($('input[name^="leave_consecutiv_day[]"]'), function() {consecutivDay.push($(this).val());});		
	$.each($('select[name^="leave_available_criteria[]"]'), function() {availableCriteria.push($(this).val());});		
	$.each($('select[name^="leave_available_after[]"]'), function() {availableAfter.push($(this).val());});			
	$.each($('select[name^="leave_fraction_leave[]"]'), function() {fractionLeave.push($(this).val());});
	$.each($('select[name^="leave_of_day_count[]"]'), function() {ofDayCount.push($(this).val());});
	$.each($('input[name^="leave_max_accumulation_limit[]"]'), function() {accumulationLimit.push($(this).val());});	
	$.each($('input[name^="leave_carring_ford_allow[]"]'), function() {fordwardAllow.push($(this).val());});		
	

	//console.log(absentParameterType);return;
	
	 
		 
	var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
	var targetUrl 		= "policy/hrm/addLeavePolicyAjax";
	
	var sendData  		= { 
							ci_csrf_token:ci_csrf_token,
							leave_policy_name:leave_policy_name,
							leave_policy_status:leave_policy_status,						
							leaveType:leaveType,
							limitType:limitType,
							maxLimit:maxLimit, 
							leaveFormula:leaveFormula,		
							consecutivDay:consecutivDay,
							availableCriteria:availableCriteria,
							availableAfter:availableAfter,
							fractionLeave:fractionLeave,
							ofDayCount:ofDayCount,
							accumulationLimit:accumulationLimit,
							fordwardAllow:fordwardAllow,
							LEAVE_POLICY_MST_ID:LEAVE_POLICY_MST_ID,
							LEAVE_POLICY_DTLS_ID:LEAVE_POLICY_DTLS_ID
											
								
						  };
	var isJSON=0;
	if((leave_policy_name!='' & leave_policy_status!='' & leaveType!='')){
		$.post(siteURL+targetUrl, sendData).success(function(response){
			console.log("response= "+response);
			viewLeaveDetail();
			if(response != false){				
				resetLeave();
				
			}
		});
		
				
	}else{
		
		alert('Please Check Star Marks');
	}	

}




// edit Leave Policy  History

function editLeaveInfo(LEAVE_POLICY_MST_ID)
{		
	if(LEAVE_POLICY_MST_ID > 0){
		
		var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
		var targetUrl 		= "policy/hrm/leavePolicyShowtAjax";
		var sendData  		= {			
							LEAVE_POLICY_MST_ID:LEAVE_POLICY_MST_ID,													
							ci_csrf_token:ci_csrf_token							
							};							  
		
		//evalDataByAjax(siteURL+targetUrl, sendData);
		$.ajax({
			type: "POST",
			url: siteURL+targetUrl, //calling method in controller
			data: sendData,
			dataType:'json',
			success: function (response) {				 
				eval(response.evalData);
				$("#detailsContainer").html(response.detailsHtml);
			},
			error: function(){ alert('Ooops ... server problem'); }
		});  
		
	} else {
		resetLeave();
	}

}

// Delete Leave Policy History
function deleteLeaveInfo(LEAVE_POLICY_DTLS_ID)
{	
	var LEAVE_POLICY_DTLS_ID  = LEAVE_POLICY_DTLS_ID;	
	var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
	var targetUrl 		= "policy/hrm/leaveDeleteAjax";	
	var sendData  		= { 
							ci_csrf_token:ci_csrf_token,							
							LEAVE_POLICY_DTLS_ID:LEAVE_POLICY_DTLS_ID					
							
						  };
	var isJSON=0;
	if( (LEAVE_POLICY_DTLS_ID!='') ){	
		setInnerHTMLAjax(siteURL+targetUrl, sendData, "#employeeLeavePolicyInnerHTML", "", isJSON, 0);
			
	}

}


function resetLeave(){
	$('#leave_policy_name').val('');		
	$('#leave_policy_status').val(1);		
	$('#LEAVE_POLICY_MST_ID').val('');
	$('#detailsContainer span').not(':first').remove();	
	$('select[name^="leave_type[]"]').val('');  //dropdown	
	$('select[name^="leave_limit_type[]"]').val('');  //dropdown		
	$('select[name^="leave_formula[]"]').val('');  //dropdown		
	$('select[name^="leave_calculation_start[]"]').val('');  //dropdown		
	$('select[name^="leave_available_criteria[]"]').val('');  //dropdown		
	$('select[name^="leave_available_after[]"]').val('');  //dropdown		
	$('select[name^="leave_fraction_leave[]"]').val('');  //dropdown	
	$('select[name^="leave_of_day_count[]"]').val('');  //dropdown	
	$('input[name^="leave_max_limit[]"').val('');
	$('input[name^="absent_amount[]"').val(''); 
	$('input[name^="leave_consecutiv_day[]"').val('');
	$('input[name^="leave_max_accumulation_limit[]"').val('');
	$('input[name^="leave_carring_ford_allow[]"').prop('checked', false);
	
}

function viewLeaveDetail()
{					
	var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
	var targetUrl 		= "policy/hrm/showLeaveInfo";		
	var sendData  		= { ci_csrf_token:ci_csrf_token	};
	var isJSON=0;
	
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#employeeLeavePolicyInnerHTML", "", isJSON, 0);
}

// Leave End


// absent Start		
		
//Check Absent plocy Name
function absentPolicyCheck(){	
	var absent_policy_name  = $.trim($('#absent_policy_name').val()); 	
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	
	if(absent_policy_name != ""){		
		$("#checkAbsentPolicyName").show();
		var targetUrl = "policy/hrm/absentPolicyCheckAjax";
		
		var sendData  = { 
							absent_policy_name:absent_policy_name, 
							ci_csrf_token:ci_csrf_token 
						};	
		setInnerHTMLAjax(siteURL+targetUrl, sendData, "#checkAbsentPolicyName");
	
	}
	else
	{
		$("#checkAbsentPolicyName").hide();
		
	}
}

//Disable and Enable  Parameter Type
function paraMeterCheck($this){	
		$this = $($this);
		var type = $this.closest('span').find('.absentMeter').val();
		//console.log("type"+type);		
		
		if(type==1)
		{ 	
			$this.closest('span').find('.absentAmountDis').prop('disabled',true);
			$this.closest('span').find('.absentPersent').prop('disabled',false);
		}
		else if(type==2)
		{	$this.closest('span').find('.absentPersent').prop('disabled',true);
			$this.closest('span').find('.absentAmountDis').prop('disabled',false);	
			
		}else{
			
			$this.closest('span').find('.absentPersent').prop('disabled',false);
			$this.closest('span').find('.absentAmountDis').prop('disabled',true);	
		}
	}
	
// absent amount check
function checkNumericAbsentAmount(){
	
	var absentAmountDis   		  = $(".absentAmountDis").val();

	if(isNaN(absentAmountDis)){
		alert('Input only numaric');
		$('.absentAmountDis').val('')		
	}

}

		
// absent tr add
function addAbsentRow(event)
{	
	
	var _clone = $(event).closest("span").clone();
	_clone.find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
	_clone.find("a").removeClass("btn-primary").addClass("btn-danger");
	_clone.find("a").removeAttr("onclick").attr("onclick", "removeAbsentTr(this)");	

	$(".detailsContainer").append("<span>"+_clone.html()+"<span>");
}

function removeAbsentTr(event){
	$(event).closest('span').remove();	
}



// add absent
function addmAbsentInfo()
{
	var absent_policy_name   	= $("#absent_policy_name").val(); 
	//var absent_deduction_head   = $("#absent_deduction_head").val(); 
	var absent_policy_status   	= $("#absent_policy_status").val(); 
	var ABSENT_POLICY_DTLS_ID   = $("#ABSENT_POLICY_DTLS_ID").val();
	var ABSENT_POLICY_MST_ID   	= $("#ABSENT_POLICY_MST_ID").val();

	var absentParameterType=[];
	var absentParameterType1=[];
	var absentPercentage=[];
	var absentBaseHead=[];
	var absentAmount=[];
	
	$.each($('select[name^="absent_parameter_type[]"]'), function() {absentParameterType.push($(this).val());});	
	$.each($('input[name^="absent_persentage[]"]'), function() {absentPercentage.push($(this).val());});	
	$.each($('select[name^="absent_base_head[]"]'), function() {absentBaseHead.push($(this).val());});	
	$.each($('input[name^="absent_amount[]"]'), function() {absentAmount.push($(this).val());});	
	
	 
	var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
	var targetUrl 		= "policy/hrm/addAbsentPolicyAjax";
	//var sendData = $("#absentInFoFrm").serialize();
	
	var sendData  		= { 
							ci_csrf_token:ci_csrf_token,
							absent_policy_name:absent_policy_name,						
							absent_policy_status:absent_policy_status,
							absentParameterType:absentParameterType,
							absentPercentage:absentPercentage, 
							absentBaseHead:absentBaseHead, 
							absentAmount:absentAmount,
							ABSENT_POLICY_DTLS_ID:ABSENT_POLICY_DTLS_ID,
							ABSENT_POLICY_MST_ID:ABSENT_POLICY_MST_ID
											
								
						  };
	var isJSON=0;
	if((absent_policy_name!='' & absentBaseHead != '' & absentParameterType!='')){
		$.post(siteURL+targetUrl, sendData).success(function(response){
			console.log(response);
			viewAbsentDetail();
			if(response != false){				
				resetAbsent();
				
			}
		});
		
				
	}else{
		
		alert('Please Check star Marks');
	}	

}

 

// Delete Absent History
function deleteAbsentInfo(ABSENT_POLICY_DTLS_ID)
{	
	if(ABSENT_POLICY_DTLS_ID > 0){	
		var ABSENT_POLICY_DTLS_ID  = ABSENT_POLICY_DTLS_ID;	
		
				
		var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
		var targetUrl 		= "policy/hrm/deleteAbsentPolicyAjax";		
		var sendData  		= { 
								ci_csrf_token:ci_csrf_token,								
								ABSENT_POLICY_DTLS_ID:ABSENT_POLICY_DTLS_ID												
							  };
		var isJSON=0;
	}
	if( (ABSENT_POLICY_DTLS_ID!='' ) ){	
		setInnerHTMLAjax(siteURL+targetUrl, sendData, "#absentPolicyInfoInnerHTML", "", isJSON, 0);
			
	}

}



// Edit Absent History
function editAbsentInfo(ABSENT_POLICY_MST_ID)
{		
	if(ABSENT_POLICY_MST_ID > 0){
		
		var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
		var targetUrl 		= "policy/hrm/editAbsentPolicyAjax";
		var sendData  		= {			
							ABSENT_POLICY_MST_ID:ABSENT_POLICY_MST_ID,													
							ci_csrf_token:ci_csrf_token							
							};							  
		
		//evalDataByAjax(siteURL+targetUrl, sendData);
		$.ajax({
			type: "POST",
			url: siteURL+targetUrl, //calling method in controller
			data: sendData,
			dataType:'json',
			success: function (response) {				 
				eval(response.evalData);
				$("#detailsContainer").html(response.detailsHtml);
			},
			error: function(){ alert('Ooops ... server problem'); }
		});  
		
	} else {
		resetAbsent();
	}

}

function resetAbsent(){
	$('#absent_policy_name').val('');	
	$('#absent_deduction_head').val('');	
	$('#absent_policy_status').val(1);		
	$('#ABSENT_POLICY_MST_ID').val('');
	$('#detailsContainer span').not(':first').remove();	
	$('select[name^="absent_parameter_type[]"').val('');  //dropdown		
	$('input[name^="absent_persentage[]"').val('');		
	$('select[name^="absent_base_head[]"').val('');  //dropdown	
	$('input[name^="absent_amount[]"').val(''); 
}


function viewAbsentDetail()
{					
	var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
	var targetUrl 		= "policy/hrm/showAbsentInfo";		
	var sendData  		= { ci_csrf_token:ci_csrf_token	};
	var isJSON=0;
	
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#absentPolicyInfoInnerHTML", "", isJSON, 0);
}

// absent End


//================ Add Maternity info Start ========================

//Add Maternity History
function addmMaternityInfo()
{	
	var MATERNITY_LEAVE_ID   				= $("#MATERNITY_LEAVE_ID").val();
	var maternity_policy_name   			= $("#maternity_policy_name").val();
	var maternity_max_day_limit   	    	= $.trim($("#maternity_max_day_limit").val());
	var maternity_mini_service   			= $("#maternity_mini_service").val();
	var maternity_leave_before   	       	= $("#maternity_leave_before").val(); 
	var maternity_leave_after   	   	 	= $("#maternity_leave_after").val();	
	var maternity_payment_calculation   	= $("#maternity_payment_calculation").val(); 	
	var maternity_payment_disburse  		= $("#maternity_payment_disburse").val();
	var maternity_policy_status  			= $("#maternity_policy_status").val();
	
		
	var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
	var targetUrl 		= "policy/hrm/addMaternityPolicyAjax";
	
	var sendData  		= { 
							ci_csrf_token:ci_csrf_token,
							MATERNITY_LEAVE_ID:MATERNITY_LEAVE_ID,
							maternity_policy_name:maternity_policy_name,						
							maternity_max_day_limit:maternity_max_day_limit,
							maternity_mini_service:maternity_mini_service,
							maternity_leave_before:maternity_leave_before, 
							maternity_leave_after:maternity_leave_after, 
							maternity_payment_calculation:maternity_payment_calculation, 
							maternity_payment_disburse:maternity_payment_disburse,
							maternity_policy_status:maternity_policy_status
								
						  };
	var isJSON=0;
	if((maternity_policy_name!='' & maternity_max_day_limit!='' & maternity_policy_status!='')){
		setInnerHTMLAjax(siteURL+targetUrl, sendData, "#maternityPolicyInfoInnerHTML", "", isJSON, 0);			
		$('#maternityInFoFrm').get(0).reset();
		resetMaternity();
	}	else {
		alert('Please Check star Marks');
	}

}

// Edit Maternity History
function editMaternityInfo(MATERNITY_LEAVE_ID)
{		
	if(MATERNITY_LEAVE_ID > 0){		
		var MATERNITY_LEAVE_ID  = MATERNITY_LEAVE_ID;		
		
		var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
		var targetUrl 		= "policy/hrm/editMaternityPolicyAjax";
		var sendData  		= {
			
							MATERNITY_LEAVE_ID:MATERNITY_LEAVE_ID,		
							ci_csrf_token:ci_csrf_token
							
							};							  
		var result 			= evalDataByAjax(siteURL+targetUrl, sendData);				
	}else{
		resetMaternity();
		/*$('#maternity_policy_name').val('');	
		$('#maternity_max_day_limit').val('');	
		$('#maternity_mini_service').val('');	
		$('#maternity_leave_before').val('');	
		$('#maternity_leave_after').val('');		
		$('#maternity_payment_calculation').val('');
		$('#maternity_payment_disburse').val('');
		$('#maternity_policy_status').val('');
		$('#MATERNITY_LEAVE_ID').val('');*/
			
	}

}


// Delete Maternity History
function deleteMaternityInfo(MATERNITY_LEAVE_ID)
{	
	if(MATERNITY_LEAVE_ID > 0){	
		var MATERNITY_LEAVE_ID  = MATERNITY_LEAVE_ID;		
				
		var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
		var targetUrl 		= "policy/hrm/deleteMaternityPolicyAjax";		
		var sendData  		= { 
								ci_csrf_token:ci_csrf_token,								
								MATERNITY_LEAVE_ID:MATERNITY_LEAVE_ID												
							  };
		var isJSON=0;
	}
	if( (MATERNITY_LEAVE_ID!='' ) ){	
		setInnerHTMLAjax(siteURL+targetUrl, sendData, "#maternityPolicyInfoInnerHTML", "", isJSON, 0);
			
	}

}

function resetMaternity(){
	$('#maternityInFoFrm').get(0).reset();
	$('#maternity_policy_name').val('');
	$('#maternity_mini_service').val('');
	$('#maternity_leave_after').val('');
	$('#maternity_payment_disburse').val('');
	$('#maternity_max_day_limit').val('');
	$('#maternity_leave_before').val('');	
	$('#maternity_payment_calculation').val('');
	$('#leave_policy_status').val(1);		
	$('#MATERNITY_LEAVE_ID').val('');
	/*$('#detailsContainer span').not(':first').remove();	
	$('select[name^="leave_type[]"]').val('');  //dropdown	
	$('select[name^="leave_limit_type[]"]').val('');  //dropdown		
	$('select[name^="leave_formula[]"]').val('');  //dropdown		
	$('select[name^="leave_calculation_start[]"]').val('');  //dropdown		
	$('select[name^="leave_available_criteria[]"]').val('');  //dropdown		
	$('select[name^="leave_available_after[]"]').val('');  //dropdown		
	$('select[name^="leave_fraction_leave[]"]').val('');  //dropdown	
	$('select[name^="leave_of_day_count[]"]').val('');  //dropdown	
	$('input[name^="leave_max_limit[]"').val('');
	$('input[name^="absent_amount[]"').val(''); 
	$('input[name^="leave_consecutiv_day[]"').val('');
	$('input[name^="leave_max_accumulation_limit[]"').val('');
	$('input[name^="leave_carring_ford_allow[]"').prop('checked', false); */
	
}

//=====End Maternity






//========Start Policy Medical  ========//	
    // ========== Start medical policy =================
function addMedicalDetailsRow(event)
{
	var _clone = $(event).closest("span").clone();
	_clone.find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
	_clone.find("a").removeClass("btn-primary").addClass("btn-danger");
	_clone.find("a").removeAttr("onclick").attr("onclick", "removeMedicalDetailsRow(this)");

	$(".detailsContainer").append("<span class='row'>"+_clone.html()+"<span>");
}

function removeMedicalDetailsRow(event){
	$(event).closest('span').remove();
}


function addMedicalInfo()
{		
	// Master table part
	var NAME   						= $("#NAME").val();
	var MPDESCRIPTION   			= $("#MPDESCRIPTION").val();
	var MPSTATUS   					= $("#MPSTATUS").val();
	var MEDICAL_POLICY_MASTER_ID    = $("#MEDICAL_POLICY_MASTER_ID").val();
	
	
	// Details part 	
	var BASE_HEAD=[];	
	var AMOUNT_TYPE=[];
	var AMOUNT=[];
	
	$.each($('select[name^="BASE_HEAD[]"]'), function() {BASE_HEAD.push($(this).val());});	
	$.each($('select[name^="AMOUNT_TYPE[]"]'), function() {AMOUNT_TYPE.push($(this).val());});	
	$.each($('input[name^="AMOUNT[]"]'), function() {AMOUNT.push($(this).val());});
	
	
	var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
	var targetUrl 		= "policy/hrm/medicalPolicyInfoAjax";
	
	var sendData  		= { 
							ci_csrf_token:ci_csrf_token,
							NAME:NAME,
							MPDESCRIPTION:MPDESCRIPTION,
							MPSTATUS:MPSTATUS,
							BASE_HEAD:BASE_HEAD,
							AMOUNT_TYPE:AMOUNT_TYPE,
							AMOUNT:AMOUNT,
							MEDICAL_POLICY_MASTER_ID:MEDICAL_POLICY_MASTER_ID,						
						  };
	var isJSON=0;
	if((NAME!='' & MPSTATUS!='' & BASE_HEAD!='' & AMOUNT_TYPE!='' & AMOUNT!='')){
		$.post(siteURL+targetUrl, sendData).success(function(response){
			viewMedicalDetail();
			if(response != false){				
				resetMedical();				
			}
		});
		
				
	}else{		
		alert('Please Select * mark fields properly before Click on Plus Button');
	}	
	
	
}


// Edit Medical  History
function editMedicalInfo(MEDICAL_POLICY_MASTER_ID)
{
	var MEDICAL_POLICY_MASTER_ID = MEDICAL_POLICY_MASTER_ID;
		
	if(MEDICAL_POLICY_MASTER_ID > 0){
		
		var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
		var targetUrl 		= "policy/hrm/editMedicalPolicyAjax";
		var sendData  		= {			
							MEDICAL_POLICY_MASTER_ID:MEDICAL_POLICY_MASTER_ID,													
							ci_csrf_token:ci_csrf_token							
							};							  		
		//evalDataByAjax(siteURL+targetUrl, sendData);
		$.ajax({
			type: "POST",
			url: siteURL+targetUrl, //calling method in controller
			data: sendData,
			dataType:'json',
			success: function (response) {				 
				eval(response.evalData);
				$("#detailsContainer").html(response.detailsHtml);
			},
			error: function(){ alert('Ooops ... server problem'); }
		});  
		
	} else {
		resetMedical();
	}		
}
// reset medical info
function resetMedical(){
	$('#NAME').val('');	
	$('#MPDESCRIPTION').val('');	
	$('#MPSTATUS').val(1);	
	$('select[name^="BASE_HEAD[]"').val('');  //dropdown
	$('select[name^="AMOUNT_TYPE[]"').val('');  //dropdown	
	$('input[name^="AMOUNT[]"').val(''); 	
	$('#BONUS_POLICY_MST_ID').val('');
	$('#detailsContainer span').not(':first').remove();			
}

// view medical info
function viewMedicalDetail()
{					
	var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
	var targetUrl 		= "policy/hrm/showMedicalInfo";		
	var sendData  		= { ci_csrf_token:ci_csrf_token	};
	var isJSON=0;
	
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#medicalPolicyInfoInnerHTML", "", isJSON, 0);
}



// Delete medical History
function deleteMedicalInfo(MEDICAL_POLICY_DETAILS_ID)
{		
	if(MEDICAL_POLICY_DETAILS_ID > 0){	
		var MEDICAL_POLICY_DETAILS_ID  = MEDICAL_POLICY_DETAILS_ID;	
		
		var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
		var targetUrl 		= "policy/hrm/deleteMedicalPolicyAjax";		
		var sendData  		= { 
								ci_csrf_token:ci_csrf_token,								
								MEDICAL_POLICY_DETAILS_ID:MEDICAL_POLICY_DETAILS_ID												
							  };
		var isJSON=0;
	}
	if( (MEDICAL_POLICY_DETAILS_ID!='' ) ){	
		setInnerHTMLAjax(siteURL+targetUrl, sendData, "#medicalPolicyInfoInnerHTML", "", isJSON, 0);			
	}

}

// check medical policy name
function Check_policyName(){
	
	var NAME   		  = $("#NAME").val();	
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	
	if(NAME != ""){
		
	$("#chkMpolicyName").show();
	var targetUrl = "policy/hrm/checkMedicalPolicyNameAjax";
	var sendData  = { NAME:NAME, ci_csrf_token:ci_csrf_token };	
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#chkMpolicyName");	
	}
	else
	{
		$("#chkMpolicyName").hide();		
	}
}
	
	
//========End Policy Medical  ========//	
	
	
	

//================ Add Shift Policy Start ========================

//Add Shift policy 
function addShiftPolicy()
{						
	var SHIFT_NAME   				= $("#SHIFT_NAME").val();
	var DESCRIPTION   	       		= $("#DESCRIPTION").val(); 
	var SHIFT_TYPE   	   	 		= $("#SHIFT_TYPE").val();
	var SHIFT_STARTS   	    		= $("#SHIFT_STARTS").val();
	var SHIFT_ENDS   	   	 		= $("#SHIFT_ENDS").val();
	var LATE_MARKING_STARTS   		= $("#LATE_MARKING_STARTS").val();
	var EXIT_BUFFER_TIME   		   	= $("#EXIT_BUFFER_TIME").val(); 	
	var LUNCH_BREAK_STARTS   	    = $("#LUNCH_BREAK_STARTS").val(); 
	var LUNCH_BREAK_ENDS  			= $("#LUNCH_BREAK_ENDS").val();
	var EXTRA_BREAK_STARTS   	   	= $("#EXTRA_BREAK_STARTS").val();
	var EXTRA_BREAK_ENDS  			= $("#EXTRA_BREAK_ENDS").val();
	var EARLY_OUT_STARTS   	   	 	= $("#EARLY_OUT_STARTS").val();	
	var ENTRY_RESTRICTION_STARTS   	= $("#ENTRY_RESTRICTION_STARTS").val();
	var STATUS   					= $("#STATUS").val();
	var SHIFT_POLICY_ID_TARGET 		= $("#SHIFT_POLICY_ID_TARGET").val();
	
	var ci_csrf_token 				= $("input[name='ci_csrf_token']").val();	
	
	var targetUrl 					= "policy/hrm/shiftPolicyInfoAjax";
	
	var sendData  					= { 
										ci_csrf_token:ci_csrf_token,							
										SHIFT_NAME:SHIFT_NAME,
										DESCRIPTION:DESCRIPTION,
										SHIFT_TYPE:SHIFT_TYPE,
										SHIFT_STARTS:SHIFT_STARTS,
										SHIFT_ENDS:SHIFT_ENDS,
										LATE_MARKING_STARTS:LATE_MARKING_STARTS,
										EXIT_BUFFER_TIME:EXIT_BUFFER_TIME,
										LUNCH_BREAK_STARTS:LUNCH_BREAK_STARTS,
										LUNCH_BREAK_ENDS:LUNCH_BREAK_ENDS,
										EXTRA_BREAK_STARTS:EXTRA_BREAK_STARTS,
										EXTRA_BREAK_ENDS:EXTRA_BREAK_ENDS,
										EARLY_OUT_STARTS:EARLY_OUT_STARTS,
										ENTRY_RESTRICTION_STARTS:ENTRY_RESTRICTION_STARTS,
										SHIFT_POLICY_ID_TARGET:SHIFT_POLICY_ID_TARGET,
										STATUS:STATUS
									  };
	
	var isJSON=0;
	if(SHIFT_NAME!='' & SHIFT_TYPE != '' & SHIFT_STARTS != '' & SHIFT_ENDS != '' & LATE_MARKING_STARTS != '' &  ENTRY_RESTRICTION_STARTS != '' & EXIT_BUFFER_TIME != '' & EARLY_OUT_STARTS != ''){
		
		setInnerHTMLAjax(siteURL+targetUrl, sendData, "#shiftPolicyInfoInnerHTML", "", isJSON, 0);	
		$('#shiftPolicyFrm').get(0).reset();
		resetShift();
	} else {
		alert('Please Check star Marks');
	}
	

}



// Shift Policy data's showing for Editing  
function editShiftPolicyInfo(shift_policy_id)
{		
	if(shift_policy_id > 0){		
		var shift_policy_id_edit = shift_policy_id;		
		var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
		var targetUrl 		= "policy/hrm/getShiftpolicyInfoAjax";
		var sendData  		= {	shift_policy_id_edit: shift_policy_id_edit, ci_csrf_token:ci_csrf_token };							  
		var result 			= evalDataByAjax(siteURL+targetUrl, sendData);	
	}else{
		resetShift();

		/*$('#SHIFT_NAME').val('');	
		$('#DESCRIPTION').val('');	
		$('#SHIFT_TYPE').val('');	
		$('#SHIFT_STARTS').val('');	
		$('#SHIFT_ENDS').val('');		
		$('#LATE_MARKING_STARTS').val('');		
		$('#EXIT_BUFFER_TIME').val('');		
		$('#LUNCH_BREAK_STARTS').val('');	
		$('#LUNCH_BREAK_ENDS').val('');	
		$('#EXTRA_BREAK_STARTS').val('');	
		$('#EXTRA_BREAK_ENDS').val('');	
		$('#EARLY_OUT_STARTS').val('');		
		$('#ENTRY_RESTRICTION_STARTS').val('');
		$('#STATUS').val('');	
		$('#SHIFT_POLICY_ID_TARGET').val('');*/
	}

}


// Show list when click on view

function showShiftInfo(){
	 
	var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
	var targetUrl 		= "policy/hrm/shiftPolicyInfoAjax";
	
	var sendData  		= { 
							ci_csrf_token:ci_csrf_token,

						  };
	var isJSON=0;
	
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#shiftPolicyInfoInnerHTML", "", isJSON, 0);
 
}




// Delete Shift Policy
function deleteShiftPolicyInfo(shift_policy_id)
{	
	if(shift_policy_id > 0){	

		var shift_policy_id_delete  = shift_policy_id;	
		var ci_csrf_token 			= $("input[name='ci_csrf_token']").val();	
		var targetUrl 				= "policy/hrm/deleteShiftPolicy";		
		var sendData  				= { 
										ci_csrf_token:ci_csrf_token,								
										SHIFT_POLICY_ID:shift_policy_id_delete													
							  		};
		var isJSON=0;
	}

	if( (shift_policy_id_delete!='') ){

		setInnerHTMLAjax(siteURL+targetUrl, sendData, "#shiftPolicyInfoInnerHTML", "", isJSON, 0);	
	}

}


function resetShift(){
	$('#SHIFT_NAME').val('');	
	$('#DESCRIPTION').val('');	
	$('#SHIFT_TYPE').val('');	
	$('#SHIFT_STARTS').val('');	
	$('#SHIFT_ENDS').val('');		
	$('#LATE_MARKING_STARTS').val('');		
	$('#EXIT_BUFFER_TIME').val('');		
	$('#LUNCH_BREAK_STARTS').val('');	
	$('#LUNCH_BREAK_ENDS').val('');	
	$('#EXTRA_BREAK_STARTS').val('');	
	$('#EXTRA_BREAK_ENDS').val('');	
	$('#EARLY_OUT_STARTS').val('');		
	$('#ENTRY_RESTRICTION_STARTS').val('');
	$('#STATUS').val('1');	
	$('#SHIFT_POLICY_ID_TARGET').val('');		
}
	
//========End Policy shift ========//	
	
	
	
	
//================ Add Bonus Policy Start ========================

// Bonus row add
function addBonusDetailsRow(event) 
{
	var _clone = $(event).closest("span").clone();
	_clone.find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
	_clone.find("a").removeClass("btn-primary").addClass("btn-danger");
	_clone.find("a").removeAttr("onclick").attr("onclick", "removeBonusDetailsRow(this)");
	
	$(".detailsContainer").append("<span class='row'>"+_clone.html()+"<span>");

	
}

function removeBonusDetailsRow(event){
	$(event).closest('span').remove();	
}


//Add Bonus policy 
function addBonusPolicy()
{		
	var BONUS_NAME   				= $("#BONUS_NAME").val();
	var DESCRIPTIONS   				= $("#DESCRIPTIONS").val();
	var BPSTATUS   					= $("#BPSTATUS").val();
	var BONUS_POLICY_MST_ID 		= $("#BONUS_POLICY_MST_ID").val();
	var BONUS_POLICY_DETAILS_ID		= $("#BONUS_POLICY_DETAILS_ID").val();
	

	var CONFIRMATION_STATUS=[];
	var WORKING_DAYS=[];
	var BASE_HEAD=[];
	var AMOUNT_TYPE=[];
	var AMOUNT=[];
	
	$.each($('select[name^="CONFIRMATION_STATUS[]"]'), function() {CONFIRMATION_STATUS.push($(this).val());});	
	$.each($('input[name^="WORKING_DAYS[]"]'), function() {WORKING_DAYS.push($(this).val());});
	$.each($('select[name^="BASE_HEAD[]"]'), function() {BASE_HEAD.push($(this).val());});		
	$.each($('select[name^="AMOUNT_TYPE[]"]'), function() {AMOUNT_TYPE.push($(this).val());});
	$.each($('input[name^="AMOUNT[]"]'), function() {AMOUNT.push($(this).val());});
	
	var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
	var targetUrl 		= "policy/hrm/bonusPolicyInfoAjax";
	
	var sendData  		= { 
							ci_csrf_token:ci_csrf_token,
							BONUS_NAME:BONUS_NAME,
							DESCRIPTIONS:DESCRIPTIONS,
							BPSTATUS:BPSTATUS,
							CONFIRMATION_STATUS:CONFIRMATION_STATUS,
							WORKING_DAYS:WORKING_DAYS,
							BASE_HEAD:BASE_HEAD,
							AMOUNT_TYPE:AMOUNT_TYPE,
							AMOUNT:AMOUNT,
							BONUS_POLICY_MST_ID:BONUS_POLICY_MST_ID,
							BONUS_POLICY_DETAILS_ID:BONUS_POLICY_DETAILS_ID	
						  };
	var isJSON=0;
	if((BONUS_NAME!='' & BPSTATUS!='' & CONFIRMATION_STATUS!='' & WORKING_DAYS!='' & BASE_HEAD!='' & AMOUNT_TYPE!='' & AMOUNT!='')){
		$.post(siteURL+targetUrl, sendData).success(function(response){
			viewBonusDetail();
			if(response != false){				
				resetBonus();
				
			}
		});						
	}else{		
		alert('Please Select * mark fields properly before Click on Plus Button');
	}	

}



// Edit Bonus Policy 
function editBonusInfo(BONUS_POLICY_MST_ID)
{
	var BONUS_POLICY_MST_ID = BONUS_POLICY_MST_ID;
		
	if(BONUS_POLICY_MST_ID > 0){
		
		var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
		var targetUrl 		= "policy/hrm/editBonusPolicyAjax";
		var sendData  		= {			
							BONUS_POLICY_MST_ID:BONUS_POLICY_MST_ID,													
							ci_csrf_token:ci_csrf_token							
							};							  		
		//evalDataByAjax(siteURL+targetUrl, sendData);
		$.ajax({
			type: "POST",
			url: siteURL+targetUrl, //calling method in controller
			data: sendData,
			dataType:'json',
			success: function (response) {				 
				eval(response.evalData);
				$("#detailsContainer").html(response.detailsHtml);
			},
			error: function(){ alert('Ooops ... server problem'); }
		});  
		
	} else {
		resetBonus();
	}
	
	
}

// Delete Bonus  Policy
function deleteBonusInfo(BONUS_POLICY_DETAILS_ID)
{	
	
	if(BONUS_POLICY_DETAILS_ID > 0){	
		var BONUS_POLICY_DETAILS_ID  = BONUS_POLICY_DETAILS_ID;	
		
				
		var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
		var targetUrl 		= "policy/hrm/deleteBonusPolicyAjax";		
		var sendData  		= { 
								ci_csrf_token:ci_csrf_token,								
								BONUS_POLICY_DETAILS_ID:BONUS_POLICY_DETAILS_ID												
							  };
		var isJSON=0;
	}
	if( (BONUS_POLICY_DETAILS_ID!='' ) ){	
		setInnerHTMLAjax(siteURL+targetUrl, sendData, "#bonusPolicyInfoInnerHTML", "", isJSON, 0);
			
	}

}

function resetBonus(){
	$('#BONUS_NAME').val('');	
	$('#DESCRIPTIONS').val('');	
	$('#BPSTATUS').val(1);		
	$('#BONUS_POLICY_MST_ID').val('');
	$('#detailsContainer span').not(':first').remove();	
	$('select[name^="CONFIRMATION_STATUS[]"').val('');  //dropdown		
	$('input[name^="WORKING_DAYS[]"').val('');		
	$('select[name^="BASE_HEAD[]"').val('');  //dropdown
	$('select[name^="AMOUNT_TYPE[]"').val('');  //dropdown	
	$('input[name^="AMOUNT[]"').val(''); 
}


function viewBonusDetail()
{					
	var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
	var targetUrl 		= "policy/hrm/showBonusInfo";		
	var sendData  		= { ci_csrf_token:ci_csrf_token	};
	var isJSON=0;
	
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#bonusPolicyInfoInnerHTML", "", isJSON, 0);
}


//Check Bonus policy Name
function bonusPolicyNameCheck(){	
	var BONUS_NAME  = $.trim($('#BONUS_NAME').val()); 	
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	
	if(BONUS_NAME != ""){		
		$("#existsBonusPolicyName").show();
		var targetUrl = "policy/hrm/CheckBonusPolicyNameAjax";
		
		var sendData  = { 
							BONUS_NAME:BONUS_NAME, 
							ci_csrf_token:ci_csrf_token 
						};	
		setInnerHTMLAjax(siteURL+targetUrl, sendData, "#existsBonusPolicyName");
		
	}
	else
	{
		$("#existsBonusPolicyName").hide();
		
	}
}


function checkNumericAmountForBonus(AMOUNT){
	
	var amount = $('input[name^="AMOUNT[]"').val('');
	alert(amount);

	if(isNaN(AMOUNT)){
		alert('Input only numaric');
		//$('input[name^="AMOUNT[]"').val('');		
	}

}



//================ Add Bonus Policy Start ========================


/*           roster policy           */


//================ Add Roster Policy
function addRosterInfo()
{	
	var ROSTER_POLICY_MST_ID   			= $("#ROSTER_POLICY_MST_ID").val();
	var combi_policy_name   			= $("#combi_policy_name").val();	
	var roster_policy_name   			= $("#roster_policy_name").val();
	var after_change_day   			= $("#after_change_day").val();
	var roster_policy_status   			= $("#roster_policy_status").val();

	var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
	var targetUrl 		= "policy/hrm/addRosterPolicyAjax";

	var sendData  		= { 
							ci_csrf_token:ci_csrf_token,
							combi_policy_name:combi_policy_name,
							roster_policy_name:roster_policy_name,						
							after_change_day:after_change_day,
							roster_policy_status:roster_policy_status,
							ROSTER_POLICY_MST_ID:ROSTER_POLICY_MST_ID
						  };
	var isJSON=0;
	if((combi_policy_name!='' & roster_policy_name!='' & after_change_day!='' & roster_policy_status != '')){
		setInnerHTMLAjax(siteURL+targetUrl, sendData, "#employeeRosterPolicyInnerHTML", "", isJSON, 0);	
		resetRoster();
	}else{
		alert('Please Check Star Marks');
	}	
}

function deleteRosterInfo(roster_id = 0) {
	if(roster_id > 0){	

		var roster_id  = roster_id;	
		var ci_csrf_token 			= $("input[name='ci_csrf_token']").val();	
		var targetUrl 				= "policy/hrm/deleteRosterPolicy";		
		var sendData  				= { 
										ci_csrf_token:ci_csrf_token,								
										ROSTER_POLICY_ID:roster_id													
							  		};
		var isJSON=0;
	}

	if( (roster_id!='') ){

		setInnerHTMLAjax(siteURL+targetUrl, sendData, "#employeeRosterPolicyInnerHTML", "", isJSON, 0);	
	}
}

function editRosterInfo(roster_id = 0) {
	if(roster_id > 0){		
		var ROSTER_POLICY_ID = roster_id;		
		var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();	
		var targetUrl 		= "policy/hrm/getRosterPolicyInfoAjax";
		var sendData  		= {	ROSTER_POLICY_ID: ROSTER_POLICY_ID, ci_csrf_token:ci_csrf_token };							  
		var result 			= evalDataByAjax(siteURL+targetUrl, sendData);	
	}else{
		resetRoster();
	}
}

function resetRoster(){	
	$('#combi_policy_name').val('');	
	$('#roster_policy_name').val('');		
	$('#after_change_day').val('');
	$('#roster_policy_status').val('1');	
	$('#ROSTER_POLICY_MST_ID').val('');		
}
