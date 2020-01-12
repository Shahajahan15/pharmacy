
$(document).on('change','#requisition_approve_product',function(){

	if($(this).val()==""){
		return false;
	}
	var approve_dtls_id=$(this).val();
	targetUrl=siteURL+'purchase_order/pharmacy/getProductInfoByOrderId/'+approve_dtls_id;

	$.get(targetUrl,function(data){
		console.log(data.id);
		var row='';

		row+='<tr class="success">';

		row+='<td>'+data.category_name+'&nbsp; >> &nbsp;'+data.product_name+'<input type="hidden" name="product_id[]" class="product_id" value="'+data.product_id+'" />';
		row+='<input type="hidden" name="product_requisition_id[]" value="'+data.id+'" /> </td>';
		row+='<td>'+data.approve_qnty+'</td>';
		row+='<td>'+data.purchase_order_qnty+'</td>';
		row+='<td class="order-pending-qnty">'+(data.approve_qnty-data.purchase_order_qnty)+'</td>';
		row+='<td>'+(data.purchase_price)+'</td>';
		row+='<td><input type="text" name="order_qnty[]" class="form-control order_qnty on-focus-selected" value="'+(data.approve_qnty-data.purchase_order_qnty)+'" required=""/></td>';
		row+='<td><input type="text" name="order_unit_price[]" class="form-control order_unit_price on-focus-selected" value="'+data.purchase_price+'" required=""/></td>';
		row+='<td><input type="text" name="order_total_price[]" value="'+((data.approve_qnty-data.purchase_order_qnty)*data.purchase_price).toFixed(2)+'" class="form-control order_total_price" required=""/></td>';

		row+='<td><button type="button" class="remove"><i class="fa fa-times" aria-hidden="true"></i></button></td>';
		row+='</tr>'

		$('#work_order_submit-data').append(row);

	},'json');

})


$(document).on('keyup change','.order_qnty',function(){
	var order_qnty=$(this).val();
	var order_pending_qnty=$(this).closest('tr').find('.order-pending-qnty').text();
	var order_unit_price=$(this).closest('tr').find('.order_unit_price').val();
	order_pending_qnty=parseInt(order_pending_qnty);

	if(order_pending_qnty < order_qnty){
		$(this).val('');
	}

	if(order_unit_price!=''){
		$(this).closest('tr').find('.order_total_price').val(order_unit_price*order_qnty);
	}
})


$(document).on('change keyup','.order_unit_price',function(){

	var order_unit_price=$(this).val();
	var order_qnty=$(this).closest('tr').find('.order_qnty').val();
	$(this).closest('tr').find('.order_total_price').val((order_unit_price*order_qnty).toFixed(2));
	
})

$(document).on('change keyup','.order_total_price',function(){

	var order_total_price=$(this).val();
	var order_qnty=$(this).closest('tr').find('.order_qnty').val();

	$(this).closest('tr').find('.order_unit_price').val((order_total_price/order_qnty).toFixed(2));
	
})









//purchase order Receive

$(document).on('keyup change','.receive_qnty',function(){

	var receive_qnty=parseFloat($(this).val());	
	var receivable_order_qnty=parseFloat($(this).closest('tr').find('.receivable_order_qnty').text());
	if(receive_qnty>receivable_order_qnty){
		$(this).val('');
		$(this).closest('tr').find('.receive_total_price').val('');
		$(this).closest('tr').find('.receive_total_qnty').val('');
	}
	var receive_free_qnty=parseFloat($(this).closest('tr').find('.receive_free_qnty').val());
	var receive_unit_price=parseFloat($(this).closest('tr').find('.receive_unit_price').val());

	var receive_total_qnty=parseFloat(receive_qnty + receive_free_qnty);
	$(this).closest('tr').find('.receive_total_qnty').val(receive_total_qnty);
	var receive_total_price=parseFloat($(this).closest('tr').find('.receive_total_price').val(receive_qnty*receive_unit_price));


})

$(document).on('keyup change','.receive_free_qnty',function(){
	
	var receive_free_qnty=parseFloat($(this).val());	
	
	var receive_qnty=parseFloat($(this).closest('tr').find('.receive_qnty').val());
	var receive_unit_price=parseFloat($(this).closest('tr').find('.receive_unit_price').val());


	

	var receive_total_qnty=parseFloat(receive_qnty + receive_free_qnty);
	$(this).closest('tr').find('.receive_total_qnty').val(receive_total_qnty);

	


})



$(document).on('keyup','.return_replace_qnty',function(){
	var return_replace_qnty=$(this).val();
	var return_pending_quntity=parseFloat($(this).closest('tr').find('.return_pending_quntity').text());

	if(return_replace_qnty>return_pending_quntity || return_replace_qnty<0){
		$(this).val('');
	}

})








/*Supplier Select 2*/
$('.pharmacy_supplier_auto').select2({
       ajax: {
            url: siteURL + "common/report/getPharmacySupplierByKey",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                };
            },
            processResults: function (data, params) {
               
                return {
                    results: data.items
                };
            },
            cache: true
        },
        
        allowClear: true,
        placeholder: 'Supplier Name/Mobile',
        minimumInputLength: 1
    });

/* finish */


$(document).on('click','.supplier-list',function(){
	var target=siteURL + "common/report/pharmacy_supplier_list";
	$.get(target,function(data){
		$('#commonModalBody').html(data);
		$('#commonModal').modal('show');
	})
})

$(document).on('click','.bf-edit-action',function(e){
	e.preventDefault();
})

$(document).on('click','.pagination ul li a',function(e){
	e.preventDefault();

})


$(document).on('click','.supplier-from-list',function(){
	var id =$(this).attr('id');
	var name =$(this).text();


	$('.pharmacy_supplier_auto').html($('<option>'))
                        .append($('<option>', { 
                value:id,
                text :name,
                selected: true
            })).trigger('change');
    $('#commonModal').modal('hide');
})


$(document).on('click','.add-new-supplier',function(){
	var target=siteURL + "supplier/pharmacy/create";

		$('#commonModalTitle').html('Create New Supplier');
		$('#commonModalBody').html('<div class="loader"></div>');
		$('#commonModal').modal('show');
		$('#commonModalFooter').remove();

	$.get(target,function(data){
		$('#commonModalBody').html(data);		
	})
})

$(document).on('submit','.pharmacy-supplier-create-form',function(e){
	e.preventDefault();
	var supplier_name=$('#pharmacy_supplier_name').val();
	var sendData=$(this).serialize()+'&save=1';
	var target=$(this).attr('action');	
	$.post(target,sendData,function(res){

		$('.pharmacy_supplier_auto').html($('<option>'))
                        .append($('<option>', { 
                value:res.inserted_id,
                text :supplier_name,
                selected: true
            })).trigger('change');

    	$('#commonModal').modal('hide');
	},'json')
})

$(document).on('submit','.purchase-order-form',function(e){	
	var products=$('#work_order_submit-data').find('.product_id').length;
	if(products <= 0){
		e.preventDefault();
		alertMessage('No product Added into the list');
	}
})


function confirmMassege()
{
	var message=confirm("Are You Sure To Order Placed !!!");
	if(message==true)
	{
           return true;
	}
	else
	{
        return false;
	}
}
function confirmMassegeRecieve()
{
	var message=confirm("Are You Sure To Recive All Products !!!");
	if(message==true)
	{
           return true;
	}
	else
	{
        return false;
	}
}