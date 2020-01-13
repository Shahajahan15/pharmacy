<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap3.3.7.css'); ?>">
<script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>
<style type="text/css">
body {
    font-family: 'Helvetica Neue', 'Segoe UI', Helvetica, Arial, sans-serif;
}

.paid_unpaid {
    position: relative;
    z-index: 999999999;
    left: 100px;
    bottom: 400px;
}

.paid_unpaid img {
    width: 130px;
    height: 130px;
}
</style>

<body>

    <div class="container" style="background-color: #F1F6FA; padding: 20px">
        <p style="margin-top:-10px">
            <img src="<?php echo $img = ($hospital->logo == "")? base_url("assets/images/hospital/default.png") : base_url("assets/images/hospital/" . $hospital->logo); ?>"
                class="" width="90" height="90">
        </p>
        <div style="margin-top:-110px;">
            <p style="text-align: center; margin:0px; font-size:18px;font-weight:700"><?php echo $hospital->name; ?></p>
            <p style="text-align: center; margin:0px"><?php echo $hospital->address; ?></p>
            <p style="text-align: center; margin:0px">Phone: <?php echo $hospital->phone; ?></p>
            <p style="text-align: center; margin:0px">Mobile: <?php echo $hospital->mobile; ?></p>
            <p style="text-align: center; margin:0px">E-mail: <?php echo $hospital->email; ?></p>
        </div>

        <p style="text-align: right">Print: <?php echo date('d/m/Y h:i A', strtotime($records['0']->created_date)); ?>
        </p>
        <h2 class="bg-info"
            style="width: 450px; margin:auto; text-align: center; padding: 10px; border-radius: 5px; background-color: #B7C9CE">
            Pharmacy Bill(Client Copy)</h2>
        <div class="main-section" style="width: 100%; margin: 20px auto 10px;">
            <div style="border: 2px solid #E3EAE8; width:100%">
                <div style="width: 49%; display: inline-block; padding-left: 10px">
                    <table style="font-size: 14px; width: 100%; margin:0px 0px 10px">
                        <tbody>
                            <tr>
                                <td style="width: 50%">Name</td>
                                <td style="width: 10%">:</td>
                                <td style="width: 40%"> <?php echo $c_info->name; ?></td>
                            </tr>

                            <tr>
                                <td style="width: 50%">Money Receipt No</td>
                                <td style="width: 10%">:</td>
                                <td style="width: 40%"><?php echo $records['0']->sale_no; ?></td>
                            </tr>

                            <tr>
                                <td style="width: 50%">Phone</td>
                                <td style="width: 10%">:</td>
                                <td style="width: 40%"> <?php echo $c_info->mobile; ?></td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Invoice Date</td>
                                <td style="width: 10%">:</td>
                                <td style="width: 40%">
                                    <?php echo date('d/m/Y h:i A', strtotime($records['0']->created_date)); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="width: 49%; display: inline-block;">
                    <table style="font-size: 12px; width: 100%; margin:10px 0px 10px">
                        <img src="<?php echo base_url('barcode/pharmacy_sale/'.$records['0']->customer_id).'-IP-10234234.png';?>"
                            alt="" style="width: 200px; height:40px; margin-top:-70px;" class="pull-right" />

                        <tbody style="margin-left: 100px">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="hospital-section" style="margin-top: 10px">
            <h4 style="margin-bottom: 10px">Pharmacy Bill as General</h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="text-align:center">Srl</th>
                            <th style="text-align:center">Date</th>
                            <th style="text-align:center">Medicine Name</th>
                            <th style="text-align:center">Unit Price</th>
                            <th style="text-align:center">Qty</th>
                            <th style="text-align:center">Disc</th>
                            <th style="text-align:center" width="15%">Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total_discount = 0;
                $sl = 1;
                foreach ($records as $record) {
                    $discount = $record->qnty * ($record->normal_discount_taka + $record->service_discount_taka);

                    $total_discount += $discount;
                    ?>
                        <tr>
                            <td style="text-align:center"><?php echo $sl++; ?></td>
                            <td style="text-align:center"><?php echo date('d/m/Y');?></td>
                            <td><?php echo $record->category_name . '&nbsp; >> &nbsp;' . $record->product_name ?></td>
                            <td style="text-align:center"><?php echo $record->unit_price ?></td>
                            <td style="text-align:center"><?php echo $record->qnty ?></td>
                            <td style="text-align: right">
                                <?php if ($discount > 0) { ?>
                                <?php echo $discount ?>
                                <?php } ?>
                            </td>
                            <td style="text-align: right">
                                <?php echo ($record->qnty * $record->unit_price) - $discount; ?></td>
                        </tr>
                        <?php }?>
                        <tr>
                            <td colspan="6"><b>Total Bill</b></td>

                            <td style="text-align: right"><b><?php echo intval($record->tot_bill); ?></b></td>
                        </tr>
                        <tr>
                            <!-- <td style="text-align: right" colspan="7"><b><i>In Word:</i> </b> Four Handred Fourty Five Taka only</td> -->
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="last-section">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">

                    </div>
                    <div class="col-md-4">

                    </div>
                    <div class="col-md-4" style="border: 2px solid #E3EAE8">
                        <table style="font-size: 14px; width: 100%; margin:0px 0px 0px">
                            <tbody>
                                <tr>
                                    <td style="width: 50%; text-align: right"><b>Amount to be pay</b></td>
                                    <td style="width: 10%; text-align: right"><b>:</b></td>
                                    <td style="width: 40%; text-align: right">
                                        <b><?php echo intval($record->tot_bill); ?></b></td>
                                </tr>
                                <?php if ($total_discount > 0) { ?>
                                <tr>
                                    <td style="width: 50%; text-align: right "><b>Total Discount</b></td>
                                    <td style="width: 10%; text-align: right"><b>:</b></td>
                                    <td style="width: 40%; text-align: right">
                                        <b><?php echo intval($total_discount); ?></b></td>
                                </tr>
                                <?php } ?>
                                <?php if ($record->tot_less_discount > 0) { ?>
                                <tr>
                                    <td style="width: 50%; text-align: right "><b>Total Less Discount</b></td>
                                    <td style="width: 10%; text-align: right"><b>:</b></td>
                                    <td style="width: 40%; text-align: right">
                                        <b><?php echo intval($record->tot_less_discount); ?></b></td>
                                </tr>
                                <?php } ?>

                                <tr>
                                    <td style="width: 50%; text-align: right"><b>Total Receivable</b></td>
                                    <td style="width: 10%; text-align: right"><b>:</b></td>
                                    <td style="width: 40%; text-align: right">
                                        <b><?php echo intval($total_receivable = $record->tot_bill - $record->tot_less_discount); ?></b>
                                    </td>
                                </tr>
                                <!-- <tr>
                                    <td style="width: 50%; text-align: right"><b>Total Payable</b></td>
                                    <td style="width: 10%; text-align: right"><b>:</b></td>
                                    <td style="width: 40%; text-align: right"><b><?php echo intval($total_payable = $record->tot_bill - $record->tot_less_discount); ?></b></td>
                                </tr> -->
                                <tr>
                                    <td style="width: 50%; text-align: right"><b>Paid Amount</b></td>
                                    <td style="width: 10%; text-align: right"><b>:</b></td>
                                    <td style="width: 40%; text-align: right">
                                        <b><?php echo intval($total_paid = $record->tot_paid); ?></b></td>
                                </tr>

                                <tr>
                                    <td style="width: 50%; text-align: right "><b>Due</b></td>
                                    <td style="width: 10%; text-align: right"><b>:</b></td>
                                    <td style="width: 40%; text-align: right">
                                        <b><?php echo intval($total_due = $total_payable - $total_paid); ?></b></td>
                                </tr>
                                <?php if (isset($previous_due) && $previous_due->due > 0):
                                    $prev_due = ($previous_due->due - $records['0']->tot_due);
                                ?>
                                <tr>
                                    <td style="width: 50%; text-align: right "><b>Previous Due:</b></td>
                                    <td style="width: 10%; text-align: right"><b>:</b></td>
                                    <td style="width: 40%; text-align: right"> <b><?php echo intval($prev_due); ?></b>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50%; text-align: right "><b>Current Due</b></td>
                                    <td style="width: 10%; text-align: right"><b>:</b></td>
                                    <td style="width: 40%; text-align: right">
                                        <b><?php echo intval($previous_due->due); ?></b></td>
                                </tr>
                                <?php endif ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-section" style="width: 100%;">
            <div class="pre" style="width: 49%; margin-top: 50px; display: inline-block;">
                <span style="border-top: 1px solid black; border-width:10%"> Prepared By </span>
            </div>
            <div class="rec" style="width: 49%; margin-top: 50px; text-align: right; display: inline-block;">
                <span style="border-top: 1px solid black"> Cash Received By <?php echo $current_user; ?> </span>
            </div>
        </div>
    </div>
    <br />
    <br />
    <p style="text-align: center; font-weight: bold;">বিঃদ্রঃ ক্যাশ মেমো ছাড়া ওষুধ ফেরত নেয়া হবে না ।</p>
    <?php $dp_img = ($total_due == "0") ? "paid" : "due"; ?>
    <div class="paid_unpaid"><img src="<?php echo base_url("assets/images/paid_unpaid/$dp_img.png"); ?>"></div>
</body>

<script type="text/javascript">
window.print();
window.close();
</script>