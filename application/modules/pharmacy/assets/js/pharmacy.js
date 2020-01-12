$(document).on('keyup','.medicine-sale-no-search',function(){
	var sale_no = $(this).val();
	var withoutSpace = sale_no.replace(/ /g,"");

	if(withoutSpace.length==0){
		$(this).closest('.form-group').find('.autocomplete_box').html('');
		return false;
	}
	$this=$(this);
	var sendData={sale_no:sale_no};
	var targetUrl=siteURL+'common/report/getMedicineSaleInfoBySaleNo';

	$.get(targetUrl,sendData,function(data){
		$this.closest('.form-group').find('.autocomplete_box').html(data);
	})

});

$(document).on('keyup','.sub-medicine-sale-no-search',function(){
	var sale_no = $(this).val();
	var type = $(this).attr('p_type');
	var withoutSpace = sale_no.replace(/ /g,"");

	if(withoutSpace.length==0){
		$(this).closest('.form-group').find('.autocomplete_box').html('');
		return false;
	}
	$this=$(this);
	var sendData={sale_no:sale_no,type:type};
	var targetUrl=siteURL+'common/report/getMedicineSaleInfoBySaleNo';

	$.get(targetUrl,sendData,function(data){
		$this.closest('.form-group').find('.autocomplete_box').html(data);
	})

});


$(document).ready( function () {
    $('#myTable1').DataTable();
} );