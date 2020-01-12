<style type="text/css">
    .remove {
        font-size: 9px;
        color: red;
    }
</style>
<div class="row">
    <div class="col-md-6 col-lg-offset-3">
        <div class="form-group">
            <select tabindex="5" class="due-saleno-medicine-auto-complete form-control"></select>
        </div>
    </div>
</div>
<table class="table table-bordered c-table" style="margin-bottom:10px;">
    <thead>
    <tr class="active">
        <th>Customer Type</th>
        <th>Customer Name</th>
        <th>Total Due</th>
    </tr>
    </thead>
    <tbody>
    <tr class="success">
        <td><?php echo $record->customer_type_name; ?></td>
        <td><?php echo $record->client_name; ?></td>
        <td class="due"><?php echo $record->due; ?></td>
    </tr>
    </tbody>
</table>
<?php echo form_open($this->uri->uri_string(), 'role="form", class="mr_form_submit" autocomplete = "off"'); ?>
<div class="row">
    <div class="col-md-8 col-lg-offset-2">
        <table class="table report-table" style="display: none;">
            <thead>
            <tr class="info">
                <th>Sale No</th>
                <th>Due</th>
                <th style="width: 10px;">Action</th>
            </tr>
            </thead>
            <tbody class="sale_row">

            </tbody>
        </table>
    </div>
</div>
<?php if ($record) :

    $customer_type = $record->customer_type;
    ?>

    <input type="hidden" value="<?php echo ($customer_type == 1) ? $record->client_id : 0; ?>" name="admission_id"
           class="admission_id"/>
    <input type="hidden" value="<?php echo ($customer_type == 3) ? $record->client_id : 0;; ?>" name="customer_id"
           class="customer_id"/>
    <input type="hidden" value="<?php echo ($customer_type == 2) ? $record->client_id : 0;; ?>" name="patient_id"
           class="patient_id"/>
    <input type="hidden" value="<?php echo ($customer_type == 4 || $customer_type == 5) ? $record->client_id : 0;; ?>"
           name="employee_id" class="employee_id"/>
    <input type="hidden" value="<?php echo $record->customer_type; ?>" name="customer_type" class="customer_type"/>

    <input type="hidden" value="<?php echo $record->client_id; ?>" name="client_id" class="client_id"/>

    <table class="table table-bordered" style="margin-bottom:0px; ">
        <tbody>
        <tr class="success" style="text-align: center;">
            <td>Paid<span style="color:red;">*</span></td>
            <td><input type="text" name="due_paid" class="form-control due_paid" required=""/></td>
            <td>OverAll Discount</td>
            <td><input type="text" name="overall_discount" value="0" class="form-control overall_discount"/></td>
        </tr>
        <tr style="text-align: center;">
            <td colspan="5"><input type="submit" name="mr_submit" value="Done" class="btn btn-primary btn-xs"
                                   id="m_report_add">
                <!--<input type="reset" class="btn btn-warning btn-xs" value="Reset">-->
                <input type="button" onclick="resetDueForm()" class="btn btn-warning btn-xs" value="Reset">
            </td>
        </tr>
        </tbody>
    </table>
    <?php echo form_close(); ?>
<?php endif; ?>

