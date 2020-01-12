
<!-- start date-->
<div class="col-xs-4 col-sm-3 col-md-2 col-lg-2 col-lg-offset-3">
	<div class="form-group">
		<label>Start Date<span class="required">*</span></label>
		<div class="input-group">
			<div class="input-group-addon">
				<i class="fa fa-calendar"></i>
			</div>
			<input type="text" id="start_date" name="start_date" class="form-control datepickerCommon" required="" value="<?php echo date("d/m/Y");?>">
		</div>
		<span class="help-inline"></span>
	</div>
</div>
<!-- start date-->
<div class="col-xs-4 col-sm-3 col-md-2 col-lg-2">
	<div class="form-group">
		<label>End Date<span class="required">*</span></label>
		<div class="input-group">
			<div class="input-group-addon">
				<i class="fa fa-calendar"></i>
			</div>
			<input type="text" id="end_date" name="end_date" class="form-control datepickerCommon" required="" value="">
		</div>
		<span class="help-inline"></span>
	</div>
</div>
<!-- start date-->
<div class="col-xs-4 col-sm-3 col-md-2 col-lg-2">
	<div class="form-group" style="margin-top: 19px;">
		<input type="submit" name="save" class="btn btn-primary btn-xs" id="rd_processing" value="Process" style="pointer-events: all; cursor: pointer;">
		<input type="button" onclick="resetRDPForm()" class="btn btn-warning btn-xs" value="Reset">
	</div>
</div>
</div>