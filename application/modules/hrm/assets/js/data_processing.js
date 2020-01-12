/*                Roster     Processing                */

$(document).on("click","#rd_processing",function(e){
    e.preventDefault();
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();
    var emp_id = $(".emp_id").val();
    var emp_id = (emp_id) ? emp_id : 0;
    if (start_date == '') {
        alert("Please, enter start date..");
        return false;
    }
    if (end_date == '') {
        alert("Please, enter end date..");
        return false;
    }

     $("input[type = 'submit']").prop('disabled', true);
    var sendData = {ci_csrf_token: ci_csrf_token, start_date: start_date,end_date:end_date, emp_id:emp_id};
    var targetUrl = siteURL +"data_processing/hrm/roster_data_processing_save";
     $.ajax({
        url: targetUrl,
        type: "POST",
        data: sendData,
        dataType: "json",
        success: function (response) {
            console.log(response);
             if (response.success === true) {
                if (emp_id) {
                    $('#commonModal').modal('hide');
                }
                window.location.href = siteURL+"data_processing/hrm/roster_data_processing";
                showMessages(response.message, 'success');
                resetRDPForm();
            } else {
                showMessages(response.message, 'error');
            }
        }, error: function (jqXHR) {
            showMessages('Unknown Error!!!', 'error');
        }
    });
});

function resetRDPForm() {
    $("#start_date").val('');
    $("#end_date").val('');
    $("input[type = 'submit']").prop('disabled', false);

}

$(document).on('click','.roster-re-process', function(){
    var id = $(this).attr('id');
    $('#commonModalTitle').html("<b style='text-align:center'>Roster Policy Re-Processing.....</b>");
    $('#commonModalFooter').remove();
    $('#commonModalBody').html('<div class="loader"></div>');
    $('#commonModal').modal('show');
    
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    var sendData  = {process_id:id,ci_csrf_token:ci_csrf_token}
    var targetUrl = siteURL +"data_processing/hrm/roster_data_re_process";
    $.post( targetUrl, sendData).done( function( data ) { 
        $('#commonModalBody').html(data);
    });
});

$(document).on('click','#roster-re-add', function(e){
    e.preventDefault();
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();
    if (start_date == '') {
        alert("Please, enter start date..");
        return false;
    }
    if (end_date == '') {
        alert("Please, enter end date..");
        return false;
    }
    $(this).prop('disabled', true);
});