<script type="text/javascript">
    $(document).ready(function () {
        $(document).on("keyup", ".due_paid, .overall_discount", function () {
            var paid = parseFloat($('.due_paid').val());
            var overall_discount = parseFloat($('.overall_discount').val());
            due_paid(paid, overall_discount);

        });


        $('form.mr_form_submit').validator().on('submit', function (e) {
            if (!e.isDefaultPrevented()) {
                e.preventDefault();
                $("input[type = 'submit']").prop('disabled', true);
                var master_id = $(".master_id").val();

                var customer_id = parseInt($('input[name="customer_id"]').val());
                var patient_id = parseInt($('input[name="patient_id"]').val());
                var admission_id = parseInt($('input[name="admission_id"]').val());
                var employee_id = parseInt($('input[name="employee_id"]').val());
                var customer_type = parseInt($('input[name="customer_type"]').val());
                // var sale_id = $('.sale_no').val();
                if (customer_type == 6) {
                    var _this = $('[customer_type="' + customer_type + '"]');
                } else if (customer_id > 0) {
                    var _this = $('[customer_id="' + customer_id + '"]');

                } else if (patient_id > 0) {
                    var _this = $('[patient_id="' + patient_id + '"]');

                } else if (admission_id > 0) {
                    var _this = $('[admission_id="' + admission_id + '"]');

                } else if (employee_id > 0) {
                    var _this = $('[employee_id="' + employee_id + '"]');

                }

                var sendData = $(this).serialize();
                // console.log(sendData);return;
                var targetUrl = siteURL + "main_pharmacy_sale_list/pharmacy/add";
                $.ajax({
                    url: targetUrl,
                    type: "POST",
                    data: sendData,
                    dataType: "json",
                    success: function (response) {
                        //console.log("res="+response);
                        if (response.success === true) {
                            resetDueForm();
                            //var net_bill = (response.payment.bill - response.payment.return_bill);
                            _this.closest('tr').find('.tot_bill').html(response.payment.bill);
                            _this.closest('tr').find('.tot_return').html(response.payment.return_bill);
                            var net_payment = (response.payment.payment - response.payment.return_amount);
                            _this.closest('tr').find('.tot_paid').html(net_payment);
                            _this.closest('tr').find('.overall_discount').html(response.payment.overall_discount);
                            var due = (response.payment.due);
                            _this.closest('tr').find('.tot_due').html(due);
                            if (due <= 0) {
                                _this.closest('tr').find('.status').html('<span class="label label-success">Paid</span>');
                                //_this.closest('tr').find('.status').remove();
                                _this.closest('tr').find('.full_paid').html('Full Paid');
                            }

                            print_view(response.print);
                            showMessages(response.message, 'success');

                        } else {
                            showMessages(response.message, 'error');
                        }
                        $('#commonModal').modal('hide');
                    }, error: function (jqXHR) {
                        console.log(jqXHR);
                        showMessages('Unknown Error!!!', 'error');
                        $('#commonModal').modal('hide');
                    }
                });
            }
        });
    });
    $(document).find('.due-saleno-medicine-auto-complete').select2({
        ajax: {
            // url: siteURL + "common/report/getMedicineByKey",
            url: siteURL + "main_pharmacy_sale_list/pharmacy/getMedicineDueSaleNoByKey" + "/" + $(".customer_type").val() + "/" + $(".client_id").val(),
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                };
            },
            processResults: function (data, params) {
                //console.log("data"+data);
                return {
                    results: data.items
                };
            },
            cache: true
        },

        allowClear: true,
        placeholder: '----------------------Sale No----------------------',
        minimumInputLength: 1
    });


    $(document).on('select2:select', '.due-saleno-medicine-auto-complete', function (e) {

        $('.report-table').show();
        $('.due_paid').prop('readonly', true);
        var $this = $(this);
        var sale_id = $this.val();
        var content = $this.find("option[value='" + sale_id + "']").text();
        var res = content.split(">>");

        var check = 0;
        $('table tbody.sale_row').find('.sale_no').each(function () {
            if ($(this).val() == sale_id) {
                check = 1;
                return false;
            }
        });
        //console.log('check='+check);
        if (check == 1) {
            // alert("already exit !");
            return false;
        }

        var row = '';
        row += '<tr class="success">';
        //row += '<span>'+res[0]+'('+parseFloat(res[1])+')<input type="hidden" value="'+sale_id+'" name="sale_no[]" class="sale_no"></span>';
        row += '<td>' + res[0] + '<input type="hidden" value="' + sale_id + '" name="sale_no[]" class="sale_no"></td>';
        row += '<td class="sub_due">' + parseFloat(res[1]) + '<input type="hidden" value="' + parseFloat(res[1]) + '" name="sale_no_due[]" class="sale_no_due"></td>';
        row += '<td><button type="button" class="remove"><i class="fa fa-times" aria-hidden="true"></i></button></td>';
        row += '</tr>';

        $('.sale_row').append(row);

        var due = getDue();
        //console.log('due arr='+due_arr);
        // var due = due_arr[0];

        $('.due_paid').val(due);
        if (due <= 0 || !due) {
            $('.report-table').hide();
        }

        //alert(sale_no);
    });

    $(document).on('click', '.remove', function () {

        var r_due = parseFloat($(this).closest('tr').find('.due').text());
        console.log('rdue=' + r_due);
        var due = parseFloat($('.due_paid').val());
        $('.due_paid').val(due - r_due);

        var check_due = getDue();

        if (check_due <= 0 || !check_due) {
            $('.due_paid').prop('readonly', false);
        }

    });

    function getDue() {
        due = 0;
        $('table tbody.sale_row .sub_due').each(function () {
            due += parseFloat($(this).text());
        });
        return due;
    }

    function resetDueForm() {
        $('.mr_form_submit')[0].reset();
        $('.due_paid').val('');
        $('.due_paid').prop('readonly', false);
        $('.sale_row').empty();
        $('.report-table').hide();
        $(".due-saleno-medicine-auto-complete").empty().trigger('change');
    }

    function due_paid(paid = 0, overall_discount = 0) {
        var due = parseFloat($('.due').text());
        if (!paid) {
            var paid = 0;
        }
        var paid_discount = parseFloat(paid + overall_discount);
        var due_paid = (due - overall_discount);
        console.log('due=' + due + 'paid=' + paid + 'overall_discount=' + overall_discount);
        if (due < overall_discount) {
            $('.due_paid').val(due);
            $('.overall_discount').val(0);
            return;
        }

        if (paid_discount > due) {
            if (!overall_discount || overall_discount == 0) {
                var due_paid = due;
            }
            $('.due_paid').val(due_paid);
            $('.overall_discount').val(overall_discount);
        }
    }


</script>