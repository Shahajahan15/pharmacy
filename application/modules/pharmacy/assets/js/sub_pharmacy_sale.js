$('#pharmacy_category').change(function(){
	var cat_id=$(this).val();
	var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();
	//targetUrl=siteURL+'sub_pharmacy_sale/pharmacy/getSubCategoryByCategoryId/'+cat_id;
	targetUrl=siteURL+'sub_pharmacy_sale/pharmacy/getProductByCategoryId/'+cat_id;
	$.get(targetUrl,function(data){
		//$('#pharmacy_sub_category').html(data);
		$('#pharmacy_medicine').html(data);
	})
});

$(document).on('change','#pharmacy_sub_category',function(){
	targetUrl=siteURL+'sub_pharmacy_sale/pharmacy/getProductBySubCategoryId/'+$(this).val();
	$.get(targetUrl,function(data){
		$('#pharmacy_medicine').html(data);
	})
});

// customer selection

 $(document).on("select2:select",".pharmacy-auto-complete",function() {
	var id = $(this).val();
	var url = siteURL+'main_pharmacy_sale/pharmacy/set_customer/'+id;	
	$.get(url,function(data){
		console.log(data);
		eval(data);
		customerCheck(id, 1);
	})
});

 $(document).on('select2:unselect',function(){
	$('#pharmacy_customer_type').val('').attr('readonly',false).trigger('change');
	$('#pharmacy_customer_name').val('').attr('readonly',false).trigger('change');
	$('#pharmacy_customer_phone').val('').attr('readonly',false).trigger('change');
	$('.patient-auto-complete').empty().trigger('change');
});


/*    customer type */

