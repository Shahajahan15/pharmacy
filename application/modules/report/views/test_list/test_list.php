
<div class="panel-group test-panel-body" id="accordion" role="tablist" aria-multiselectable="true">
<?php if ($test_group) :  $k = 0;
foreach ($test_group as $value): $k++;
?>
            <div class="panel panel-<?php echo ($k % 2 == 0) ? 'info' : 'success'?> test-panel">
                <div class="panel-heading" role="tab" id="heading1">
                    <h4 class="panel-title ac-button">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $value->id; ?>" aria-expanded="false" aria-controls="collapse1">
                        <?php echo "Test Group:&nbsp;".$value->test_group_name.",&nbsp;Total Test:&nbsp;".$value->count; ?>
                        </a>
                    </h4>
                </div>
                <div id="collapse<?php echo $value->id; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading1">
                    <div class="panel-body">
                        <table class="table table-bordered c-table" style="margin-bottom: 0px;">
                            <thead>
                                <tr class="active">
                                    <td>#</td>
                                    <td>Test Name</td>
                                    <td>Test Price</td>
                                    <td>Sample?</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php  $i = 0; foreach ($test_dtls as $dtls) :
                                    if ($value->id == $dtls->test_group) : $i++;
                                ?>
                                <tr class="<?php echo ($i % 2 == 0) ? 'info' : 'success'?>">
                                    <td><input name="test_id" class="group_test_id" value="<?php echo $dtls->id; ?>" type="checkbox"/></td></td>
                                    <td><?php echo $dtls->test_name; ?></td>
                                    <td><?php echo $dtls->test_taka; ?></td>
                                    <td><?php echo ($dtls->sample == 1) ? "Yes" : "No"; ?></td>
                                </tr>
                                <?php endif; endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endforeach; endif; ?>
            </div>
