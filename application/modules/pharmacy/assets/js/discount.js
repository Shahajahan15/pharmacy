$(document).on('click','.medicine_item',function(){
	var product_id=$(this).attr('id');
	var product_name=$(this).text();
	var exist=0;
	$('#requistion_submit-data').find('.product_id').each(function(){
		if($(this).val()==product_id){
			exist=1;
			return false;
		}
	})
	if(exist==1){		
		alert('Already Exist');
		return false;
	}



	var row='';
		row+='<tr class="success">';
		row+='<td>'+product_name+'';
		row+='<input type="hidden" class="form-control product_id" name="product_id[]" value="'+product_id+'"></td>';

		row+='<td><input type="text" class="form-control discount_parcent" name="discount_parcent[]" required=""></td>';
		row+='<td><input type="text" class="form-control discount_from datepickerCommon" name="discount_from[]" required=""></td>';
		row+='<td><input type="text" class="form-control discount_to datepickerCommon" name="discount_to[]" required=""></td>';

		row+='<td><button type="button" class="remove"><i class="fa fa-times" aria-hidden="true"></i></button></td>';

		row+='</tr>';


	$('#requistion_submit-data').append(row);

	$(this).closest('.form-group').find('.auto_name_list').empty();
	 datepicker();
})


$(document).on('keyup','.discount_parcent',function(){
	if($(this).val()>100){
		$(this).val('');
	}
})

$(document).on('change','#discount_on',function(){	
	if($(this).val()==1){
		$('.medicine_name-search').attr('disabled','true');
		$(this).closest('fieldset').find('.discount_from , .discount_to , .discount_parcent').attr('disabled',false);
		$('#requistion_submit-data').empty();
	}else{
		$('.medicine_name-search').attr('disabled',false);
		$(this).closest('fieldset').find('.discount_from , .discount_to , .discount_parcent').attr('disabled','true');
	}
})


$(document).on('click','.discount_without',function(){

	var discount_id=$(this).attr('id');
	var target_url=siteURL+'discount/pharmacy/without_from_all_list/'+discount_id;
	$.get(target_url,function(data){
		console.log(data);
		var sl=0;
		var table='<table class="table"><tr class="active"><th>SL</th><th>Product Name</th><th>Discount</th><th>Discount From</th> <th>Discount To</th></tr>'
		
		$('#commonModalTitle').html('All Discout Not applicable Products');
		$('#commonModalFooter').html('');
		$('#commonModalBody').html('<div class="loader"></div>');
		$('#commonModal').modal('show');

		for(pro in data){
			console.log(data[pro]);
			table+='<tr class="info">';
			table+='<td>'+(sl+=1)+'</td>';
			table+='<td>'+data[pro].product_name+'</td>';
			table+='<td>'+data[pro].discount_parcent+'</td>';
			table+='<td>'+data[pro].discount_from+'</td>';
			table+='<td>'+data[pro].discount_to+'</td>';
		}
		table+='</table>';
		$('#commonModalBody').html(table);
		

	},'json')
})


$(document).on('click','.change_discount',function(){
	var discount_id=$(this).attr('discount_id');
	var product_id=$(this).attr('id');

	$('.discount_submit_data').html('<input type="hidden" name="discount_id" value="'+discount_id+'"/>');
	$('.discount_submit_data').append('<input type="hidden" name="product_id" value="'+product_id+'"/>');

	$('#changeDiscount').modal('show');

	if(discount_id==0){
		$('.discount_submit_data').append('Discount From: <input type="text" class="form-control datepickerCommon" required="" name="discount_from" value=""/>');
		$('.discount_submit_data').append('Discount to: <input type="text" class="form-control datepickerCommon" required="" name="discount_to" value=""/>');
		$(".datepickerCommon").datepicker({
    		format:'dd/mm/yyyy'
		});
	}

})