
$(document).on('change','#patient_type_id',function(){
	var id=$(this).val();
	var targetUrl=siteURL+'discount/library/getSubTypeByTypeId/'+id;
	$.get(targetUrl,function(data){
		var data=$.parseJSON(data);
		$('#patient_sub_type_id').html(data);
	})
})