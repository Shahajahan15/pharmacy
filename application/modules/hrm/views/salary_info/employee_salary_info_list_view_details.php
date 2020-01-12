<style>

span#detailsId{ color: blue;
                    cursor:pointer;
    }
</style>
<?php 
//extract($sendData);
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

if (isset($requision_records))
{
    $records = (array) $requision_records;
}
$id = isset($records['id']) ? $records['id'] : '';

?>

<?php
$num_columns	= 8;
$can_delete	= $this->auth->has_permission('Store.supplier.Delete');
$can_edit	= $this->auth->has_permission('Store.supplier.Edit');
$has_records	= isset($records) && is_array($records) && count($records);
?>
<div class="admin-box">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="content"> 

        <?php echo $this->load->view('salary_info/salary_info_form_manual', $_REQUEST, true); ?>    

        </div>
        <table class="table table-striped  ">
            <thead>
                <tr>				
                    <th width=""><?php //echo lang('store_product_name'); ?></th>
                    <th width=""><?php //echo lang('Store_Requisition_Qty'); ?></th>
                </tr>
            </thead>
                <?php if ($has_records) : ?>
            <?php endif; ?>
            <tbody>
                <?php

                if ($has_records) :
                        foreach ($records as $record) : 
                ?>
                <tr>

                <?php if ($can_edit) : ?>

                    <td>
                        <a href="#" id="detailsId" onclick="detailsEdit('<?php echo $record->SALARY_HEAD; ?>','<?php echo $record->AMOUNT; ?>','<?php echo $record->RULE_NAME; ?>',<?php echo $record->HRM_EMPLOYEE_SALARY_INFO_MST_ID; ?>,<?php echo $record->HRM_EMPLOYEE_SALARY_INFO_DTLS_ID; ?>)"><?php echo $record->SALARY_HEAD_NAME; ?></a></td>
                <?php else : ?>
                <?php endif; ?>

                    <td id="amount"><?php e($record->AMOUNT);  ?>  </td>
                    <td id="rule"><?php e($record->RULE_NAME);  ?>  </td>

                </tr>
                <?php
                endforeach;
                else:
                ?>
                <tr>
                    <td colspan="<?php echo $num_columns; ?>"><?php echo lang('bf_msg_records_not_found'); ?></td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>