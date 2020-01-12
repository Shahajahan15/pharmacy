/*global $*/
$(document).ready(function(){

    $(document).on('change','#store_department', function(e){
        var $self = $(this),
            url = $self.attr('data-href'),
            store_id = $self.val();

        window.location.href = url + '/' + store_id;
    });

});