$('#store_category').change(function(){
	var cat_id=$(this).val();
	var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();
	//targetUrl=siteURL+'indoor_stock_requisition/pharmacy/getSubCategoryByCategoryId/'+cat_id;
	targetUrl=siteURL+'indoor_stock_requisition/pharmacy/getProductByCategoryId/'+cat_id;

	$.get(targetUrl,function(data){
		//$('#store_sub_category').html(data);
		$('#store_product').html(data);
	})
})

$(document).on('change','#store_sub_category',function(){
	targetUrl=siteURL+'indoor_stock_requisition/pharmacy/getProductBySubCategoryId/'+$(this).val();
	$.get(targetUrl,function(data){
		$('#store_product').html(data);
	})
})



$(document).on('change','#store_product',function(){
	if($(this).val()==""){
		return false;
	}
	var cat_id=$('#store_category').val();
	var cat=$('#store_category option:selected').text();	
	//var sub_cat_id=$('#store_sub_category').val();
	//var sub_cat=$('#store_sub_category option:selected').text();
	var sub_cat_id=0;
	var sub_cat=0;	
	var product_id=$(this).val();
	var product=$(this).find('option:selected').text();
	makeTableRow(cat,cat_id,product,product_id);
	
})
function makeTableRow(cat,cat_id,product,product_id, current_stock){

	var exist=0;
	$('#requistion_submit-data').find('.product_id').each(function(){			
		if($(this).val()==product_id){
			exist=1;
			return false;
		}		
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
	row+='<td class="issue_stock">'+Math.round(current_stock)+'</td>';

	row+='<td><input type="text" name="requisition_quantity[]" autocomplete="off" class="form-control decimal" required=""/></td>';

	row+='<td><button type="button" class="remove"><i class="fa fa-times" aria-hidden="true"></i></button></td>';
	
	row+='</tr>'	

	$('#requistion_submit-data').append(row);
}

$(document).on('select2:select','.medicine-auto-complete',function(e){
	var medicine_id=$(this).val();
	var pharmacy_id = $('#issue_pharmacy_id').val();
	var $this=$(this);
	target=siteURL+'common/report/getProductinfoById/'+medicine_id+"/"+pharmacy_id;
	$.get(target,function(data){	
	$('.medicine-auto-complete').empty().trigger('change');	
		//console.log(data);
		// if (!data.current_stock) {
		// 	alert("Stock Empty!");
		// 	return false;
		// }

		if (!data.current_stock) {
			swal ( "Stock Not Available" , " " , "error",{closeOnClickOutside: false});
			return false;
		}


		$this.closest('.autocomplete_box').empty();
		makeTableRow(data.category_name,data.cat_id,data.product_name,data.id,data.current_stock);		
	},'json')
});


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

/*            stock check        */

$(document).on('keyup','input[name = "requisition_quantity[]"]', function(){
	var pharmacy_id = $('#issue_pharmacy_id').val();
	if (!pharmacy_id) {
		alert("Please select pharmacy name");
		$(this).val('');
		return false;
	}
	var req_qnty = parseInt($(this).val());
	var issue_stock = parseInt($(this).closest('tr').find('.issue_stock').text());
	console.log("req_qnty="+req_qnty+"issue_stock"+issue_stock);
	if(req_qnty > issue_stock) {
		$(this).val('');
	}
});

$(document).on('change', '#issue_pharmacy_id', function(){
	$('#requistion_submit-data').empty();
});