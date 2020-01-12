$('#searchButton').click(function (e){
    var empId = $('#employee_id').val();
	var empName = $("#employee_name").children("option").filter(":selected").text();
	if(empName != "Select One"){
		
	}else
	{
		empName = '';
	}
	
    var empDept = $('#employee_department').val();
    var empDesignation = $('#employee_designation').val();
    var targetUrl = "movement_register/hrm/getEmployeeSearchAjax";
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

$(document).on('change','.employee-auto-complete',function(){
	var emp_id=$(this).val();
	var target=siteURL+'common/hrm/getEmployeeById/'+emp_id;

	$.get(target,function(data){
		console.log(data);
	var row='';
	row+='<tr class="success">';

	row+='<td>'+data.emp_name+'<input type="hidden" class="emp_id" name="emp_id[]" value="'+data.id+'" /> </td>';
	row+='<td>'+data.mobile+'<input type="hidden" class="emp_mobile" name="emp_mobile[]" value="'+data.mobile+'" /> </td>';
	row+='<td>'+data.designation_name+'<input type="hidden" name="emp_designation[]" class="emp_designation" value="'+data.designation_name+'" /> </td>';
	row+='<td>'+data.department_name+'<input type="hidden" name="emp_dept[]" value="'+data.department_name+'" /></td>';
    row+='<td><button type="button" class="remove s_remove"><i class="fa fa-times" aria-hidden="true"></i></button></td>';
	
	row+='</tr>'

	$('#movement_register_data').append(row);
	},'json')
})

