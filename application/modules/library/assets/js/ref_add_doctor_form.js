/*global $,siteURL*/

$(document).ready(function(){
   
   $('#add-new-ref-doctor').on('click', function(e){
        e.preventDefault();
        
        var urlPath = $(this).attr('data-alt-path');
        
        $('#commonModal').modal('show');
        
        $.ajax({
            type: 'get',
            url: urlPath,
            dataType: 'html',
            timeout: 5000
        })
        .done(function(resp){
            
            var refBody = $(resp);
            refBody.find('form').on('submit', newReferenceDoctorAdd);
            refBody.find('form .cancel-form').on('click', function(e){
                e.preventDefault();
                $('#commonModal').modal('hide'); 
            });
            
            $('#commonModalTitle').text('New Reference Doctor');
            $('#commonModalBody').html(refBody);
            $('#commonModalFooter');
            
        })
        .fail(function(x, t, s){
            $('#commonModalBody').html('<p class="text-danger">Some Error Happened in the server</p>');
        });
        
    });
    
});

function newReferenceDoctorAdd(evt) {
    evt.preventDefault();
    
    var formRefDoc = $(this),
        btn = formRefDoc.find('[type="submit"]'),
        formData = formRefDoc.serialize();
    
    formRefDoc.find('.form-group').removeClass('has-error');
    formRefDoc.find('.help-inline').text('');
    
    btn.prop('disabled', true);
    
    $.ajax({
        type: 'post',
        url: siteURL + 'sales_reference/library/ref_new_doctor_save',
        data: formData,
        dataType: 'json',
        timeout: 5000
    })
    .always(function(){
        btn.prop('disabled', false);
    })
    .done(function(resp){
        
        if ($('#reference_doctor_id').length > 0) {
        
            $('#reference_doctor_id').html($('<option>'))
                        .append($('<option>', { 
                value: resp.data.id,
                text : resp.data.ref_name,
                selected: true
            })).trigger('change');
            
        }
        
        if ($('#reference_name').length > 0) {
            
            $('#reference_name').val(
                resp.data.ref_name + ' - ' + resp.data.ref_quali
            );
            
        }
        
        $('#commonModal').modal('hide');
        
    })
    .fail(function(x, t, s){
        
        if (x.statusText == 'Unprocessable Entity') {
            
            var $field = null,
                formAliasFields = {
                    'ref_name': 'doctor_ref_doc_or_org_name',
                    'ref_mobile': 'doctor_ref_doc_or_org_mobile',
                    'ref_phone': 'doctor_ref_doc_or_org_phone',
                    'ref_quali': 'doctor_ref_doc_quali',
                    'ref_address': 'doctor_ref_doc_or_org_address',
                    'ref_commission': 'doctor_ref_doc_or_org_comission',
                    'ref_type': 'doctor_ref_type',
                    'ref_status': 'doctor_ref_doc_or_org_status'
                };
            
            $.each(x.responseJSON.errors, function(field, message){
                $field = $('#' + formAliasFields[field]);
                $field.closest('.form-group').addClass('has-error')
                        .find('.help-inline').text(message);
            });
            
        } else if (x.statusText == 'Unauthorized') {
            
            alert('You are unauthorized to access here.')
            
        }
    });
}