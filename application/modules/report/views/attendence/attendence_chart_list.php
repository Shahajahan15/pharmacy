<?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal"'); ?>
<fieldset>
	<div class="col-md-12">
		<div class="col-md-4 col-lg-4 col-md-offset-4 col-lg-offset-4">
			<div class="form-group">
				<label class="control-label">Month</label>
				<div class="input-group">
					<select name="month" class="form-control month" value="" id="month">
						<?php $i=0;
						$start_date='2017-01-01';
					while($i!=30){
							$date=date("F,Y", strtotime($start_date ." +$i month"));
						?>

						<option value="<?php echo date('Y-m',strtotime($start_date . " +$i month"));?>" <?php if($date==date("F,Y")){echo 'selected';}?>>
							<?php 
							echo $date;
								if($date==date("F,Y")){
									$i=29;
								}						
							?>
						</option>
							
						<?php $i++; }?>
					</select>
					<div class="input-group-addon btn-xs btn process-now">
						<span class=""><i class="fa fa-bolt" aria-hidden="true"> View Now</i></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</fieldset>
 <?php echo form_close(); ?>
<section id="attendence-chart">
	
</section>




