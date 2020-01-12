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

$can_edit = true; //$this->auth->has_permission('pharmacy.pharmacy.Edit');
?>
<div>
    <table class="table">
        <thead>
            <tr>
                <th width="10%">SL</th>
                <th>Pharmacy Name</th>
                <th>Phone</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>Images</th>
                <th>Logo</th>
            </tr>
        </thead>
        <tbody>

            <?php if($records){ ?>
            <?php $sl=1; foreach($records as $record){ ?>
                <tr>
                    <td><?php echo $sl++; ?></td>
                    <?php if($can_edit){?>
                        <td><?php echo anchor(SITE_AREA . '/pharmacy_setup/pharmacy/edit/' . $record->id, '<span class="glyphicon glyphicon-pencil"></span>' . $record->name,'class="bf-edit-action"'); ?></td>
                    <?php }else{?>
                    <?php }?>
                        <td><?php echo $record->mobile; ?></td>
                        <td><?php echo $record->phone; ?></td>
                        <td><?php echo $record->email; ?></td>
                        <td><img src="<?php echo $img = ($record->logo == "")? base_url("assets/images/hospital/default.png") : base_url("assets/images/hospital/" . $record->logo); ?>"class="img-circle"  width="80" height="80"></td>
                    <td><?php echo ($record->status==1) ?'Active':'Inactive'; ?></td>
                </tr>
            <?php } }else{?>
                <tr>
                    <td colspan="3">Record Not Found</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>