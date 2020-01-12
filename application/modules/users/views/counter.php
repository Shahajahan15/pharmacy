<style>.alert-error{color:#a94442;background-color:#f2dede;border-color:#ebccd1}</style>
<div class="container">
    <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
        <div class="panel panel-info" >
            <div class="panel-heading">
                <div class="panel-title">Select Counter</div>
            </div>

            <?php echo form_open($this->uri->uri_string(), array('autocomplete' => 'off', 'class' => 'form-horizontal', 'id' => 'counterform', 'role'=> 'form')); ?>

            <div style="padding-top:30px" class="panel-body" >
                <?php echo Template::message(); ?>

                <div style="margin-bottom: 15px" class="<?php echo iif( form_error('counter_id') , 'error') ;?>">
                    <label class="control-label" for="counter_id">Select Counter<span class="required">*</span></label>
                    <div class="controls">
                        <select name="counter_id" class="form-control" id="counter_id" >
                            <option value=''>Select one</option>
                            <?php
                                if($counters){
                                    foreach($counters as $row){
                                        echo "<option value='".$row->counter_id."'";
                                        echo ">".$row->counter_name."</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>

            <div style="margin-top:5px" class="form-group">
                <div class="col-sm-12 controls text-center">
                    <input class="btn btn-primary" type="submit" name="Submit" id="submit" value="Submit" /> Or <a href="">click here to refresh counter list</a>
                </div>
            </div>

            <?php echo form_close(); ?>

        </div>
    </div>
</div>
    