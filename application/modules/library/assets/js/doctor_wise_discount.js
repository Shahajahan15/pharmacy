$(document).on('change','.service_id',function(){
	var id=$(this).val();
	var $this = $(this);
	var targetUrl=siteURL+'discount_setup/library/getSubServiceByServiceId/'+id;

	$.get(targetUrl,function(data){
		var data=$.parseJSON(data);
		
		$('.sub_service_id').html(data);
	})
});

$(document).on('change','#agent_type',function(){
    var agent_type = $(this).val();
    if(agent_type==""){        
        $('#agent').html('<option value="">Not Available</option>');
        return  false;
    }
    var target = siteURL+'commission_setup/doctor/getAgentByType/'+agent_type;
    $('#agent').html('<option value="">Searching....</option>');
    $.get(target,function(data){
        $('#agent').html(data);
    })
})

function checkDuplicateSubService(sub_service_id = 0, discount_type = 0){
	var exist=true;
	if (discount_type == 2) {
		$('#discount_body_show').find('.sub_service_id').each(function(){
	        if($(this).val()==sub_service_id){
	            exist=false;
	            return false;
	        }
    	});
	} else {
		var rowCount = $('#discount_body_show tr').length;
		if (rowCount > 0) {
			exist = 1;
			return false;
		}
	}
    return exist;
}

function showValidation() {agent_type
	var patient_name = $(".patient_name").val();
	var patient_id = $(".patient_id").val();
	var sex = $('#sex').val();
	var dob = $('#dob').val();
	var contact_no = $('#contact_no').val();
	var agent = $("#agent").val();
	var agent_type = $("#agent_type").val();
	var discount_type = $(".discount_type").val();
	var suggest_discount = $(".suggest_discount").val();
	var service_id = $(".service_id").val();
	var sub_service_id = $(".sub_service_id").val();
	$(".p_name,.p_dob,.p_sex, .p_conNo, .a_type,.a_id,.s_id,.d_p,.ss_id").removeClass("has-error");
	if (!patient_name) {
		$(".p_name").addClass("has-error");
	} else {
		$(".p_name").removeClass("has-error");
	}
	if (!sex) {
		$(".p_sex").addClass("has-error");
	}
	if (!dob) {
		$(".p_dob").addClass("has-error");
	}
	if (!contact_no) {
		$(".p_conNo").addClass("has-error");
	}
	if (!agent_type) {
		$(".a_type").addClass("has-error");
	}
	if (!agent) {
		$(".a_id").addClass("has-error");
	}
	if (!suggest_discount) {
		$(".d_p").addClass("has-error");
	}
	if (!service_id) {
		$(".s_id").addClass("has-error");
	}
	if (discount_type == 1) {
		if (!patient_name || !sex || !dob || !contact_no || !agent || !agent_type || !suggest_discount || !service_id) {
			alert('Plz All Select....');
		return false;
	}
	} else {
		if (!sub_service_id) {
			$(".ss_id").addClass("has-error");
		}
		if (!patient_name || !sex || !dob || !contact_no || !agent || !agent_type || !suggest_discount || !service_id || !sub_service_id) {
			alert('Plz All Select....');
		return false;
	}
	}
}

function resetDoctorDiscountForm(){
	location.reload();

	$("input[type = 'submit']").prop('disabled', false);
	$(".doctor_discount_form")[0].reset();
	$(".patient-auto-complete").empty().trigger('change');
	$("#discount_show").html('');
}

         /*      doctor discount show              */

$(document).on("click", ".show", function(){
	/*var patient_id = $("#patient_id").val();
	var patient_name = $("#patient_name").val();
	var sex = $("#sex").val();
	var dob = $("#dob").val();
	var contact_no = $("#contact_no").val();*/

	var ref_id = $("#agent").val();
	var agent_type = $("#agent_type").val();
	var discount_type = $(".discount_type").val();
	var suggest_discount = $(".suggest_discount").val();
	var service_id = $(".service_id").val();
	var sub_service_id = $(".sub_service_id").val();
	var sub_service_name=$('.sub_service_id :selected').text();
	if (discount_type == 1) {
		var sub_service_id = 0;
		var sub_service_name = "";
	}
	if (showValidation() == false) {
		return false;
	}
	if(checkDuplicateSubService(sub_service_id, discount_type) == false){
            return false;
        }

	var targetUrl=siteURL+'doctor_wise_discount/library/showDiscount';
	if (discount_type == 1) {
		//$(this).attr("disabled", true);
	}
	var sendData={
		/*patient_id: patient_id,
		patient_name: patient_name,
		sex: sex,
		dob: dob,
		contact_no: contact_no,*/

		ref_id: ref_id,
		agent_type: agent_type,
		service_id: service_id,
		suggest_discount: suggest_discount,
		discount_type: discount_type,
		sub_service_id: sub_service_id,
		sub_service_name: sub_service_name,
		ci_csrf_token: $('input[name="ci_csrf_token"]').val()
	}
	$.post(targetUrl,sendData,function(data){
		//console.log('data=' + data);
		if (data.error == true) {
			if (data.commission > 0) {
			$('#discount_show,.submit-btn').show();
			$('#discount_body_show').append(data.page);
		} else {
			showMessages("Plz, Commission Entry....",'error');
		}
	} else {
		showMessages("Plz, Commission Entry....",'error');
	}
		

		
	},"json");

});

