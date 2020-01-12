/*global $*/
$(document).ready(function(){

    $(document).on('change','#main_store_select', function(e){
        var $self = $(this),
            url = $self.attr('data-href'),
            store_id = $self.val();

        window.location.href = url + '/' + store_id;
    });

    $(document).on('click','.main_store_details1', function(e){
        e.preventDefault();

        var $self = $(this),
            url = $self.attr('href'),
            modalBody = $('#commonModalBody');

        modalBody.html('<p class="text-center"><i class="fa fa-spinner fa-spin fa-3x"></i></p>');
        $('#commonModalFooter').html('');
        $('#commonModal').modal('show');

        $.ajax({
            type:'get',
            url: url,
            dataType: 'html',
            timeout: 10000
        })
        .always(function(){

        })
        .done(function(resp){
            modalBody.html(resp);
        })
        .fail(function(x, t, s){
            modalBody.html('<p class="text-danger">There has been a problem connecting to the server. Please try again later.</p>');
        });

    });

});