$(document).on('click','.products_issue',function(){
	var id=$(this).attr('mst_id');
	targetUrl=siteURL+'indoor_requisition_issue/pharmacy/getDtlsRequisition/'+id;

	$('#commonModalTitle').html('Requisition Issue');
	$('#commonModalBody').html('<div class="loader"></div>');
	$('#commonModalFooter').html('');
	$('#commonModal').modal('show');

	$.get(targetUrl,function(data){
		$('#commonModalBody').html(data);
	})
});



$(document).on('click','.products_issue_com',function(){
	var id=$(this).attr('mst_id');
	targetUrl=siteURL+'indoor_requisition_issue/pharmacy/getCompleteDtlsRequisition/'+id;

	$('#commonModalTitle').html('Complete Requisition Issue');
	$('#commonModalBody').html('<div class="loader"></div>');
	$('#commonModalFooter').html('');
	$('#commonModal').modal('show');

	$.get(targetUrl,function(data){
		$('#commonModalBody').html(data);
	})
});

$(document).on('keyup','.issue_qnty_for_dept',function(){
	var stock=$(this).closest('tr').find('.main_stock').text();
	var issue_needed=$(this).closest('tr').find('.issue_need').text();
	if($(this).val()>parseInt(stock) || $(this).val()>parseInt(issue_needed)){
		$(this).val('');
	}

})

function confirmMassege()
{
	var message=confirm("Are You Sure To Issue Indor Requisition !!!");
	if(message==true)
	{
           return true;
	}
	else
	{
        return false;
	}
}