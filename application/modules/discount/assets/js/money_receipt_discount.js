
/*   submit emergency ticket    */
$(document).ready(function(){

	$('form#mr-discount-form').validator().on('submit', function(e){
		if (!e.isDefaultPrevented()) {
			e.preventDefault();
			$("input[type = 'submit']").prop('disabled', true);
			var sendData = $(this).serialize();
				var targetUrl = siteURL +"money_receipt_discount/discount/show";
	   		$.ajax({
	        	url: targetUrl,
	        	type: "POST",
	        	data: sendData,
	        	dataType: "json",
	        	success: function (response) {
	        		console.log("result=="+response);
		        	if (response.success == true) {
		        		$('#discount-money-recept-show').append(response.page);
		        		$('#search-money-receipt-btn').prop('disabled', true);
		            } else {
		                showMessages(response.message, 'error');
		                $('#search-money-receipt-btn').prop('disabled', false);
		            }
	        }, error: function (jqXHR) {
	            showMessages('Unknown Error!!!', 'error');
	        }
	    });
		}
	});

});

$(document).on('click','.money_recept_discount',function(){
		$(".money_recept_discount").prop('disabled', true);
		var ci_csrf_token = $("input[name='ci_csrf_token']").val();
		var mr_discount = $(".mr_discount").val();
		var mr_no = $(".mr_no").val();
		var service_id = $(".service_id").val();
		var net_bill = $(".net_bill").val();
		var sendData = {mr_discount:mr_discount,mr_no:mr_no,ci_csrf_token:ci_csrf_token, service_id:service_id,net_bill:net_bill};
		var targetUrl = siteURL +"money_receipt_discount/discount/save";
	   		$.ajax({
	        	url: targetUrl,
	        	type: "POST",
	        	data: sendData,
	        	dataType: "json",
	        	success: function (response) {
	        		//console.log("result=="+response);
		        	if (response.success == true) {
		        		$("#mr-discount-form")[0].reset();
		        		$('#searched-money-receipts').empty();
		        		showMessages(response.message, 'success');
		            } else {
		                showMessages(response.message, 'error');
		            }
	        }, error: function (jqXHR) {
	            showMessages('Unknown Error!!!', 'error');
	        }
	    });
	});

$(document).on('click','.approve-now',function(){
	var r = confirm("Are You Confirm!");
	if (r == false) {
		return false;
	}
	var id=$(this).attr('id');
	var $this=$(this);
	var mr_approve_discount = parseFloat($(this).closest("tr").find(".mr_approve_discount").val());
	var targetUrl=siteURL+'money_receipt_discount/discount/approved/'+id+"/"+mr_approve_discount;
	$.get(targetUrl,function(data){		
		if(data.status==true){
			$this.closest('tr').remove();
			showMessages(data.message,'success');			
		}else{
			showMessages(data.message,'error');
		}
	},'json')
});

$(document).on('click','.cancel-now',function(){
	var r = confirm("Are You Confirm!");
	if (r == false) {
		return false;
	}
	var id=$(this).attr('id');
	var $this=$(this);
	var targetUrl=siteURL+'money_receipt_discount/discount/cancel/'+id;
	$.get(targetUrl,function(data){		
		if(data.status==true){
			$this.closest('tr').remove();
			showMessages(data.message,'success');			
		}else{
			showMessages(data.message,'error');
		}
	},'json')
});

$(document).on("click",".collection-show",function(){
	$('#commonModalTitle').html("<b style='text-align:center'>Money Receipt Wise Discount</b>");
	$('#commonModalFooter').remove();
	$('#commonModalBody').html('<div class="loader"></div>');
	$('#commonModal').modal('show');
	var id=$(this).attr('id');
	$(this).html('<i class="fa fa-spinner fa-spin" style="color:red;"></i>Collection');
	var $this=$(this);
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	var sendData  = {ci_csrf_token:ci_csrf_token,id:id};
	var targetUrl = siteURL +"money_receipt_discount/discount/collection_show";
   	$.post( targetUrl, sendData).done( function( data ) { 
   		console.log(data);
   		if (data == false) {
   			window.location = siteURL+"money_receipt_discount/discount/collection_list";
   		}
   		$('#commonModalBody').html(data);
    	//$('.ref-legend').hide();
    	//$('.box').css({'border':'0px'});
	}); 
});

$(document).on("click",".mr-discount-collection",function(){
	var id=$(".master_id").val();
	//alert(id);return;
	if (!id) {
		return false;
	}
	//$this = $(this);
	var _this = $("table.pending_list [id="+id+"]").closest('tr');
	$(this).html('<i class="fa fa-spinner fa-spin" style="color:red;"></i>');
	$("span.mr-discount-collection").attr("disabled","disabled");
	
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	var sendData  = {ci_csrf_token:ci_csrf_token,id:id};
	var targetUrl = siteURL +"money_receipt_discount/discount/collection";
   	$.post( targetUrl, sendData).done( function( data ) { 
   		//console.log(data);
    	$('#commonModal').modal('hide');
    	$(_this).remove();
	},'json'); 
});

$(document).on("keyup", ".mr_discount", function(){
	var $mr_discount = parseFloat($(this).val());
	var $net_bill = parseFloat($(".net_bill").val());
	if ($mr_discount > $net_bill) {
		$(this).val("0");
	}
});

$(document).on("keyup", ".mr_approve_discount", function(){
	var $this=$(this);
	var $mr_approve_discount = parseFloat($(this).val());
	var $net_bill = parseFloat($(this).closest("tr").find(".net_bill").val());
	if ($mr_approve_discount > $net_bill) {
		$(this).val("0");
	}
});
