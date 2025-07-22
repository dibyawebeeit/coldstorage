<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manage
      <small>Compliences</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Compliences</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12 col-xs-12">

        <div id="messages">

                  <?php if($this->session->flashdata('messages')){  ?>
                  <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong><?php echo $this->session->flashdata('messages'); ?>
                  </div>
                <?php   $this->session->set_flashdata('messages', '');} ?>

                 <?php if($this->session->flashdata('err_messages')){  ?>
                    <div class="alert alert-warning alert-dismissible" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong><?php echo $this->session->flashdata('err_messages'); ?>
                    </div>
               <?php
                                                                    $this->session->set_flashdata('messages', '');
                                                                    } ?>


        </div>


        <?php if(in_array('createCompliences', $user_permission)): ?>
          <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">Add Compliences</button>
          <br /> <br />
        <?php endif; ?>

        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Manage Compliences</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">





          <form id="report_form">
            <div class="row">

                  <div class="col-md-2">
                        <div class="form-group">
                            <label for="contact">Compliences Head</label>
                            <select class="form-control" id="filter_compliences_heads_id" name="filter_compliences_heads_id">
                              <option value=''>-select-</option>
                              <?php if($complience_heads){ 
                                foreach($complience_heads as $row){
                                ?>
                                  <option <?php if($filter_compliences_heads_id==$row['id']){ echo "selected";} ?> value='<?php echo $row['id'];  ?>'><?php echo $row['name'];  ?></option>
                                <?php }} ?>
                            </select>
                          
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="contact">Compliences Service Type</label>
                            <select class="form-control" id="filter_compliences_service_type_id" name="filter_compliences_service_type_id">
                              <option value=''>-select-</option>
                              <?php if($complience_service_types){ 
                                foreach($complience_service_types as $row){
                                ?>
                                  <option <?php if($filter_compliences_service_type_id==$row['id']){ echo "selected";} ?> value='<?php echo $row['id'];  ?>'><?php echo $row['name'];  ?></option>
                                <?php }} ?>
                            </select>
                          
                        </div>
                    </div>

                     <div class="col-md-2">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="filter_service_status" name="filter_service_status">
                                <option value=''>-select-</option>
                              
                                <option <?php if($filter_service_status==1){ echo "selected"; } ?> value='1'>Completed</option>
                            </select>
                          
                        </div>
                    </div>

                  <div class="col-md-4 col-lg-3">
                        <div class="form-group">
                            <label for="contact">From Date</label>
                            <input type="date" class="form-control"  name="filter_from_date" id="filter_from_date" value="<?php echo $filter_from_date;  ?>">
                          
                        </div>
                    </div>

                    <div class="col-md-4 col-lg-3">
                        <div class="form-group">
                            <label for="contact">To Date</label>
                            <input type="date" class="form-control"  name="filter_to_date" id="filter_to_date" value="<?php echo $filter_to_date;  ?>">
                          
                        </div>
                    </div>

                     

                    
                  
              </div>
              <div class="box-footer">
                  <button type="submit" class="btn btn-primary">Search</button>
                  <a href="javascript:void(0)" class="btn btn-warning" onclick="resetSearch()" >Reset</a>
              </div>

              <?php if($is_report_download && $compliences){ ?>
                <div class="box-footer">
                    <a href="javascript:void(0)" class="btn btn-danger" onclick="exportReport()" >Download Report</a>
                </div>
              <?php } ?>
             </form>














              <div class="box-body tableResponsDiv">
                <table id="complience_tbl"  class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Heads</th>
                    <th>Type</th>
                  
                    <th>Start Date</th>
                    <th>Service Date</th>
                    <th>Status</th>
                    <th>Details</th>
                    <!-- <th>Status</th> -->
                    <?php if(in_array('updateCompliences', $user_permission) || in_array('deleteCompliences', $user_permission)): ?>
                      <th>Action</th>
                    <?php endif; ?>
                  </tr>
                  </thead>
                  <tbody>
                    <?php if($compliences){ 
                    foreach($compliences as $row){  
                    ?>
                    <tr id="row_<?php echo $row['id'];  ?>">
                      <td><?php echo $row['compliences_heads_name'];  ?></td>
                      <td><?php echo $row['compliences_service_type_name'];  ?></td>
                    
                      <td><?php echo date("d/m/Y",strtotime($row['start_date']));  ?></td>
                      <td><?php echo date("d/m/Y",strtotime($row['service_date']));  ?></td>

                      <td>
                        <label class="switch">
                          <input <?php if($row['service_status']==1){ echo "checked";} ?> onchange="serviceCompleted(this.value,<?php echo $row['id'];  ?>)" type="checkbox" id="mySwitch_<?php echo $row['id'];  ?>">
                          <span class="slider"></span>
                        </label>
                      </td>




                      <td><?php echo $row['details'];  ?></td>
                      <?php if(in_array('updateCompliences', $user_permission) || in_array('deleteCompliences', $user_permission)): ?>
                    
                    
                        <td>
                        <?php if(in_array('updateCompliences', $user_permission)): ?>
                          <button type="button" class="btn btn-default" onclick="editFunc(<?php echo $row['id'];  ?>)" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i></button>
                        <?php endif; ?>
                        <?php if(in_array('deleteCompliences', $user_permission)): ?>
                          <button type="button" class="btn btn-default" onclick="removeFunc(<?php echo $row['id'];  ?>)" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button></td>
                        <?php endif; ?>
                      </tr>
                    <?php endif; ?>
                    <?php }}else{ ?>
                    <tr>
                      <td colspan="7">No Record Found</td>
                    </tr>

                      <?php } ?>
                  </tbody>

                </table>
              </div>


          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
      <!-- col-md-12 -->
    </div>
    <!-- /.row -->
    

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php if(in_array('createCompliences', $user_permission)): ?>
<!-- create brand modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="addModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Compliences</h4>
      </div>

      <form role="form" action="<?php echo base_url('compliences/create') ?>" method="post" id="createForm" enctype="multipart/form-data">

        <div class="modal-body">

        <div class="row">
           <div class="col-md-6">
              <div class="form-group">
                <label for="name">Compliences Heads <sup>*</sup></label>
                <select class="form-control" name="compliences_heads_id" id="compliences_heads_id">
                  <option value="">-select-</option>
                  <?php if($complience_heads){
                    foreach($complience_heads as $row){
                    ?>
                      <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                    <?php }} ?>
                </select>
              </div>
          </div>

           <div class="col-md-6">
              <div class="form-group">
                <label for="name">Compliences Service Type <sup>*</sup></label>
                <select class="form-control" name="compliences_service_type_id" id="compliences_service_type_id">
                  <option value="">-select-</option>
                  <?php if($complience_service_types){
                    foreach($complience_service_types as $row){
                    ?>
                      <option  value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                    <?php }} ?>
                </select>
              </div>
          </div>


          <div class="col-md-6">
            <div class="form-group">
              <label for="from_date">Start Date<sup>*</sup></label>
              <input type="date" class="form-control" id="start_date" name="start_date" placeholder="Date" autocomplete="off">
            </div>
           </div>


            <div class="col-md-6">
              <div class="form-group">
                <label for="name">Duration<sup>*</sup></label>
                <select class="form-control" name="service_duration" id="service_duration">
                    <option value="">-select-</option>
                    <option value="3">3 Months</option>
                    <option value="6">6 Months</option>
                    <option value="12">12 Months</option>
                </select>
              </div>
          </div>
           

            <div class="col-md-12">
              <div class="form-group">
                <label for="brand_name">Details</label>
                <textarea class="form-control" name="details" placeholder="Enter Details"></textarea>
              </div>
            </div>

      </div>
          
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Add</button>
        </div>

      </form>


    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php endif; ?>

