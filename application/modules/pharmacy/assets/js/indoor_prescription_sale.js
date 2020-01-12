$('#pharmacy_category').change(function(){
	var cat_id=$(this).val();
	var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();
	//targetUrl=siteURL+'indoor_prescription_sale/pharmacy/getSubCategoryByCategoryId/'+cat_id;
	targetUrl=siteURL+'indoor_prescription_sale/pharmacy/getProductByCategoryId/'+cat_id;

	$.get(targetUrl,function(data){
		//$('#pharmacy_sub_category').html(data);
		$('#pharmacy_medicine').html(data);
	})
});

$(document).on('change','#pharmacy_sub_category',function(){
	targetUrl=siteURL+'indoor_prescription_sale/pharmacy/getProductBySubCategoryId/'+$(this).val();
	$.get(targetUrl,function(data){
		$('#pharmacy_medicine').html(data);
	})
});

$(document).on('click','.p_print',function(){
	var id = $(this).attr('id');
	var _this = $(this).closest('tr');
	//alert(id);return;
	targetUrl=siteURL+'indoor_prescription_sale/pharmacy/prescription_print/'+id;
	$.get(targetUrl,function(response){
		//console.log(response);
        	if (response.success == true) {
                showMessages(response.message, 'success');
                $(_this).fadeOut(1000, function () {
	    			$(_this).remove();
	    		});
                print_view(response.print);
            } else {
                showMessages(response.message, 'error');
            }
	},"json")
});
/*  print    */

/*    customer type */

$('#pharmacy_customer_type').change(function(){
	var c_type = $(this).val();
	var label_name = "Doctor Name";
	if (c_type == 2) {
	 var label_name = "Employee Name";
	}
	var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();
	sendData = {ci_csrf_token:ci_csrf_token,c_type:c_type};
	targetUrl=siteURL+'indoor_prescription_sale/pharmacy/getCustomerType';
	$.post( targetUrl, sendData).done( function( response ) { 
	console.log(response);
	if (c_type != 1)
	{
		$(".c_name, .c_phone").hide();
		$(".emp_doctor").show();
		$(".ed_label").html(label_name + "<span class='required'>*</span>");
		$("#emp_doctor").html(response);
		$("#emp_doctor").attr("required");
	} else {
		$(".emp_doctor").hide();
		$(".c_name, .c_phone").show();
		$("#emp_doctor").removeAttr("required");
	}
	});
});

/*  submit sales */

$(document).on("submit",".sale_submit",function(e){
   		e.preventDefault();
   		var sendData = $(".sale_submit").serialize();
   		//console.log(sendData);return;
   		var master_id = $('.payment').attr('id');
   		var targetUrl = siteURL +"indoor_prescription_sale/pharmacy/save";
   		$.ajax({
        url: targetUrl,
        type: "POST",
        data: sendData,
        dataType: "json",
        success: function (response) {
        	//console.log(response);
        	if (response.success == true) {
        		//$(".sale_submit")[0].reset();
        		//$("#sale_data").empty();
        		window.location = siteURL +"indoor_prescription_sale/pharmacy/show_list";
        		//print_view(response.print);
                showMessages(response.message, 'success');
            } else {
                showMessages(response.message, 'error');
            }
        }, error: function (jqXHR) {
        	console.log(jqXHR);
            showMessages('Unknown Error!!!', 'error');
        }
    });
   });



$(document).on('change','#pharmacy_medicine',function(){
	var product_id=$(this).val();
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	var sendData  = {ci_csrf_token:ci_csrf_token, product_id: product_id};
	targetUrl=siteURL+'sub_pharmacy_sale/pharmacy/checkSubPharmacyMedicineStock';
	$.post( targetUrl, sendData).done( function( data ) { 
		//console.log("Data" + data);
    	if (data == true) {
    		medicine_insert_data(product_id);
    	} else {
    		alert("Stock Empty!");
    		return false;
    	}
	});
	
});

