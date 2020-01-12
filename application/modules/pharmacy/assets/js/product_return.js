$(document).on('click','.receive_details',function(e){	
	var target=$(this).attr('href');

	$('#commonModalBody').html('<div class="loader"></div>');
	$('#commonModalTitle').html('Details Receiving Report');
	$('#commonModalFooter').html('');
	$('#commonModal').modal('show');
	$.get(target,function(data){		
		$('#commonModalBody').html(data);
	})

	return false;
})


$(document).on('keyup','.return_qnty',function(){
	var returnable_qnty=parseFloat($(this).closest('tr').find('.returnable_qnty').text());
	if($(this).val() > returnable_qnty){
		$(this).val('');
		$(this).closest('tr').find('.reason').attr('required',false);
	}else{
		$(this).closest('tr').find('.reason').attr('required',true);
	}
});

$(document).on('click','.approved_return',function(){
	var target=$(this).attr('href');
	var return_requst_qnty=parseFloat($(this).closest('tr').find('.return_requst_qnty').text());
	var supplier_name=$(this).closest('tr').find('.supplier_name').text();
	var product_name=$(this).closest('tr').find('.product_name').text();

	$('#commonModalBody').html('<div class="loader"></div>');
	$('#commonModalTitle').html('Return Approved');
	$('#commonModalFooter').html('');
	$('#commonModal').modal('show');
	
	$.get(target,function(data){
		$('#commonModalBody').html(data);
	});

});

function confirMessage()
{
	var message=confirm("Are You Sure To Return Product !!!");
	if(message==true)
	{
           return true;
	}
	else
	{
        return false;
	}
}