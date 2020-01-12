$(document).on('click','.confirm_return_restore',function(){
	var restore_row=$(this).closest('tr').html()
    
	$('#commonModalTitle').html('Return Replace product');
	$('#commonModalFooter').html('');
	$('#commonModalBody').html('<div class="loader"></div>');
	$('#commonModal').modal('show');

	var target=$(this).attr('href');
	$.get(target,function(data){

		$('#commonModalBody').html(data);
	})
})

$(document).on('keyup change','.replace_qntity',function(){
	var replace_qntity=$(this).val();
	var receivable_replace_qnty=parseFloat($('.receivable_replace_qnty').text());
	if(receivable_replace_qnty<replace_qntity){
		$(this).val('');
	}
})