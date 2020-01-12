// JavaScript Document

//============ for Zone Area ================
$("#library_zone_division_name").on("change", function(){
	var divisionId = $(this).val(); 
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	if(divisionId == "" || divisionId == 0){
		return;
	}		
	var targetUrl = "area/library/getDistrictListAjax";
	var sendData  = { divisionId: divisionId, ci_csrf_token:ci_csrf_token };
	var FirstOP   = "<option value=''>Select One</option>";
	var isJSON 	  = 1;
	//alert(divisionId);
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#library_zone_district_name",FirstOP,isJSON);	
});

//============ for Zone TRT Area ================
$("#library_zone_district_name").on("change", function(){
	var districtId = $(this).val(); 
	var divisionId = $("#library_zone_division_name").val();
	
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	if(divisionId == "" || divisionId == 0){
		return;
	}		
	var targetUrl = "trtarea/library/getAreaListAjax";
	var sendData  = { divisionId: divisionId, districtId: districtId, ci_csrf_token:ci_csrf_token };
	var FirstOP   = "<option value=''>Select One</option>";
	var isJSON 	  = 1;
	//alert(divisionId+" "+districtId);
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#library_zone_area_name",FirstOP,isJSON);	
});



function divisionCheck(){

	var divisionName = $.trim($('#library_zone_division_name').val());	
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	
	if(divisionName != ""){
		
	$("#checkName").show();
	var targetUrl = "division/library/checkDivisionNameAjax";
	
	var sendData  = { 
						divisionName:divisionName,					
						ci_csrf_token:ci_csrf_token 
					};	
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#checkName");
	
	}
	else
	{
		$("#checkName").hide();
		
	}
}


$(document).on('blur','#library_zone_district_name',function(e){	
	
	var districtName = $.trim($('#library_zone_district_name').val());	
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	
	if(districtName != ""){
	var targetUrl = siteURL+"district/library/checkDistrictNameAjax";
	
	var sendData  = { 
						districtName:districtName,					
						ci_csrf_token:ci_csrf_token 
					};	
	//setInnerHTMLAjax(siteURL+targetUrl, sendData, "#checkName");
	$.post(targetUrl,sendData,function(data){
		if(data.status==1){
			alertMessage(data.message);
			$('#library_zone_district_name').val('');
		}
		
	},'json')
	
	}

})
// $(document).on('blur','#library_zone_division_name',function(e){	
	
// 	var divisionName = $.trim($('#library_zone_division_name').val());	
// 	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	
// 	if(divisionName != ""){
// 	var targetUrl = siteURL+"division/library/checkDivisionNameAjax";
	
// 	var sendData  = { 
// 						divisionName:divisionName,					
// 						ci_csrf_token:ci_csrf_token 
// 					};	
// 	//setInnerHTMLAjax(siteURL+targetUrl, sendData, "#checkName");
// 	$.post(targetUrl,sendData,function(data){
// 		if(data.status==1){
// 			alertMessage(data.message);
// 			$('#library_zone_division_name').val('');
// 		}
		
// 	},'json')
	
// 	}

// })
$(document).on('blur','#library_zone_area_name',function(e){	

	var districtName = $.trim($('#library_zone_district_name').val());	
	var thanaName = $.trim($('#library_zone_area_name').val());	
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	
	if(thanaName != ""){
	var targetUrl = siteURL+"area/library/checkThanaNameAjax";
	
	var sendData  = { 
						districtName:districtName,				
						thanaName:thanaName,	
						ci_csrf_token:ci_csrf_token 
					};	
	//setInnerHTMLAjax(siteURL+targetUrl, sendData, "#checkName");
	$.post(targetUrl,sendData,function(data){
		if(data.status==1){
			alertMessage(data.message);
			$('#').val('library_zone_area_name');
			$('#').val('library_zone_district_name');

		}
		
	},'json')
	
	}

})


// function thanaCheck(){
// 	//alert();
// 	var thanaName = $.trim($('#library_zone_area_name').val());
// 	var districtName = $.trim($('#library_zone_district_name').val());
	
// 	if(thanaName != ""){		
// 	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
// 	var targetUrl = "area/library/checkThanaNameAjax";
	
// 	var sendData  = { 
// 						districtName:districtName,
// 						thanaName:thanaName,						
// 						ci_csrf_token:ci_csrf_token 
// 					};	
					
// 	$.post(siteURL+targetUrl, sendData).success(function(response){
			
// 			if(response != false){				
// 				alert('Thana Name Already Created With Same District !');
// 				$('#library_zone_area_name').val('');
// 				$('#library_zone_district_name').val('');				
// 			}
// 		});
	
// 	}
// 	else
// 	{
// 		alert('Select * Marks Fields !');
		
// 	}
// }


// function postOfficeCheck(){
// 	//alert();
// 	var districtName = $.trim($('#library_zone_district_name').val());
// 	var thanaName = $.trim($('#library_zone_area_name').val());	
// 	var postOfficeName = $.trim($('#library_zone_trt_name').val());
	
// 	if(postOfficeName != ""){		
// 	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
// 	var targetUrl = "trtarea/library/checkPostNameAjax";
	
// 	var sendData  = { 
// 						districtName:districtName,
// 						thanaName:thanaName,
// 						postOfficeName:postOfficeName,						
// 						ci_csrf_token:ci_csrf_token 
// 					};	
					
// 	$.post(siteURL+targetUrl, sendData).success(function(response){
			
// 			if(response != false){				
// 				alert('Post Office Name Already Created With Same District And Thana !');
// 				$('#library_zone_trt_name').val('');
// 				$('#library_zone_area_name').val('');
// 				$('#library_zone_district_name').val('');				
// 			}
// 		});
	
// 	}
// 	else
// 	{
// 		alert('Select * Marks Fields !');
		
// 	}
// }