<?php if(in_array('updateCompliences', $user_permission)): ?>
<!-- edit brand modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="editModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Compliences</h4>
      </div>

      <form role="form" action="<?php echo base_url('compliences/update') ?>" method="post" id="updateForm" enctype="multipart/form-data">

        <div class="modal-body">
             

              <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="edit_name">Compliences Heads <sup>*</sup></label>
                        <select class="form-control" name="edit_compliences_heads_id" id="edit_compliences_heads_id">
                          <option value="">--</option>
                          <?php if($complience_heads){
                          foreach($complience_heads as $row){
                          ?>
                              <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                          <?php }} ?>
                        </select>
                      </div>
                    </div>


                     <div class="col-md-6">
                      <div class="form-group">
                        <label for="edit_name">Compliences Service Type<sup>*</sup></label>
                        <select class="form-control" name="edit_compliences_service_type_id" id="edit_compliences_service_type_id">
                          <option value="">--</option>
                          <?php if($complience_service_types){
                          foreach($complience_service_types as $row){
                          ?>
                              <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                          <?php }} ?>
                        </select>
                      </div>
                    </div>

                    
                    
                  
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="edit_to_date">Date <sup>*</sup></label>
                        <input type="date" class="form-control" id="edit_start_date" name="edit_start_date" placeholder="Enter Date" autocomplete="off">
                      </div>
                   </div>


              

                  <div class="col-md-6">
                      <div class="form-group">
                        <label for="name">Duration<sup>*</sup></label>
                        <select class="form-control" name="edit_service_duration" id="edit_service_duration">
                            <option value="">-select-</option>
                            <option value="3">3 Months</option>
                            <option value="6">6 Months</option>
                            <option value="12">12 Months</option>
                        </select>
                      </div>
                  </div>

                

                   <div class="col-md-12">
                    <div class="form-group">
                      <label for="edit_details">Details </label>
                      <textarea class="form-control" id="edit_details" name="edit_details" placeholder="Enter Details" autocomplete="off"></textarea>
                    </div>
                  </div>
              </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>

      </form>


    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php endif; ?>

