<div class="admin-box">
    
    <table class="table table-striped">
        
        <thead>
            <tr>
                <th><?php e(lang('userwise_column_collection_head')) ?></th>
                <th><?php e(lang('userwise_column_collection')) ?></th>
                <th><?php e(lang('userwise_column_refund')) ?></th>
                <th><?php e(lang('userwise_column_total')) ?></th>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach($items as $item) : ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        
        <tfoot>
            <tr>
                <td><?php e(lang('userwise_column_tfoot_total_balance')) ?></td>
                <td><?php echo 0 ?></td>
                <td><?php echo 0 ?></td>
                <td><?php echo 0 ?></td>
                
            </tr>
        </tfoot>
        
    </table>
    
    
</div>