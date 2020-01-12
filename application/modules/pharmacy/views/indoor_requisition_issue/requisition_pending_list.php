<?php
$validation_errors = validation_errors();
if ($validation_errors) :
    ?>
    <div class="alert alert-block alert-error fade in">
        <a class="close" data-dismiss="alert">&times;</a>
        <h4 class="alert-heading">Please fix the following errors:</h4>
        <?php echo $validation_errors; ?>
    </div>
    <?php
endif;

?>

<?php

$can_delete = false; //$this->auth->has_permission('pharmacy.dept.requisition.Delete');
$can_edit = false; //$this->auth->has_permission('pharmacy.dept.requisition.Edit');

$has_records = true; //isset($records) && is_array($records) && count($records);
?>

<div class="admin-box">
    <div class="col-sm-12 col-md-12 col-lg-12">
<?php echo form_open($this->uri->uri_string()); ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>SL</th>         
                    <th>Pharmacy</th>			
                    <th>Requisitio No</th>
                    <th>Requisition Date</th>
                    <th>Requisition By</th>
                    <th>Action</th>
                </tr>
            </thead>
            
            <tbody>
            <?php
            if ($has_records) :
                $sl=1;
                foreach ($records as $record) :
                    ?>
                        <tr>

                        <?php if ($can_delete) : ?>
                                <td class="column-check"><input type="checkbox" name="checked[]" value="<?php echo $record->id; ?>" /></td>
                            <?php endif; ?>
                                <td><?php echo $sl++; ?></td>
                                <td><?php echo ($record->pharmacy_name) ? $record->pharmacy_name : "Main Pharmacy"; ?></td>

                                <?php if ($can_edit) : ?>
                                <td><?php echo anchor(SITE_AREA . '/indoor_requisition_issue/pharmacy/edit/' . $record->id, '<span class="glyphicon glyphicon-pencil"></span>' . $record->requisition_no); ?></td>
                            <?php else : ?>
								<td><?php e($record->requisition_no); ?></td>
                            <?php endif; ?>
                            <td><?php echo date('d-m-Y',strtotime(str_replace('/','-',
                                $record->requisition_date)))?></td>
                            <td><?php e($record->emp_name); ?></td>
                            <td>
                            <a class="btn btn-info btn-xs glyphicon glyphicon-th products_issue" mst_id="<?php echo $record->id; ?>"></a>
                            </td>
                        </tr>
                        <?php
                    endforeach;
                else:
                    ?>
                    <tr>
                        <td colspan="4"><?php echo lang('bf_msg_records_not_found'); ?></td>
                    </tr>
        <?php endif; ?>
            </tbody>
        </table>
<?php echo form_close(); ?>
    </div>
</div>
<?php echo $this->pagination->create_links();?>

<script type="text/javascript">

$(document).ready(function(){

    <?php if(isset($print_page)){?>

    var jsonObj=<?php echo json_encode($print_page) ?>;
    print_view(jsonObj);
    <?php unset($print_page); } ?>
})

    
</script>