<?php if(in_array('deleteCompliences', $user_permission)): ?>
<!-- remove brand modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Remove Compliences</h4>
      </div>

      <form role="form" action="<?php echo base_url('compliences/remove') ?>" method="post" id="removeForm">
        <div class="modal-body">
          <p>Do you really want to remove?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>


    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php endif; ?>


<script type="text/javascript">
var manageTable;

 function resetSearch(){
    window.location.replace("<?php echo base_url('compliences'); ?>");
  }

  function exportReport(){
      $("#report_form").attr("action","<?php echo base_url('compliences/export_report'); ?>").submit().attr("action","");
     
  }

  function serviceCompleted(service_status,id)
  {
   

      const checkbox = document.getElementById('mySwitch_'+id);
    

      service_status = checkbox.checked ? 1 : 0;
   

       $.ajax({
          url: '<?php echo base_url('compliences/service_status_update'); ?>',
          type: 'post',
          data: { id:id,service_status:service_status }, 
          dataType: 'json',
          success:function(response) {

           console.log(response)

          
          }
        }); 
  }

  // remove functions 
function removeFunc(id)
{
  
    if(id) {
      $("#removeForm").on('submit', function() {

        var form = $(this);

        // remove the text-danger
        $(".text-danger").remove();

       

        $.ajax({
          url: form.attr('action'),
          type: form.attr('method'),
          data: { id:id }, 
          dataType: 'json',
          success:function(response) {

           

            if(response.success === true) {


                $("#row_"+id).remove();
                $("#messages").html('<div class="alert alert-success alert-dismissible" role="alert">'+
                  '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                  '<strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong>'+response.messages+
                '</div>');

                // hide the modal
                $("#removeModal").modal('hide');

            } else {

              $("#messages").html('<div class="alert alert-warning alert-dismissible" role="alert">'+
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>'+response.messages+
              '</div>'); 
            }
          }
        }); 

        return false;
      });
    }
}


