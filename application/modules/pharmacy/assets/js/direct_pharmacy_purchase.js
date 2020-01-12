/**
 *  medicine auto complete search box
 */
$(document).on('select2:select', '.medicine-auto-complete', function (e) {
    var medicine_id = $(this).val();
    var ci_csrf_token = $("input[name='ci_csrf_token']").val();
    var sendData = {ci_csrf_token: ci_csrf_token, medicine_id: medicine_id};
    target = siteURL + 'direct_pharmacy_purchase/pharmacy/getProductInfoById/' + medicine_id;
    $.get(target, function (data) {
        //console.log(data);return;
        makeTableRow(data.category_name, data.cat_id, data.product_name, data.id, data.current_stock, data.purchase_price);
    }, 'json')
});

function makeTableRow(cat, cat_id, product, product_id, stock, purchase_price) {

    var exist = 0;
    var rows = $('#pharmacy_product_rows');
    rows.find('.product_id').each(function () {
        if ($(this).val() == product_id) {
            exist = 1;
            return false;
        }
    });
    if (exist == 1) {
        alert('Already assigned');
        return false;
    }

    var row = '';

    row += '<tr class="success">';

    row += '<td>' + cat + '<input type="hidden" name="category_id[]" value="' + cat_id + '" /> </td>';
    row += '<td>' + product + '<input type="hidden" name="product_id[]" class="product_id" value="' + product_id + '" /> </td>';
    row += '<td>' + stock + '</td>';
    row += '<td>' + purchase_price + '</td>';
    row += '<td><input type="text" name="order_qnty[]" class="form-control order_qnty real-number" required=""/></td>';
    row += '<td><input type="text" name="receive_free_qnty[]" class="form-control receive_free_qnty on-focus-selected numeric-zero" value="0" required=""></td>';
    row += '<td><input type="text" name="order_unit_price[]" class="form-control order_unit_price decimal" required=""/></td>';
    row += '<td><input type="text" name="order_total_price[]" class="form-control order_total_price" readonly="" required=""/></td>';
    row += '<td><button type="button" class="remove"><i class="fa fa-times" aria-hidden="true"></i></button></td>';

    row += '</tr>';

    rows.append(row);
}

$(document).validator().on('submit', '#purchase_pharmacy_form', function (e) {
    e.preventDefault();
    console.log('js call');
    if ($('#supplier_id').val() == '') {
        return false;
    }
    if ($('#store').val() == '') {
        return false;
    }
    var arr = [];
    $('input[name="order_qnty[]"]').each(function () {
        let a = $(this).val();
        arr.push(a);
    });
    //  $(".purchase_product_btn").prop('disabled', true);
    var sendData = $(this).serialize();
    var targetUrl = siteURL + "direct_pharmacy_purchase/pharmacy/save";
    $.ajax({
        url: targetUrl,
        type: "POST",
        data: sendData,
        dataType: "json",
        success: function (response) {
            if (response.status === true) {
                $('.pharmacy_supplier_auto').val('').trigger('change');
                $('.medicine-auto-complete').val('').trigger('change');
                $('#pharmacy_product_rows').empty();
                $('#purchase_pharmacy_form')[0].reset();
                toastr.success(response.message, 'Success Alert', {timeOut: 3000});
                print_view(response.print);
            } else {
                toastr.error(response.message, 'Inconceivable!', {timeOut: 5000});
            }
        }, error: function (jqXHR) {
            //console.log(jqXHR);
            showMessages('Unknown Error!!!', 'error');
        }
    });
});

$(document).on('click', '.receive_details', function (e) {

    let target = $(this).attr('href');

    $('#commonModalBody').html('<div class="loader"></div>');
    $('#commonModalTitle').html('Details Report');
    $('#commonModalFooter').html('');
    $('#commonModal').modal('show');

    $.get(target, function (data) {
        $('#commonModalBody').html(data);
    });

    return false;
});
