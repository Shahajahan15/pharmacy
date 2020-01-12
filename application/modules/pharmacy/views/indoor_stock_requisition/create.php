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
        <legend>Pharmacy Requisition Create</legend>

        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="col-sm-4 col-md-4 col-lg-4 col-lg-offset-4 col-md-offset-4 col-sm-offset-4">
                <div class="form-group">
                   <!--  <span>
                        <input type="type" class="form-control medicine_name-search searbox-clean" placeholder="Search Product by Name.....">
                    </span>
                    <div class="autocomplete_box"></div> -->

                    <select tabindex="5" class="medicine-auto-complete form-control"></select>

                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-3 col-lg-offset-2">
            <div class="form-group">
                <label class="control-label">Requisition Date</label>
                <input type="text" disabled='' class="form-control"  name="" value="<?php echo date('d/m/Y'); ?>">
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-3">
            <div class="form-group">
                <label class="control-label">Pharmacy Name<span style="color:red;">*</span></label>
                <select name="issue_pharmacy_id" id="issue_pharmacy_id" class="form-control" required="">
                   <!-- <option value="">Select a pharmacy</option> -->
                    <?php foreach($pharmacy_name as $p_row){?>
                    <option value="<?php echo $p_row['id']; ?>" <?php if(isset($requisition)){ echo ($p_row['id'] == $requisition->issue_pharmacy_id)? 'selected':''; } ?>><?php echo $p_row['name']; ?></option>
                    <?php } ?>
                    
                </select>
            </div>
        </div>

       <!-- <div class="col-sm-12 col-md-6 col-lg-3">
            <div class="form-group">
                <label class="control-label">Category</label>
                <select name="" id="store_category" class="form-control">
                    <option value="">Select Category</option>
                    <?php foreach($categories as $cat){?>
                    <option value="<?php echo $cat->id; ?>" <?php if(isset($requisition)){ echo ($cat->id==$requisition->category_id)? 'selected':''; } ?>><?php echo $cat->category_name; ?></option>
                    <?php } ?>
                    
                </select>
            </div>
        </div>
         <!--<div class="col-sm-12 col-md-6 col-lg-3">
            <div class="form-group">
                <label class="control-label">Sub Category</label>
                <select name="" id="store_sub_category" class="form-control">
                    <option value="">Select Sub Category</option>
                    
                </select>
            </div>
        </div>
         <div class="col-sm-12 col-md-6 col-lg-3">
            <div class="form-group">
                <label class="control-label">Product</label>
                <select name="" id="store_product" class="form-control">
                    <option value="">Select Product</option>                    
                </select>
            </div>
        </div> -->

     </fieldset>


     <fieldset>
         <legend>Submit Requisition</legend>
         <table class="table">
             <thead>
                 <tr class="info">
                     <th width="20%">Category</th>
                     <!--<th width="20%">Subcategory</th>-->
                     <th width="20%">Product</th>
                     <th width="20%">Issue Pharmacy Stock</th>
                     <th width="20%">Quantity(Req.)<span style="color:red;">*</span></th>
                     <th width="5%">-</th>
                 </tr>
             </thead>
             <tbody id="requistion_submit-data">
                 
             </tbody>
         </table>
     </fieldset>


     <fieldset>
         <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="text-center">
                <!-- <input type="submit"  name="save" class="btn btn-primary btn-sm" value="Submit"  /> -->
                <button name="save" class="btn btn-primary btn-sm" onclick="doubleClickNotSubmit(this)" type="submit" value="Submit">Submit</button>
                <input type="reset" name="" class="btn btn-primary btn-sm" value="Reset" />                
            </div>
     </fieldset>
        	
    <?php echo form_close(); ?>


</div>
