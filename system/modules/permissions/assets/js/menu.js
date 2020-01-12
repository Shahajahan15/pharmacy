$(document).ready(function(){    
    $('.dd').nestable({
        dropCallback: function(details) {
            var order = new Array();
            $("li[data-id='"+details.destId +"']").find('ol:first').children().each(function(index,elem) {
                order[index] = $(elem).attr('data-id');
            });

            if (order.length === 0){
                var rootOrder = new Array();
                $("#nestable > ol > li").each(function(index,elem) {
                    rootOrder[index] = $(elem).attr('data-id');
                });
            }

            var ci_csrf_token = $("input[name='ci_csrf_token']").val();
            $.post(siteURL+'menu/permissions/menuOrderSaved',
                { source : details.sourceId,
                    destination: details.destId,
                    order:JSON.stringify(order),
                    rootOrder:JSON.stringify(rootOrder),
                    ci_csrf_token:ci_csrf_token
                },
                function(data) {
                    // console.log('data '+data);
                })
                .done(function() {
                    $( "#success-indicator" ).fadeIn(100).delay(1000).fadeOut();
                })
                .fail(function() {  })
                .always(function() {  });
        }
    });

});


$(".add_menu_btn").on("click", function(){
    var url=$(this).attr('data-href');
    var targetUrl=siteURL+url;

    $('#commonModalTitle').html('Add/Edit Menu');
    $('#commonModalFooter').remove();
    $('#commonModalBody').html('<div class="loader"></div>');
    $('#commonModal').modal('show');
    $.get(targetUrl,function(content) {
        $('#commonModalBody').html(content);
    });    
});

