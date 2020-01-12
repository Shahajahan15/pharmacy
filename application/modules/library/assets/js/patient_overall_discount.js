$(document).on('click','.overall_discount_submit',function(e){
	e.preventDefault();	
	if(checkValidation($(this))){
			return false;
	}
	$('#confirmAlert').modal('show');

	var $this=$(this);
	var over_all_discount=$(this).closest('tr').find('input[name="over_all_discount"]').val();
	var admission_id=$(this).closest('tr').find('input[name="admission_id"]').val();
	var ci_csrf_token=$('input[name="ci_csrf_token"]').val();
	var target=$(this).attr('href');
	var sendData={
		ci_csrf_token:ci_csrf_token,
		admission_id:admission_id,
		over_all_discount:over_all_discount
	};
	$(document).on('click','#confirm',function(){		
		$.post(target,sendData,function(data){
			if(data.status==1){
				$this.closest('tr').remove();
				$('#confirmAlert').modal('hide');
				showMessages(data.message,'success');
			}
		},'json')
	})
})

$(document).on('change','.over_all_discount',function(){
	

})

function checkValidation($this){
	var over_all_discount=parseInt($this.closest('tr').find('.over_all_discount').val());
	if(over_all_discount==''){
		over_all_discount=0;
	}
	var due_amount=parseInt($this.closest('tr').find('.due-amount').text());
	if(over_all_discount>due_amount){
		$(this).val(due_amount);
		alertMessage('You can create discunt below due('+due_amount+')');
		return true;
	}else{
		return false;
	}
}