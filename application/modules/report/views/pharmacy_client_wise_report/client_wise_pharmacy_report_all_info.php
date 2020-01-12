<table class="table table-striped m_ph_payment">
    <thead>
        <tr>
            <th>SL No.</th>
            <th>Date</th>
            <th>Paid Amount</th>
            <th>Return</th>
        </tr>
    </thead>
    <?php
            $has_records = isset($records) && is_array($records) && count($records);
            ?>
    <tbody>
        <?php
            if ($has_records) :
                $i=1;
                foreach ($records as $record) :

                    ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo date('d/m/Y',strtotime($record->create_time)); ?></td>
            <td><?php if($record->type==1 || $record->type==2){echo $record->amount;} ?></td>
            <td><?php if($record->type==3){echo $record->amount;}else {echo "0";}?></td>
        </tr>
        <?php
            endforeach;
         ?>
        <?php
            else:
        ?>
        <tr>
            <td colspan="4"><?php echo lang('bf_msg_records_not_found'); ?></td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>