/*     Hospital discount check          */

$(document).on("keyup",".hospital_discount", function() {
	var h_discount = parseFloat($(this).closest("tr").find(".hospital_discount").val());
	var d_discount = parseFloat($(this).closest("tr").find(".dr_discount").val());
	var total_discount = h_discount + d_discount;
	console.log("d discount ="+d_discount+", h_discount="+h_discount+"tot discount ="+ total_discount);
	if (total_discount > 100) {
		$(this).closest("tr").find(".hospital_discount").val(0);
	}
});

$(document).on('select2:select','.patient-auto-complete',function(){
	var targetUrl=siteURL+'doctor_wise_discount/library/getPatient';
	var sendData={
		id:$(this).val(),
		ci_csrf_token:$('input[name="ci_csrf_token"]').val()
	}
	$.post(targetUrl,sendData,function(data){
		eval(data);
	})
})

$(document).on('change','.discount_type',function(){
	var discount_type=$(this).val();
	if(discount_type==1){
		$('.sub_service_id').attr('disabled',true);
		$('.sub_service_id').attr('required',false);
		$(".sub_required").hide();
		//$('.discount_percent-if-overall').attr('disabled',false);
		$('.multi-input-fields').empty();
	}else{
		$('.sub_service_id').attr('disabled',false);
		$('.sub_service_id').attr('required',true);
		$(".sub_required").show();
		//$('.discount_percent-if-overall').attr('disabled',true);
		//$('.discount_percent-if-overall').val('');
	}
})

/*

$(document).on('change','.sub_service_id',function(){
	var service_id=$('.service_id').val();
	var service_name=$('.service_id :selected').text();
	var sub_service_id=$('.sub_service_id').val();
	var sub_service_name=$('.sub_service_id :selected').text();

	var exist=0;
	$('.multi-input-fields').find('.sub_service_id').each(function(){
		if($(this).val()==sub_service_id){			
			if($(this).closest('tr').find('.service_id').val()==service_id){
				exist=1;				
				return false;
			}

		}		
	})
	if(exist==1){
		return false;
	}



	var row='<tr class="success">';

	row+='<td>'+service_name+'<input type="hidden" name="service_id[]" class="form-control service_id" value="'+service_id+'" /> </td>';
	row+='<td>'+sub_service_name+'<input type="hidden" name="sub_service_id[]" class="form-control sub_service_id" value="'+sub_service_id+'" /> </td>';
	
	row+='<td><input type="text" name="discount_percent[]" class="form-control decimal" value="" required="" /> </td>';
	

	row+='<td><button type="button" class="remove"><i class="fa fa-times" aria-hidden="true"></i></button></td>';
	row+='</tr>';

	$('.multi-input-fields').append(row);
});

*/



$(document).on('click','button[type="reset"]',function(){
	$('.multi-input-fields').empty();
})

$(document).on('click','.approved-now',function(){
	if (!confirm("Are You Confirm!")) {
		return false;
	}
	var id=$(this).attr('id');
	var $this=$(this);
	var h_discount = parseFloat($(this).closest("tr").find(".h_discount").val());
	var targetUrl=siteURL+'doctor_wise_discount/library/approved/'+id+"/"+h_discount;
	$.get(targetUrl,function(data){		
		if(data.status==true){
			$this.closest('tr').remove();
			showMessages(data.message,'success');			
		}else{
			if (data.check == false) {
				$this.closest('tr').remove();
			}
			showMessages(data.message,'error');
		}
	},'json')
});

$(document).on("keyup", ".h_discount", function(){
	var d_discount = parseFloat($(this).closest("tr").find(".dr_discount").text());
	var h_discount = parseFloat($(this).closest("tr").find(".h_discount").val());
	var total_discount = d_discount + h_discount;
	if (total_discount > 100) {
		var h_discount = 0;
		$(this).closest("tr").find(".h_discount").val(h_discount.toFixed(2));
		var total_discount = d_discount + h_discount;
	}
	
	$(this).closest("tr").find(".tot_discount").text(total_discount.toFixed(2));
});