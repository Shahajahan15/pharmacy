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

<style type="text/css">

    .table tbody tr td{
        margin: 0;
        padding:1px;
    }
    .table thead tr th{
        margin: 0;
        padding:1px;
    }
    .plus-minus{
        width:20px;
        height: 25px;
    }
    .panel-title{
    }
    .style-border{
            border: 3px solid #3c8dbc;
            padding: 5px;
            background: whitesmoke;
            margin: 2px;
        }

</style>

<div class="box box-primary">
    
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal"'); ?>
     <fieldset>
        <legend>Pharmacy Discount</legend>

        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="col-sm-4 col-md-4 col-lg-4 col-lg-offset-4 col-md-offset-4 col-sm-offset-4">
                <div class="form-group">
                    <span>
                        <input type="type" class="form-control medicine_name-search searbox-clean" disabled="" placeholder="Search Product by Name.....">
                    </span>
                    <div class="autocomplete_box"></div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-3">
            <div class="form-group">
                <label class="control-label">Product</label>
                <select name="discount_on" id="discount_on" class="form-control">
                    <option value="1">On All Product</option>
                    <option value="2">On Specific Product</option>                    
                </select>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-3">
            <div class="form-group">
                <label class="control-label">Discount From</label>
                <input type="text" class="form-control datepickerCommon discount_from"  required=""  name="discount_from" value="<?php echo date('d/m/Y'); ?>">
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-3">
            <div class="form-group">
                <label class="control-label">Discount To</label>
                <input type="text" class="form-control datepickerCommon discount_to" required=""  name="discount_to" value="">
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-3">
            <div class="form-group">
                <label class="control-label">Discount(%)</label>
                <input type="text" class="form-control discount_parcent"  name="discount_parcent" value="" required="">
            </div>
        </div>

       
         
     </fieldset>


     <fieldset>
         <legend>>Submit Requisition</legend>
         <table class="table">
             <thead>
                 <tr class="info">
                     <th width="20%">Product</th>
                     <th width="20%">Discount(%)</th>
                     <th width="20%">From</th>
                     <th width="20%">TO</th>
                     <th width="5%">Delete</th>
                 </tr>
             </thead>
             <tbody id="requistion_submit-data">
                 
             </tbody>
         </table>
     </fieldset>


     <fieldset>
         <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="text-center">
                <input type="submit" name="save" class="btn btn-primary btn-sm" value="Submit" />
                <input type="reset" name="" class="btn btn-primary btn-sm" value="Reset" />
            </div>
     </fieldset>
        	
    <?php echo form_close(); ?>


</div>
