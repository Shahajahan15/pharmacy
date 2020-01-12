



<style>
 #confirm_alert .modal-header, #confirm_alert .pager{
    margin:3px;
    padding: 3px;
  }



.loader-only {
  left: 0;
  right: 0;
  position: fixed;
  top: 35%; 
  z-index: 999; 
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 90px;
  height: 90px;
  margin:0 auto;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
}

.loader {
  z-index: 999;
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 90px;
  height: 90px;
  margin:0 auto;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
}

@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}





</style>

<!-- Modal -->
<div id="confirm_alert" class="modal" role="dialog" hidden="">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <center><h3 class="modal-title" id="alert_message" style="color:red;background-color: whitesmoke"><b>Are You Sure ?</b></h3></center>
      </div>
      
      <div class="pager">
        <button type="button" class="btn btn-danger" data-dismiss="modal">NO</button>
        <button type="button" class="btn btn-success" id="confirm_action">YES</button>
      </div>
    </div>

  </div>
</div>



<!-- Common Modal -->
<div id="commonModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="commonModalTitle"></h4>
      </div>
      <div class="modal-body" id="commonModalBody">
        <div class="loader"></div>
      </div>
      <div class="modal-footer" id="commonModalFooter">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- alert Modal -->
<div id="alertModal" class="modal" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" id="alertHeader" style="border:3px solid whitesmoke;border-top: 30px solid whitesmoke;">
        <button type="button" class="pull-right btn btn-danger" data-dismiss="modal" style="margin-top: -41px;padding:0 10px">&times;</button>
        <h4 class="modal-title" id="alertModalMessage"></h4>
      </div>
    </div>

  </div>
</div>



<!--Confirm Modal -->
<div id="confirmAlert" class="modal" role="dialog" hidden="">
  <div class="modal-dialog modal-sm" id="alertSize">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <center><h3 class="modal-title" id="confirmAlertMessage" style="color:red;background-color: whitesmoke"><b>Are You Sure ?</b></h3></center>
      </div>
      
      <center style="margin: 5px 0">
        <button type="button" class="btn btn-success" id="confirm">YES</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">NO</button>
      </center>
    </div>

  </div>
</div>