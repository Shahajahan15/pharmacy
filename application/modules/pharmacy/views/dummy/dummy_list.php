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
$num_columns = 8;
$can_delete = $this->auth->has_permission('pharmacy.Dummy.Delete');
$can_edit = $this->auth->has_permission('pharmacy.Dummy.Edit');
$has_records = isset($records) && is_array($records) && count($records);
?>
<div class="admin-box">
    <div class="col-sm-12 col-md-12 col-lg-12">

        <table class="table table-striped">
            <thead>
                <tr>

                    <th width="20%"><?php echo 'id'; ?></th>
                    <th width="20%"><?php echo 'Name'; ?></th>
                    <th width="20%"><?php echo 'Imdb'; ?></th>
                    <th width="20%"><?php echo 'RT'; ?></th>

                </tr>
            </thead>

            <tbody>
            <?php
                foreach ($records as $record) :
                    ?>
                        <tr>

                            <td><?php echo $record->id; ?></td>
                            <td><?php echo $record->name; ?></td>
                            <td><?php echo $record->imdb ; ?></td>
                            <td><?php  echo $record->rottenTomatos;?></td>
                        </tr>
                        <?php
                    endforeach;
                    ?>
            </tbody>
        </table>
<?php echo form_close(); ?>
    </div>
<!--     --><?php //echo $this->pagination->create_links(); ?>
</div>