$(document).on('select2:select','.medicine-auto-complete',function(e){
    var product_id=$(this).val();
	
var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	var sendData  = {ci_csrf_token:ci_csrf_token, product_id: product_id};
	targetUrl=siteURL+'sub_pharmacy_sale/pharmacy/checkSubPharmacyMedicineStock';
	$.post( targetUrl, sendData).done( function( data ) { 
		console.log("Data" + data);

    	if (data == true) {
    		medicine_insert_data(product_id);
    	} else {
    		swal ( "Stock Not Available" , " " , "error" );
    		return false;
    	}
	});

});

    function medicine_insert_data(product_id = 0){
	
	var patient_id=$("#patient_id").val();
	var check = 0;
	$('form table tbody').find('.product_id').each(function(){
			if ($(this).val() == product_id) {
				check = 1;
				return false;
			}
		});
	  if (check == 1){
	  	alert("already exit !");
	  	return false;
	  }
	targetUrl=siteURL+'indoor_prescription_sale/pharmacy/getMedicineInfo/'+product_id+"/" +patient_id;
	$.get(targetUrl,function(response){
		console.log(response);
	var row='';
	var total_price = 0;
	row+='<tr class="success">';

	row+='<td>'+response.category_info.category_name+'&nbsp; >> &nbsp;'+response.product_info.product_name+'<input type="hidden" class="product_id" name="product_id[]" value="'+response.product_info.id+'" /> </td>';
	row+='<td>'+response.product_info.sale_price+'<input type="hidden" class="tot_price" name="sale_price[]" value="'+response.product_info.sale_price+'" /> </td>';
	row+='<td>'+response.stock+'<input type="hidden" name="stock[]" class="stock" value="'+response.stock+'" /> </td>';
	row+='<td>'+response.n_discount_percent+'<input type="hidden" name="nd_percent[]" value="'+response.n_discount_percent+'" />% </td>';
	row+='<td>'+response.n_discount_amount+'<input type="hidden" class="nd_amount" name="nd_amount[]" value="'+response.n_discount_amount+'" /> </td>';
	row+='<td>'+response.s_discount_percent+'<input type="hidden" name="sd_percent[]" value="'+response.s_discount_percent+'" />% </td>';
	row+='<td>'+response.s_discount_amount+'<input type="hidden" class="sd_amount" name="sd_amount[]" value="'+response.s_discount_amount+'" /> </td>';
	row+='<td><span class="tot_discount_text">'+response.total_discount_amount+'</span><input type="hidden" class="tot_discount" name="td_amount[]" value="'+response.total_discount_amount+'" /> </td>';
	row+='<td>0<input type="hidden" name="roule_id[]" value="0"><input type="hidden" value="0" name="duration[]"></td>';
	row+='<td><input type="text" name="qnty[]" autocomplete="off" class="form-control s_qnty" value="'+response.qnty+'" style="width:50px;" required=""/></td>';
	row+='<td><span class="sub_total_text">'+response.sub_total+'</span><input type="hidden" name="sub_total[]" class="sub_total" value="'+response.sub_total+'" /> </td>';
	row+='<td><button type="button" class="remove s_remove"><i class="fa fa-times" aria-hidden="true"></i></button></td>';
	
	row+='</tr>'

	$('#sale_data').append(row);
	total();
	},'json').fail(function(xhr) {
          console.log(xhr);
  });
	}
$(document).on('keyup','.s_qnty',function(){
	var qnty = parseFloat($(this).val());
	var stock = parseFloat($(this).closest('tr').find('.stock').val());
	var tot_price = parseFloat($(this).closest('tr').find('.tot_price').val());
	//var tot_discount = parseFloat($(this).closest('tr').find('.tot_discount').val());
	var nd_amount = parseFloat($(this).closest('tr').find('.nd_amount').val());
	var sd_amount = parseFloat($(this).closest('tr').find('.sd_amount').val());
	 if (stock < qnty) {
	 	$(this).val(stock);
	 	var qnty = stock;
	 }
	var tot_discount = parseFloat((nd_amount * qnty) + (sd_amount * qnty));
	var sub_total = parseFloat((tot_price *qnty) - tot_discount);
	$(this).closest('tr').find('.tot_discount').val(tot_discount.toFixed(2));
	$(this).closest('tr').find('.tot_discount_text').text(tot_discount.toFixed(2));
	$(this).closest('tr').find('.sub_total').val(sub_total.toFixed(2));
	$(this).closest('tr').find('.sub_total_text').text(sub_total.toFixed(2));
	total();
	});
$(document).on('click','.s_remove',function(){
	total();
	});	
$(document).on('keyup','#pharmacy_total_paid, #pharmacy_total_less_discount',function(){
	total_due();
	});
	
	function total(){
		total_price = 0;
		$('form table tbody .sub_total').each(function(){
			total_price += parseFloat($(this).val());
		});
		console.log("total="+total_price);
		$("#pharmacy_total_price,#pharmacy_total_due").val(total_price.toFixed(2));
		total_due();
	}
	
	function total_due()
	{
		var tot_paid = parseFloat($("#pharmacy_total_paid").val());
		var tot_less_due = parseFloat($("#pharmacy_total_less_discount").val());
		var tot_price = parseFloat($("#pharmacy_total_price").val());
		var tot_net_paid = (tot_paid + tot_less_due);
		var tot_due = (tot_price - tot_net_paid);
		$("#pharmacy_total_due").val(tot_due.toFixed(2));
	}