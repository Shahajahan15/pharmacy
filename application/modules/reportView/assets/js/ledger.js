// JavaScript Document

//============ for Account Ledger ================
$("#ac_project_name").on("change", function(){
	var projectId = $(this).val(); //alert(lang('account_selete_one'));
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	if(projectId == "" || projectId == 0){
		return;
	}		
	var targetUrl = "setup/account/getGroupListAjax";
	var sendData  = { projectId: projectId, ci_csrf_token:ci_csrf_token };
	var firstOP   = "<option value=''>Select One</option>";
	var isJSON 	  = 1;
	//alert(projectId);
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#ac_group_name",firstOP,isJSON);	
});

$("#ac_group_name").on("change", function(){
	var groupId = $(this).val(); //alert(lang('account_selete_one'));
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	if(groupId == "" || groupId == 0){
		return;
	}		
	var targetUrl = "setup/account/getCatagoryListAjax";
	var sendData  = { groupId: groupId, ci_csrf_token:ci_csrf_token };
	var firstOP   = "<option value=''>Select One</option>";
	var isJSON 	  = 1;
	//alert(projectId);
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#ac_category_name",firstOP,isJSON);	
	
	//=====  Load Account Head by Account Group =========
	var targetUrl = siteURL+"voucher/account/getHeadListAjax";
	getAccountHeadList(targetUrl,sendData,"#ac_head_name",firstOP,isJSON);
});

$("#ac_category_name").on("change", function(){
	var categoryId = $(this).val(); //alert(lang('account_selete_one'));
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	if(categoryId == "" || categoryId == 0){
		return;
	}		
	var targetUrl = "setup/account/getSubCatagoryListAjax";
	var sendData  = { categoryId: categoryId, ci_csrf_token:ci_csrf_token };
	var firstOP   = "<option value=''>Select One</option>";
	var isJSON 	  = 1;
	//alert(projectId);
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#ac_subcategory_name",firstOP,isJSON);
	
	//=====  Load Account Head by Category =========
	var targetUrl = siteURL+"voucher/account/getHeadListAjax";
	getAccountHeadList(targetUrl,sendData,"#ac_head_name",firstOP,isJSON);
		
});

$("#ac_subcategory_name").on("change", function(){
	var subcategoryId = $(this).val(); //alert(lang('account_selete_one'));
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	if(subcategoryId == "" || subcategoryId == 0){
		return;
	}		
	var targetUrl = "setup/account/getSubChildListAjax";
	var sendData  = { subcategoryId: subcategoryId, ci_csrf_token:ci_csrf_token };
	var firstOP   = "<option value=''>Select One</option>";
	var isJSON 	  = 1;
	//alert(projectId);
	setInnerHTMLAjax(siteURL+targetUrl, sendData, "#ac_sub_child_name",firstOP,isJSON);	
	//=====  Load Account Head by Subcategory =========
	var targetUrl = siteURL+"voucher/account/getHeadListAjax";
	getAccountHeadList(targetUrl,sendData,"#ac_head_name",firstOP,isJSON);
});


$("#ac_sub_child_name").on("change", function(){
	var subchildId = $(this).val(); //alert(lang('account_selete_one'));
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	if(subchildId == "" || subchildId == 0){
		return;
	}
			
	var targetUrl = siteURL+"voucher/account/getHeadListAjax";
	var sendData  = { subchildId: subchildId, ci_csrf_token:ci_csrf_token };
	var firstOP   = "<option value=''>Select One</option>";
	var isJSON 	  = 1;
	//alert(subchildId);
	getAccountHeadList(targetUrl,sendData,"#ac_head_name",firstOP,isJSON);
});

function getAccountHeadList(targetUrl,sendData,fieldId,firstOP,isJSON){
	setInnerHTMLAjax(targetUrl, sendData, fieldId, firstOP, isJSON);	
}