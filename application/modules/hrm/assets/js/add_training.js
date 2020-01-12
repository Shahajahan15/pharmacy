$('#EmpSearchButton').click(function (e){
    var empId = $('#employee_id').val();
	var empName = $("#employee_name").val();
	var empDept = $('#employee_department').val();
    var empDesignation = $('#employee_designation').val();
    var targetUrl = "add_training/hrm/getEmployeeSearchAjax";
    var ci_csrf_token 	= $("input[name='ci_csrf_token']").val();
	
        var sendData  = {
		  ci_csrf_token:ci_csrf_token,
	      empId:empId,
		  empName:empName,
	      empDept:empDept,
		  empDesignation:empDesignation
		 
		  
        };
        
      setInnerHTMLAjax(siteURL+targetUrl, sendData, "#show_employee_search_result"); 
		
      
    if(!$('#show_employee_search_result').html()){
		$('td#infoMessage p').show();
		
		
	}else{ $('td#infoMessage p').hide();}
    
    
    
});

 // Add row how many need click on '+' button
 function addRow(event){

	 var _clone = $(event).closest("span").clone();
		_clone.find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
		_clone.find("a").removeClass("btn-primary").addClass("btn-danger");
		_clone.find("a").removeAttr("onclick").attr("onclick", "removeRow(this);");	
	 //$('.add_training_div').append(__clone);
	 
	$("#add_training_div").append("<span class='row'>"+_clone.html()+"<span>");	
 }
 
// Remove a specific row click on '-' button  
function removeRow(event){
	$(event).closest('span.row').remove();	
}
