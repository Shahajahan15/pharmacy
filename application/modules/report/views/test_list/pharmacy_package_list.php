<?php if ($testPack) :  $k = 0;
?>
<div class="panel-group test-panel-body" id="accordion" role="tablist" aria-multiselectable="true">
<?php foreach ($testPack as $value): $k++; ?>
            <div class="panel panel-<?php echo ($k % 2 == 0) ? 'info' : 'success'?> test-panel">
                <div class="panel-heading" role="tab" id="heading1">
                    <h4 class="panel-title ac-button">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $value->id; ?>" aria-expanded="false" aria-controls="collapse1">
                        <?php echo "Package Name:&nbsp;".$value->package_name.",&nbsp;Total Product:&nbsp;".$value->count.",&nbsp;Total Price:&nbsp;".$value->total_price; ?>
                        </a>
                    </h4>
                </div>
                <div id="collapse<?php echo $value->id; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading1">
                    <div class="panel-body">
                        <table class="table table-bordered c-table" style="margin-bottom: 0px;">
                            <thead>
                                <tr class="active">
                                    <td><input type="checkbox" name="pharmacy_test_package[]" class="pharmacy_test_package form-control" style="height: 15px" value="<?php echo $value->id; ?>"></td>
                                    <td>Product Name</td>
                                    <td>Product Stock</td>
                                    <td>Product Price</td>

                                </tr>
                            </thead>
                            <tbody>
                                <?php  $i = 0; foreach ($test_pack_dtls as $dtls) :
                                    if ($value->id == $dtls->master_id) : $i++;
                                ?>
                                <tr class="<?php echo ($i % 2 == 0) ? 'info' : 'success'?>">
                                    <td>#</td>
                                    <td><?php echo $dtls->product_name; ?></td>
                                    <td><?php echo $dtls->quantity_level; ?></td>
                                    <td><?php echo $dtls->unit_price; ?></td>
                                </tr>
                                <?php endif; endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            </div>
<?php endif; ?>
