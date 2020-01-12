$(document).on('change','.attendance_date',function(){
	var attendance_date=$(this).val();
	var $this=$(this);
	if(attendance_date==""){
		return false;
	}
	var target=siteURL+'attendance/hrm/checkAttendanceByDate';
	var sendData={
			date:attendance_date,
			ci_csrf_token:$('input[name="ci_csrf_token"]').val()
		};
	$.post(target,sendData,function(data){
		if(data>0){
			$this.val('');
			alertMessage('Attendance proccess Complete for that day');
			return false;
		}
	},'json')
})

$(document).on('change','#attendance_date',function(){
	var target=siteURL+'attendance/hrm/take_attendance';
	var sendData={
		ci_csrf_token:$('input[name="ci_csrf_token"]').val(),
		date:$(this).val()
	}
	$.post(target,sendData,function(data){
		$('#attendance_form').html(data);

		$(".datepickerCommon").datepicker({
        	format:'dd/mm/yyyy'
    	});
	})
})

$(document).on('change','.present_status',function(){
	var present_status=$(this).val();
	$(this).closest('tr').find('.leave-options').html('For leave only');
	if(present_status!=3){
		return false;
	}
	var employee_id=$(this).closest('tr').find('.employee_id').val();
	$this=$(this);
	var targetUrl=siteURL+'attendance/hrm/getLeaveTypeOptions/'+employee_id;
	$.get(targetUrl,function(data){
		$this.closest('tr').find('.leave-options').html(data);
	})
})
$(document).on('change','.leave_type',function(){
	var $this=$(this);
	var leave_type=$(this).val();
	if(leave_type==""){
		return false;
	}
	var sendData={
		leave_type_id:leave_type,
		employee_id:$(this).closest('tr').find('.employee_id').val(),
		ci_csrf_token:$('input[name="ci_csrf_token"]').val()
	};
	var targetUrl=siteURL+'attendance/hrm/getEmployeeReamingLeave'
	$.post(targetUrl,sendData,function(data){
		if(data.available==false){
			$this.closest('tr').find('.present_status').val(2).trigger('change');
			alertMessage('<span style="color:red"> Alert!! </span> You have not available '+data.leave_type);
		}
	},'json')
})

$(document).on('click','input[type="reset"]',function(){
	$('.leave-options').html('For leave only');
})