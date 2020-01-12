// JavaScript Document
/*function getDropdown(PostUrl,dropdown,conditionFName,conditionFValue){	
	$.ajax({
		type: 'POST',
		url: PostUrl,
		if(conditionFName!=""){
		data: conditionFName+"="+conditionFValue,
		}
		success: function(option){
				$("#"+dropdown).html(option);
		}//Success
	});// ajax		
	
}*/

//============ for Category ================
$("#ac_category_project_name").on("change", function(){
	var projectId = $(this).val(); //alert(lang('account_selete_one'));
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	if(projectId == "" || projectId == 0){
		return;
	}		
	var targetUrl = "setup/account/getGroupListAjax";
	var sendData  = { projectId: projectId, ci_csrf_token:ci_csrf_token };
	var FirstOP   = "<option value=''>Select One</option>";
	var isJSON 	  = 1;
	//alert(projectId);
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#ac_category_group_name",FirstOP,isJSON);	
});

//============ for Sub-Category ================
$("#ac_subcategory_project_name").on("change", function(){
	var projectId = $(this).val(); //alert(lang('account_selete_one'));
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	if(projectId == "" || projectId == 0){
		return;
	}		
	var targetUrl = "setup/account/getGroupListAjax";
	var sendData  = { projectId: projectId, ci_csrf_token:ci_csrf_token };
	var FirstOP   = "<option value=''>Select One</option>";
	var isJSON 	  = 1;
	//alert(projectId);
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#ac_subcategory_group_name",FirstOP,isJSON);	
});

$("#ac_subcategory_group_name").on("change", function(){
	var groupId = $(this).val(); //alert(lang('account_selete_one'));
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	if(groupId == "" || groupId == 0){
		return;
	}		
	var targetUrl = "setup/account/getCatagoryListAjax";
	var sendData  = { groupId: groupId, ci_csrf_token:ci_csrf_token };
	var FirstOP   = "<option value=''>Select One</option>";
	var isJSON 	  = 1;
	//alert(projectId);
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#ac_subcategory_category_name",FirstOP,isJSON);	
});

//============ for Sub-Child ================
$("#ac_subchild_project_name").on("change", function(){
	var projectId = $(this).val(); //alert(lang('account_selete_one'));
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	if(projectId == "" || projectId == 0){
		return;
	}		
	var targetUrl = "setup/account/getGroupListAjax";
	var sendData  = { projectId: projectId, ci_csrf_token:ci_csrf_token };
	var FirstOP   = "<option value=''>Select One</option>";
	var isJSON 	  = 1;
	//alert(projectId);
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#ac_subchild_group_name",FirstOP,isJSON);	
});

$("#ac_subchild_group_name").on("change", function(){
	var groupId = $(this).val(); //alert(lang('account_selete_one'));
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	if(groupId == "" || groupId == 0){
		return;
	}		
	var targetUrl = "setup/account/getCatagoryListAjax";
	var sendData  = { groupId: groupId, ci_csrf_token:ci_csrf_token };
	var FirstOP   = "<option value=''>Select One</option>";
	var isJSON 	  = 1;
	//alert(projectId);
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#ac_subchild_category_name",FirstOP,isJSON);	
});

$("#ac_subchild_category_name").on("change", function(){
	var categoryId = $(this).val(); //alert(lang('account_selete_one'));
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	if(categoryId == "" || categoryId == 0){
		return;
	}
		
	var targetUrl = "setup/account/getSubCatagoryListAjax";
	var sendData  = { categoryId: categoryId, ci_csrf_token:ci_csrf_token };
	var FirstOP   = "<option value=''>Select One</option>";
	var isJSON 	  = 1;
	//alert(projectId);
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#ac_subchild_subcategory_name",FirstOP,isJSON);	
});

//============ for Account Head ================
$("#ac_head_project_name").on("change", function(){
	var projectId = $(this).val(); //alert(lang('account_selete_one'));
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	if(projectId == "" || projectId == 0){
		return;
	}		
	var targetUrl = "setup/account/getGroupListAjax";
	var sendData  = { projectId: projectId, ci_csrf_token:ci_csrf_token };
	var FirstOP   = "<option value=''>Select One</option>";
	var isJSON 	  = 1;
	//alert(projectId);
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#ac_head_group_name",FirstOP,isJSON);	
});

$("#ac_head_group_name").on("change", function(){
	var groupId = $(this).val(); //alert(lang('account_selete_one'));
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	if(groupId == "" || groupId == 0){
		return;
	}
		
	var targetUrl = "setup/account/getCatagoryListAjax";
	var sendData  = { groupId: groupId, ci_csrf_token:ci_csrf_token };
	var FirstOP   = "<option value='0'>Select One</option>";
	var isJSON 	  = 1;
	//alert(projectId);
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#ac_head_category_name",FirstOP,isJSON);	
});

$("#ac_head_category_name").on("change", function(){
	var categoryId = $(this).val(); //alert(lang('account_selete_one'));
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	if(categoryId == "" || categoryId == 0){
		return;
	}		
	var targetUrl = "setup/account/getSubCatagoryListAjax";
	var sendData  = { categoryId: categoryId, ci_csrf_token:ci_csrf_token };
	var FirstOP   = "<option value='0'>Select One</option>";
	var isJSON 	  = 1;
	//alert(projectId);
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#ac_head_subcategory_name",FirstOP,isJSON);	
});
$("#ac_head_subcategory_name").on("change", function(){
	var subcategoryId = $(this).val(); //alert(lang('account_selete_one'));
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	if(subcategoryId == "" || subcategoryId == 0){
		return;
	}		
	var targetUrl = "setup/account/getSubChildListAjax";
	var sendData  = { subcategoryId: subcategoryId, ci_csrf_token:ci_csrf_token };
	var FirstOP   = "<option value='0'>Select One</option>";
	var isJSON 	  = 1;
	//alert(projectId);
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#ac_head_sub_child_name",FirstOP,isJSON);	
});