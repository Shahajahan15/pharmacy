function check_duplicate(product_id){
		var exist=0;
	$('#package-products').find('.product_id').each(function(){		
		if(product_id==$(this).val()){
			exist=1;
			return false;
		}
	})
	return exist;
	
}

$(document).on('select2:select','.medicine-auto-complete',function(e){
	var product_id=$(this).val();
	//console.log(product_id); return;	
	if(check_duplicate(product_id)==1){
		return false;
	}
	
	var $this=$(this);
	target=siteURL+'common/report/getPharmacyPurchaseProductinfoById/'+product_id;
	$.get(target,function(data){
		makeTableRow(data);	
		$this.closest('.auto_name_list').empty();
		$('.canteen_purchase_product').val('');
	},'json')
})

$(document).on('change','#canteen_product',function(){
	var product_id=$(this).val();
	if(check_duplicate(product_id)==1){
		return false;
	}

	var $this=$(this);
	target=siteURL+'common/report/getPharmacyPurchaseProductinfoById/'+product_id;
	$.get(target,function(data){
		makeTableRow(data);	
	},'json')
})



function makeTableRow(data){	

	var row='';
	row+='<tr class="success">';
	
	row+='<td>'+data.product_name+'<input type="hidden" name="product_id[]" class="product_id" value="'+data.id+'" /> </td>';
	row+='<td><div class="input-group"><input type="text" readonly="" name="unit_price[]" value="'+data.sale_price+'" class="form-control unit_price" required=""/><span class="input-group-btn"><button class="btn btn-xs" type="button" style="padding:3px;width:50px">/'+data.unit_name+'</button></span></td>';
	row+='<td><button type="button" class="remove-pack-pro"><i class="fa fa-times" aria-hidden="true"></i></button></td>';
	row+='</tr>';

	$('#package-products').append(row);
	total_price();
}


$('#canteen_category').change(function(){
	var cat_id=$(this).val();
	var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();
	targetUrl=siteURL+'stock_purchase/canteen/getProductByCategoryId/'+cat_id;

	$.get(targetUrl,function(data){
		$('#canteen_product').html(data);
	})
})


function total_price(){
	var total=0;
	$('#package-products').find('.unit_price').each(function(){		
		 total+=parseFloat($(this).val());
	})

	$('.package_total_price').html(total);
	var discount_taka=$('.discount_taka').val();
	$('.package_price').val(total-discount_taka);
}


$(document).on('click','.remove-pack-pro',function(){
	$(this).closest('tr').remove();
	total_price();
})


$(document).on('keyup change','.discount_taka',function(){
	var discount_taka=$(this).val();
	var package_total_price=parseFloat($('.package_total_price').text());
	if(package_total_price>0){
		$('.package_price').val(package_total_price-discount_taka);
	}
})

$(document).on('click','.p-details',function(){
	var master=$(this).attr('id');	
	var target=siteURL+'food_package/canteen/details_package/'+master;

	$('#commonModalTitle').html('Package Details Information');
	$('#commonModalFooter').remove();
	$('#commonModalBody').html('<div class="loader"></div>');
	$('#commonModal').modal('show');
	$.get(target,function(data){
		$('#commonModalBody').html(data);
	})

})

$(document).on('click','[type="reset"]',function(){
	$('#package-products').empty();
	total_price();
})


$(document).on('submit','form',function(e){	
	var products=$('#package-products').find('tr').length;
	//console.log(products);
	if(products==0){
		e.preventDefault();
		alertMessage('No Product Added in the package');
	}
})