$(document).ready(function() {

    $("#compliencesNav").addClass('active');
    $("#compliencesSubNav").addClass('active');
  
  // initialize the datatable 
 // manageTable = $('#manageTable').DataTable();

  // submit the create from 
  $("#createForm").unbind('submit').on('submit', function() {
    var form = $(this);
 var formData = new FormData(this);
    // remove the text-danger
    $(".text-danger").remove();

    $.ajax({
      url: form.attr('action'),
      type: form.attr('method'),
      data: formData, // /converting the form data into array and sending it to server
      dataType: 'json',
      cache:false,
      contentType: false,
      processData: false,
      success:function(response) {

       // manageTable.ajax.reload(null, false); 
       
        if(response.success === true) {
          $("#messages").html('<div class="alert alert-success alert-dismissible" role="alert">'+
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
            '<strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong>'+response.messages+
          '</div>');


          // hide the modal
          $("#addModal").modal('hide');

          location.reload(); 


          // reset the form
          $("#createForm")[0].reset();
          $("#createForm .form-group").removeClass('has-error').removeClass('has-success');

        } else {

          if(response.messages instanceof Object) {
            $.each(response.messages, function(index, value) {
              var id = $("#"+index);

              id.closest('.form-group')
              .removeClass('has-error')
              .removeClass('has-success')
              .addClass(value.length > 0 ? 'has-error' : 'has-success');
              
              id.after(value);

            });
          } else {
            $("#messages").html('<div class="alert alert-warning alert-dismissible" role="alert">'+
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
              '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>'+response.messages+
            '</div>');
          }
        }
      }
    }); 

    return false;
  });

});

// edit function
function editFunc(id)
{ 
 
  $.ajax({
    url: 'fetchDataById/'+id,
    type: 'post',
    dataType: 'json',
    success:function(response) {
      

      $("#edit_compliences_heads_id").val(response.compliences_heads_id);
      $("#edit_compliences_service_type_id").val(response.compliences_service_type_id);
      $("#edit_start_date").val(response.start_date);
      $("#edit_details").val(response.details);
      $("#edit_service_duration").val(response.service_duration);
      

      // submit the edit from 
      $("#updateForm").unbind('submit').bind('submit', function() {
        var form = $(this);
 var formData = new FormData(this);
        // remove the text-danger
        $(".text-danger").remove();

        $.ajax({
          url: form.attr('action') + '/' + id,
          type: form.attr('method'),
          data: formData, // /converting the form data into array and sending it to server
          dataType: 'json',
          cache:false,
          contentType: false,
          processData: false,
          success:function(response) {

            location.reload(); 
            //manageTable.ajax.reload(null, false); 

            if(response.success === true) {
              $("#messages").html('<div class="alert alert-success alert-dismissible" role="alert">'+
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                '<strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong>'+response.messages+
              '</div>');


              // hide the modal
              $("#editModal").modal('hide');
              // reset the form 
              $("#updateForm .form-group").removeClass('has-error').removeClass('has-success');

            } else {

              if(response.messages instanceof Object) {
                $.each(response.messages, function(index, value) {
                  var id = $("#"+index);

                  id.closest('.form-group')
                  .removeClass('has-error')
                  .removeClass('has-success')
                  .addClass(value.length > 0 ? 'has-error' : 'has-success');
                  
                  id.after(value);

                });
              } else {
                $("#messages").html('<div class="alert alert-warning alert-dismissible" role="alert">'+
                  '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                  '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>'+response.messages+
                '</div>');
              }
            }
          }
        }); 

        return false;
      });

    }
  });
}



</script>

<script>
  const checkbox = document.getElementById('mySwitch');
  const status = document.getElementById('switchStatus');

  checkbox.addEventListener('change', function() {
    status.textContent = checkbox.checked ? 'Switch is ON' : 'Switch is OFF';
  });
</script>

<style>
    .switch {
      position: relative;
      display: inline-block;
      width: 40px;
      height: 22px;
    }

    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      transition: 0.3s;
      border-radius: 22px;
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 16px;
      width: 16px;
      left: 3px;
      bottom: 3px;
      background-color: white;
      transition: 0.3s;
      border-radius: 50%;
    }

    input:checked + .slider {
      background-color: #4caf50;
    }

    input:checked + .slider:before {
      transform: translateX(18px);
    }

    .status {
      margin-top: 8px;
      font-family: Arial, sans-serif;
      font-size: 14px;
    }
  </style>