<?php
$num_columns	= 7;
$has_results	= isset($results) && is_array($results) && count($results);
$TotalDr		= 0;
$TotalCr		= 0;
?>
<div class="admin-box error-log">
	<?php echo form_open($this->uri->uri_string()); ?>

        
		<table class="table table-striped">
			<thead>
				<tr>
					<th><?php echo lang("errorlog_log_date");?></th>
					<th><?php echo lang("errorlog_errtype");?></th>
                    <th><?php echo lang("errorlog_errfile");?></th>
                    <th><?php echo lang("errorlog_errstr");?></th>
					<th><?php echo lang("errorlog_errline");?></th>
					<th><?php echo lang("errorlog_user_agent");?></th>
					<th><?php echo lang("errorlog_referer");?></th>
				</tr>
			</thead>
			
			<tbody>
				<?php
				if ($has_results) :
					foreach ($results as $record) :
                        $record = (array) $record;
				?>
				<tr>
					<td><?php e($record["log_date"]) ?></td>
					<td><?php e($record["errtype"]) ?></td>
					<td><?php e($record["errfile"]) ?></td>
					<td><?php e($record["errstr"]) ?></td>
					<td><?php e($record["errline"]) ?></td>
					<td><?php e($record["user_agent"]) ?></td>
					<td>
                        <div class="errorlogLayout">
                            <p><a href="javascript:void(0)" class="showMore">Show Details</a></p>
                            <div class="errorlog hide">
                                    <?php
                                        $err = json_decode($record["referer"], true);
                                        print_r($err);
                                        /*$i=1; $errorContent = "";
                                        foreach($err as $rows){
                                            if(is_array($rows)){
                                                foreach($rows as $row){
                                                    $errorContent .= '<p>'.$i++.') '.$row.'</p>';
                                                }
                                            } else {
                                                $errorContent .= '<p>'.$i++.') '.$row.'</p>';
                                            }
                                        }

                                        echo $errorContent;*/
                                    ?>
                            </div>
                        </div>
                    </td>
				</tr>
				<?php
					endforeach;

				else:
				?>
				<tr>
				<td colspan="<?php echo $num_columns;?> ">
				    <?php echo lang("errorlog_no_records_found")?>
                </td>
				</tr>
				<?php endif; ?>
			</tbody>
		</table>

    <!--div class="container">
        <div class="productLayout">
            <p><a href="#" class="showMore">Show More 1</a></p>
            <div class="hidden hide">this is hidden content</div>
        </div>
        <div class="productLayout">
            <p><a href="#" class="showMore">Show More 2</a></p>
            <div class="hidden hide">this is hidden content</div>
        </div>
        <div class="productLayout">
            <p><a href="#" class="showMore">Show More 3</a></p>
            <div class="hidden hide">this is hidden content</div>
        </div>
        <div class="productLayout">
            <p><a href="#" class="showMore">Show More 4</a></p>
            <div class="hidden hide">this is hidden content</div>
        </div>
        <div class="productLayout">
            <p><a href="#" class="showMore">Show More 5</a></p>
            <div class="hidden hide">this is hidden content</div>
        </div>
        <div class="productLayout">
            <p><a href="#" class="showMore">Show More 6</a></p>
            <div class="hidden hide">this is hidden content</div>
        </div>
        <div class="productLayout">
            <p><a href="#" class="showMore">Show More 7</a></p>
            <div class="hidden hide">this is hidden content</div>
        </div>
        <div class="productLayout">
            <p><a href="#" class="showMore">Show More 8</a></p>
            <div class="hidden hide">this is hidden content</div>
        </div>
    </div-->

	<?php echo form_close();

    echo $this->pagination->create_links();

    ?>

</div>