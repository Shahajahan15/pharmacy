


<table class="table table-hover">
    <thead>
        <tr class="active">
                        <th>Serial</th>
                        <th>Requisition No</th>
                        <th>Requisition Date</th>
                        <th>Issue Date</th>
                        <th>Product Name</th>
                        <th>Request Qntity</th>
                        <th>Issue Qntity</th>
        </tr>
    </thead>
    <tbody>
        <?php $sl=0; foreach($records as $record){ $record=(object)$record; ?>
        <tr class="warning">
            <td><?php echo $sl+=1; ?></td>
             <td><?php echo $record->requisition_no; ?></td>
              <td><?php echo $record->requisition_date; ?></td>
               <td><?php echo $record->issue_date; ?></td>
            <td>
                <?php echo $record->product_name; ?>
      
            </td>            
            <td><?php echo $record->req_qnty; ?></td>

            <td><?php echo $record->issue_qnty; ?></td>

        </tr>
        <?php } ?>
    </tbody>
</table>





