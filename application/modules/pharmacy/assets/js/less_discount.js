/*        get sale info       */

$(document).on('click','.sale_no',function(e){
		var sale_id = $(this).attr('id');
		var ci_csrf_token = $("input[name='ci_csrf_token']").val();
		var sendData = {sale_id:sale_id,ci_csrf_token:ci_csrf_token};
		var targetUrl = siteURL +"main_pharmacy_sale_return/pharmacy/getSaleInfo";
		$.ajax({
        url: targetUrl,
        type: "POST",
        data: sendData,
        dataType: "json",
        success: function (response) {
        	console.log(response);
        	if (response.success == false){
				showMessages(response.message, 'error');
			} else {
			$("#return_sale_data").html(response.return_info);
        	$("#pharmacy_total_return_amount").val(response.tot_sub_amount);
        	$("#tot_bill").val(response.total_bill);
        	$("#total_less_dsc").val(response.total_less_discount);
        	$("#tot_due").val(response.total_bill-response.total_paid-response.total_less_discount);
        	$("#tot_paid").val(response.total_paid);
        	$("#pharmacy_tot_charge_amount").val(response.tot_charge);
        	//$("#pharmacy_total_return_unit_amount, #pharmacy_return_taka").val(response.tot_unit_amount);
			}
        	
        }, error: function (jqXHR) {
        	console.log(jqXHR);
            showMessages('Unknown Error!!!', 'error');
        }
    });
	});

/*  submit sales */

$(document).on("submit",".sale_return_submit",function(e){
   		e.preventDefault();
   		$("input[type = 'submit']").prop('disabled', true);
   		var sendData = $(".sale_return_submit").serialize();
   		var targetUrl = siteURL +"main_pharmacy_sale_return/pharmacy/save";
   		$.ajax({
        url: targetUrl,
        type: "POST",
        data: sendData,
        dataType: "json",
        success: function (response) {
        	console.log(response);
        	if (response.success == true) {
        		$(".sale_return_submit")[0].reset();
        		$("#return_sale_data").empty();
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




$(document).on('keyup','.r_qnty',function(){
	var r_qnty = parseFloat($(this).val());
	var sub_amount = parseFloat($(this).closest('tr').find('.sub_amount').val());
	var qnty = parseFloat($(this).closest('tr').find('.qnty').val());
	var total_less_dsc=parseFloat($('#total_less_dsc').val());
	var total_qnty=0;
	$('form table tbody .qnty').each(function(){
			total_qnty += parseFloat($(this).val());
		});

	var per_qnty_dsc = parseFloat(total_less_dsc / total_qnty);

   

	if (qnty < r_qnty) {
	 	$(this).val(qnty);
	 	var qnty = r_qnty;
	 }

    var less_discount_rqty = r_qnty * per_qnty_dsc;
	var per_price = parseFloat(sub_amount / qnty);
	var r_sub_amount = per_price * r_qnty;
	$(this).closest('tr').find('.r_sub_amount').val(Math.round(r_sub_amount));
	$(this).closest('tr').find('.r_sub_amount_text').text(Math.round(r_sub_amount));
	$(this).closest('tr').find('.less_disc').val(Math.round(less_discount_rqty));
	total();
	});
$(document).on('click','.s_remove',function(){
	total();
	});	
$(document).on('keyup','.pharmacy_tot_charge',function(){
	total();
	});
	
	function total(){
		var charge_qnty = parseFloat($(".pharmacy_tot_charge").val());
		tot_r_amount = 0;
		$('form table tbody .r_sub_amount').each(function(){
			tot_r_amount += parseFloat($(this).val());
		});

		tot_dsc_amount = 0;
		$('form table tbody .less_disc').each(function(){
			tot_dsc_amount += parseFloat($(this).val());
		});
		// console.log('result = '+tot_dsc_amount);
		
		$("#less_amount").val(Math.round(tot_dsc_amount));

		$("#pharmacy_total_return_amount").val(Math.round(tot_r_amount));
		var tot_charge = parseFloat((charge_qnty * tot_r_amount)/100);
		var pharmacy_total_return_amount = parseFloat($("#pharmacy_total_return_amount").val());
		if(tot_charge <= pharmacy_total_return_amount)
		{
		$("#pharmacy_tot_charge_amount").val(Math.round(tot_charge));
		$("#pharmacy_total_return_unit_amount").val(Math.round(tot_r_amount - tot_charge));
		$("input[type = 'submit']").prop('disabled', false);
		}
		else 
		{
			$("#pharmacy_tot_charge").val("0");
			$("input[type = 'submit']").prop('disabled', true);
		}
	}
	
	
	$(document).on('keyup','#pharmacy_return_taka',function(){
		var tot_due=parseFloat($('#tot_due').val());
		var less_amount = parseFloat($("#less_amount").val());
		var tot_returnable_amount=parseFloat($('#pharmacy_total_return_unit_amount').val());
		var return_taka=parseFloat($(this).val());

		if(tot_returnable_amount-less_amount>tot_due){
			var total_payable=Math.round(tot_returnable_amount-less_amount-tot_due);						
			if(return_taka>total_payable){
				$(this).val(total_payable);
			}
		}else{
			$(this).val(0);
		}
	})