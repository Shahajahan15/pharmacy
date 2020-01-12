$(document).on('change','.service_id',function(){
	var id=$(this).val();
	var $this = $(this);
	var targetUrl=siteURL+'discount_setup/library/getSubServiceByServiceId/'+id;

	$.get(targetUrl,function(data){
		var data=$.parseJSON(data);
		
		$this.closest('fieldset').find('.sub_service_id').html(data);
	})
})
/*
$(document).ready(function(){
	var fields=$('.multi-input-fields').html();
	$(document).on('click','#new-discount-field',function(){
		$('tbody').append(fields);
	})

	$(document).on('click','.delete-new-discount-field',function(){
		$(this).closest('tr').remove();
	})
})
*/

$(document).on('click','#discount_details_info',function(){
	var id=$(this).attr('discount_id');
	var targetUrl=siteURL+'patient_discount_setup/library/getDetailsDiscountInfo/'+id;

	$.get(targetUrl,function(data){
		var data=$.parseJSON(data);
		$('#details_discount_table').html(data);
		$('#view_details_discount').modal('show');
	})
})


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
	row+='<td><select name="discount_type[]" class="form-control" required=""><option value="1">Percentage</option><option value="0">Amount</option></select> </td>';
	row+='<td><input type="text" name="discount[]" class="form-control decimal" value="" required="" /> </td>';
	row+='<td><select name="discount_unit[]" class="form-control discount_unit" required=""><option value="1">Day</option><option value="2">Hour</option></select> </td>';

	row+='<td><button type="button" class="remove"><i class="fa fa-times" aria-hidden="true"></i></button></td>';
	row+='</tr>';

	$('.multi-input-fields').append(row);
})



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
	var targetUrl=siteURL+'patient_discount_approve/library/approved/'+id+"/"+h_discount;
	$.get(targetUrl,function(data){		
		if(data.approve_status==true){
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

$(document).on('click','.approve-cancel',function(){
	if (!confirm("Are You To Cancel!")) {
		return false;
	}
	var id=$(this).attr('id');
	var $this=$(this);
	var h_discount = parseFloat($(this).closest("tr").find(".h_discount").val());
	var targetUrl=siteURL+'patient_discount_approve/library/approved_cancel/'+id+"/"+h_discount;
	$.get(targetUrl,function(data){		
		if(data.approve_status==true){
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