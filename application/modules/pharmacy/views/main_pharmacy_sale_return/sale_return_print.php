<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap3.3.7.css'); ?>">
<script src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>


<style type="text/css">

    #em_print_id {
        font-family: "Lucida Sans Unicode", Times, serif;
        text-align: center;
    }

    #em_print_id .table tr td {
        padding: 0px;
        margin: 0px;
        border: none;
        font-size: 11px;
        font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
    }

    #em_print_id .table tr th {
        padding: 0px;
        margin: 0px;
        border: none;
        font-size: 11px;
        font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
    }

    #em_print_id .table {
        margin: 0;
        padding: 0;
    }

    h5 {
        margin: 0px;
        margin-bottom: 10px;
        font-size: 11px;
        text-align: center;
    }

    h3 {
        margin: 0px;
        margin-top: 10px;
        font-size: 24px;
        text-align: center;
    }

    img {
        height: 50px;
    }

    .logo {
        width: 150px;
    }

    .head {
        text-align: right;
        margin-right: 150px;
    }

    .barcode {
        text-align: left;
    }

    .head-info {
        text-align: center;
    }

    .border-top {
        border-top: 1px solid black;
    }

    .border {
        border: 1px solid black;
    }

    .border_sales_return {
        border: 2px solid red;
        color: red;
        transform: rotate(-30deg);
        width: 40%;
        margin-left: 30%;
    }

    .h-center {
        text-align: center;
    }

    .v-align {
        vertical-align: middle !important;
    }

</style>

<div class=" box box-primary">
    <div class="col-md-8 col-md-offset-2" id="em_print_id">
        <table>
            <tr>
                <td>
                    <img src="<?php echo base_url($hospital->logo); ?>" width="200">
                </td>
                <td width="2%"></td>
                <td align="center" class="text-nowrap">
                    <b><?php echo $hospital->name; ?></b>
                    <p style="margin:0"><?php echo $hospital->address; ?></p>
                    <p style="margin:0"><?php echo $hospital->mobile; ?></p>
                </td>
                <td width="5%"></td>
                <td width="35%">
                    <table border="1" width="100%" style="margin-top:10px;">
                        <tr class="v-align h-center">
                            <td colspan="2"><b>Money Receipt</b></td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px; font-weight: bold;">Receipt No</td>
                            <td style="font-size: 12px; font-weight: bold;"><?php echo $records['0']->mr_no; ?></td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px; font-weight: bold;">Sale No</td>
                            <td style="font-size: 12px; font-weight: bold;"><?php echo $records['0']->sale_no; ?></td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px; font-weight: bold;">Date</td>
                            <td style="font-size: 12px; font-weight: bold;"><?php echo date('d/m/Y h:i A', strtotime($records['0']->created_date)); ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <br>
        <center>
            <p><b>Pharmacy Sale Return(Client Copy)</b></p>
        </center>
        <hr>

        <br>
        <table class="table">
            <thead>
            <tr class="border">
                <th>SL</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Discount</th>
                <th>Total Price</th>
            </tr>
            </thead>
            <tbody>
            <?php $sl = 1;
            foreach ($records as $record) { ?>
                <tr>
                    <td><?php echo $sl++; ?></td>
                    <td><?php echo $record->product_name ?></td>
                    <td><?php echo $record->price ?></td>
                    <td><?php echo $record->r_qnty ?></td>
                    <td><?php echo $record->tot_discount ?></td>
                    <td><?php echo $record->r_sub_total ?></td>
                </tr>
            <?php } ?>
            <tr class="border-top">
                <th colspan="4"></th>
                <th>Return Amount</th>
                <th><?php echo intval($record->tot_return_amount); ?></th>
            </tr>
            <?php if ($record->tot_less_discount != 0.00) { ?>
                <tr>
                    <th colspan="4"></th>
                    <th>Less Amount</th>
                    <th><?php echo round($record->tot_less_discount); ?></th>
                </tr>
            <?php } ?>

            <?php if ($record->overall_discount != 0.00) { ?>
                <tr>
                    <th colspan="4"></th>
                    <th>Overall Discount</th>
                    <th><?php echo round($record->overall_discount); ?></th>
                </tr>
            <?php } ?>

            <tr>
                <th colspan="4"></th>
                <th>Return Taka</th>
                <th><?php echo intval($total_return = $record->tot_paid_return_amount); ?></th>
            </tr>
            </tbody>
        </table>
        <br>
        <div class="border_sales_return">
            <strong style="padding: 5px;font-size: 25px;color: red;">Sales Return</strong>
        </div>
        <div class="border">
            <p style="text-align: left;margin:0;padding: 3px;"><b>Total Paid(In word)
                    : <?php echo inWords($total_return); ?> Only</b></p>
        </div>
        <table class="table" style="margin-top: 45px">
            <tr>
                <td>
                    <span class="border-top">Cash Received By <?php echo $current_user; ?></span>
                </td>
            </tr>
        </table>
    </div>
</div>


<script type="text/javascript">
    window.print();
    window.close();
</script>