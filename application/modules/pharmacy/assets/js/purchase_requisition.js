$('#pharmacy_category').change(function(){
	var cat_id=$(this).val();
	var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();
	//targetUrl=siteURL+'purchase_requisition/pharmacy/getSubCategoryByCategoryId/'+cat_id;
	targetUrl=siteURL+'purchase_requisition/pharmacy/getProductByCategoryId/'+cat_id;

	$.get(targetUrl,function(data){
		//$('#pharmacy_sub_category').html(data);
		$('#pharmacy_product').html(data);
	})
})

$(document).on('change','#pharmacy_sub_category',function(){
	targetUrl=siteURL+'purchase_requisition/pharmacy/getProductBySubCategoryId/'+$(this).val();
	$.get(targetUrl,function(data){
		$('#pharmacy_product').html(data);
	})
})



$(document).on('change','#pharmacy_product',function(){
	var cat_id=$('#pharmacy_category').val();
	var cat=$('#pharmacy_category option:selected').text();	
	//var sub_cat_id=$('#pharmacy_sub_category').val();
	//var sub_cat=$('#pharmacy_sub_category option:selected').text();	
	var sub_cat_id=0
	var sub_cat=0
	var product_id=$(this).val();
	var product=$(this).find('option:selected').text();
	targetUrl=siteURL+'purchase_requisition/pharmacy/getProductStock/'+$(this).val();
	$.get(targetUrl,function(response){
		makeTableRow(cat,cat_id,product,product_id,response);
	});
})





$(document).on('select2:select','.medicine-auto-complete',function(e){
	var medicine_id=$(this).val();
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	var sendData  = {ci_csrf_token:ci_csrf_token, medicine_id: medicine_id};
	target=siteURL+'common/report/getProductinfoById/'+medicine_id;
	$.get(target,function(data){		
		makeTableRow(data.category_name,data.cat_id,data.product_name,data.id,data.current_stock);		
	},'json')
})



function makeTableRow(cat,cat_id,product,product_id,stock){

	var exist=0;
	$('#requistion_submit-data').find('.product_id').each(function(){			
		if($(this).val()==product_id){
			exist=1;
			return false;		}		
	})
	if(exist==1){
		alert('Already assigned');
		return false;
	}

	var row='';
	row+='<tr class="success">';

	row+='<td>'+cat+'<input type="hidden" name="category_id[]" value="'+cat_id+'" /> </td>';
	//row+='<td>'+sub_cat+'<input type="hidden" name="sub_category_id[]" value="'+sub_cat_id+'" /> </td>';
	row+='<td>'+product+'<input type="hidden" name="product_id[]" class="product_id" value="'+product_id+'" /> </td>';
	row+='<td>'+stock+'</td>';

	row+='<td><input type="text" name="requisition_quantity[] class="form-control" required=""/></td>';

	row+='<td><button type="button" class="remove"><i class="fa fa-times" aria-hidden="true"></i></button></td>';
	
	row+='</tr>';

	$('#requistion_submit-data').append(row);
}

$(document).on('submit','.common_search_form',function(e){
	//e.preventDefault();
	if($('.product_id').each().length==0){
		e.preventDefault();
		alertMessage('No Product Added');
	}
})

$(document).on('click','.remove-all',function(){
	$('#confirmAlertMessage').html('Are you sure want to remove all product from the list ?');
	$('#alertSize').removeClass('modal-sm');
	$('#confirmAlert').modal('show');
	$(document).on('click','#confirm',function(e){
		$('#requistion_submit-data').empty();
		$('#confirmAlert').modal('hide');
	})
})



function confirmMassege()
{
	var message=confirm("Are You Sure To Send Requisition !!!");
	if(message==true)
	{
           return true;
	}
	else
	{
        return false;
	}
}
