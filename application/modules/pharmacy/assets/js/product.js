$(document).on("change", "#pharmacy_category_name", function(e) {
	var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	var id = $(this).val();
    var targetUrl = "product/pharmacy/getSubCategoryAjax";
    var sendData = {id: id, ci_csrf_token: ci_csrf_token};
    $.post(siteURL + targetUrl, sendData).done(function (data) {
        console.log('sa');
        $("#pharmacy_sub_category_name").html(data);
    });
});

$(document).on('submit', '#pharmacy-product-form', function(e) {
    e.preventDefault();

    var $self = $(this),
        $btn = $self.find('input[type="submit"]')
       // console.log($btn)
        $form = $self,
        formUrl = $form.attr('action'),
        formData = $form.serialize() + '&save=1';


    if ($form.find('.has-error').length > 0) {
        console.warn('Form has errors. Validate fields first.');
        return false;
    }

    $.ajax({
        type : 'post',
        url : formUrl,
        data : formData,
        dataType : 'json'
    })
    .always(function(){
        $btn.prop('disabled', false);
    })
    .done(function(resp) {
        console.log('sss');
        $('#bf-list-view').trigger('bf-list:reload');

        $self.find('#pharmacy_product_name').val('');
    })
    .fail(function(x,t,s){
        showErrorModal();
    });

});