$('#pharmacy_customer_type').change(function(){
	var c_type = $(this).val();
	var label_name = "Doctor Name";
	if (c_type == 2) {
	 var label_name = "Employee Name";
	}
	var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();
	sendData = {ci_csrf_token:ci_csrf_token,c_type:c_type};
	targetUrl=siteURL+'sub_pharmacy_sale/pharmacy/getCustomerType';
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
   		$("input[type = 'submit']").prop('disabled', true);
   		var sendData = $(".sale_submit").serialize();
   		//console.log(sendData);return;
   		var master_id = $('.payment').attr('id');
   		var targetUrl = siteURL +"sub_pharmacy_sale/pharmacy/save";
   		$.ajax({
        url: targetUrl,
        type: "POST",
        data: sendData,
        dataType: "json",
        success: function (response) {
        	console.log(response);
        	if (response.success == true) {
        		$(".sale_submit")[0].reset();
        		$("#sale_data").empty();
        		$(".c_name, .c_phone").show();
        		$(".emp_doctor").hide();
        		$("#emp_doctor").removeAttr("required");
        		print_view(response.print);
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

$(document).on('click','.short_list_test',function(e){
    var medicine_id = $(this).attr('id');
        medicine_insert_data(medicine_id);
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
    		swal ( "Stock Not Available" , " " , "error" );
    		return false;
    	}
	});
	
});

// $(document).on('click','.medicine_item',function(e){
// 	var product_id=$(this).attr('id');
// 	medicine_insert_data(product_id);
// });

$(document).on('select2:select','.medicine-auto-complete',function(e){
    var product_id=$(this).val();
	
var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	var sendData  = {ci_csrf_token:ci_csrf_token, product_id: product_id};
	targetUrl=siteURL+'sub_pharmacy_sale/pharmacy/checkSubPharmacyMedicineStock';
	$.post( targetUrl, sendData).done( function( data ) { 
		//console.log("Data" + data);
    	if (data == true) {
    		medicine_insert_data(product_id);
    	} else {
    		swal ( "Stock Not Available" , " " , "error" );
    		return false;
    	}
	});





	});

function medicine_insert_data(product_id = 0)
{
	var patient_id=$("#patient_id").val();
	var c_type = $("#pharmacy_customer_type").val();
	if (c_type == 1) {
		var emp_id = 0;
	} else {
	   var emp_id = $("#emp_doctor").val();
	  	if (emp_id == ''){
			alert("Plz employee/doctor select...");
	  			return false;
		}
	}
	var check = 0;
	$('form table tbody').find('.product_id').each(function(){
			if ($(this).val() == product_id) {
				check = 1;
				return false;
			}
		});
	  if (check == 1){
	  	swal ( "Already Exit" , " " , "error" );
	  	return false;
	  }
	targetUrl=siteURL+'sub_pharmacy_sale/pharmacy/getMedicineInfo/'+product_id+'/'+c_type;
	$.get(targetUrl,function(response){
		//console.log(response);
	var row='';
	var total_price = 0;
	row+='<tr class="success">';

	row+='<td>'+response.category_info.category_name+'&nbsp; >> &nbsp;'+response.product_info.product_name+'<input type="hidden" class="product_id" name="product_id[]" value="'+response.product_info.id+'" /> </td>';
	row+='<td>'+response.product_info.sale_price+'<input type="hidden" class="tot_price" name="sale_price[]" value="'+response.product_info.sale_price+'" /> </td>';
	row+='<td>'+response.stock+'<input type="hidden" name="stock[]" class="stock" value="'+response.stock+'" /> </td>';
	row+='<td>'+response.n_discount_percent+'<input type="hidden" name="nd_percent[]" value="'+response.n_discount_percent+'" /> </td>';
	row+='<td>'+response.n_discount_amount+'<input type="hidden" class="nd_amount" name="nd_amount[]" value="'+response.n_discount_amount+'" /> <input type="hidden" class="n_discount_id" name="n_discount_id[]" value="'+response.n_discount_id+'" /><input type="hidden" class="n_discount_type" name="n_discount_type[]" value="'+response.n_discount_type+'" /></td>';
	row+='<td>'+response.s_discount_percent+'<input type="hidden" name="sd_percent[]" value="'+response.s_discount_percent+'" /> <input type="hidden" class="s_discount_id" name="s_discount_id[]" value="'+response.s_discount_id+'" /> <input type="hidden" class="s_discount_type" name="s_discount_type[]" value="'+response.s_discount_type+'" /></td>';
	row+='<td>'+response.s_discount_amount+'<input type="hidden" class="sd_amount" name="sd_amount[]" value="'+response.s_discount_amount+'" /> </td>';
	row+='<td><span class="tot_discount_text">'+response.total_discount_amount+'</span><input type="hidden" class="tot_discount" name="td_amount[]" value="'+response.total_discount_amount+'" /> </td>';
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
	var tot_price = parseFloat($(this).closest('tr').find('.tot_price').val());
	//var tot_discount = parseFloat($(this).closest('tr').find('.tot_discount').val());
	var stock = parseFloat($(this).closest('tr').find('.stock').val());
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
		$("#pharmacy_total_price,#pharmacy_total_due").val(Math.round(total_price));
		total_due();
	}
	
	function total_due()
	{
		var tot_paid = parseFloat($("#pharmacy_total_paid").val());
		var tot_less_due = parseFloat($("#pharmacy_total_less_discount").val());
		if (!tot_paid) {
			var tot_paid = 0;
		}
		if (!tot_less_due){
			var tot_less_due = 0;
		}
		var tot_price = parseFloat($("#pharmacy_total_price").val());
		var pid_less = (tot_paid + tot_less_due);
		if (pid_less > tot_price) {
			$("#pharmacy_total_paid, #pharmacy_total_less_discount").val("0");
	  		$("#pharmacy_total_due").val(Math.round(tot_price));
		} else {
			var tot_net_paid = (tot_paid + tot_less_due);
			var tot_due = (tot_price - tot_net_paid);
			$("#pharmacy_total_due").val(Math.round(tot_due));
		}
	}

	$(document).on("click",".main_pharmacy_bill",function(){	
    var test_name = $(this).closest('tr').find('.sale_no').text();
    //alert(test_name);return;
	$('#commonModalTitle').html("<b style='text-align:center'>Paid("+test_name+")</b>");
	$('#commonModalFooter').remove();
	$('#commonModalBody').html('<div class="loader"></div>');
	$('#commonModal').modal('show');
	
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	var sendData  = {
		customer_id 	: $(this).attr('customer_id'),
		employee_id 	: $(this).attr('employee_id'),
		patient_id 		: $(this).attr('patient_id'),
		admission_id 	: $(this).attr('admission_id'),
		customer_type 	: $(this).attr('customer_type'),
		ci_csrf_token	: ci_csrf_token
	};
	var targetUrl = siteURL +"sub_pharmacy_sale_list/pharmacy/due_paid";
   	$.post( targetUrl, sendData).done( function( data ) { 
    	$('#commonModalBody').html(data);
	}); 
	
});

  $("#mr_num_sp").prop('disabled', true);
  $(document).on('change', '#pharmacy_customer_type_sp', function(){
  	var val = $(this).val();
  	$("#mr_num_sp").prop('disabled', true);
  	if (val) {
  		$("#mr_num_sp").prop('disabled', false);
  	}
  });

  function resetMPForm() {

	$("input[type = 'submit']").prop('disabled', false);
	$(".sale_submit")[0].reset();
	$(".medicine-auto-complete").empty().trigger('change');
	$("#emp_doctor").val('');
	//$().val('');
	$("#sale_data").html('');
	$(".c_name, .c_phone").show();
	$(".emp_doctor").hide();
	$("#emp_doctor").removeAttr("required");
     }