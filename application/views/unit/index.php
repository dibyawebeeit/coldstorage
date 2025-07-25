<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manage
      <small>Unit</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Unit</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12 col-xs-12">

        <div id="messages"></div>


        <?php if(in_array('createUnit', $user_permission)): ?>
          <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">Add Unit</button>
          <br /> <br />
        <?php endif; ?>

        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Manage Unit</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body tableResponsDiv2">
            <table id="manageTable" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>Sl</th>
                <th>Name</th>
                <th>Code</th>
                <th>Base Unit</th>
                <th>Formula</th>
                <!-- <th>Status</th> -->
                <?php if(in_array('updateUnit', $user_permission) || in_array('deleteUnit', $user_permission)): ?>
                  <th>Action</th>
                <?php endif; ?>
              </tr>
              </thead>

            </table>
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

<?php if(in_array('createUnit', $user_permission)): ?>
<!-- create brand modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="addModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Unit</h4>
      </div>

      <form role="form" action="<?php echo base_url('unit/create') ?>" method="post" id="createForm">

        <div class="modal-body">

          <div class="form-group">
            <label for="name">Name <sup>*</sup></label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" autocomplete="off">
          </div>
          <div class="form-group">
            <label for="code">Code <sup>*</sup></label>
            <input type="text" class="form-control" id="code" name="code" placeholder="Enter Code" autocomplete="off">
          </div>
          <div class="form-group">
            <label for="base_unit_id">Base Unit </label>
            <select class="form-control" name="base_unit_id" autocomplete="off">
              <option value="">Select</option>
              <?php
              foreach ($baseUnit as $value) {
                ?>
                <option value="<?=$value['id']?>"><?=$value['name']?></option>
                <?php
              }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label for="operator">Operator </label>
            <select class="form-control" name="operator" autocomplete="off">
              <option value="">Select</option>
              <option value="*">Multiply</option>
              <option value="/">Divide</option>
              <option value="+">Plus</option>
              <option value="-">Minus</option>
            </select>
          </div>
          <div class="form-group">
            <label for="operation_value">Operation Value </label>
            <input type="number" step="any" class="form-control" name="operation_value" placeholder="Operation Value" autocomplete="off">
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

<?php if(in_array('updateUnit', $user_permission)): ?>
<!-- edit brand modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="editModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Unit</h4>
      </div>

      <form role="form" action="<?php echo base_url('unit/update') ?>" method="post" id="updateForm">

        <div class="modal-body">
          <div id="messages"></div>

          <div class="form-group">
            <label for="edit_name">Name <sup>*</sup></label>
            <input type="text" class="form-control" id="edit_name" name="edit_name" placeholder="Enter Name" autocomplete="off">
          </div>
          <div class="form-group">
            <label for="edit_code">Code <sup>*</sup></label>
            <input type="text" class="form-control" id="edit_code" name="edit_code" placeholder="Enter Code" autocomplete="off">
          </div>
          <div class="form-group">
            <label for="edit_base_unit_id">Base Unit </label>
            <select class="form-control" id="edit_base_unit_id" name="edit_base_unit_id">
              <option value="">Select</option>
              <?php
              foreach ($baseUnit as $value) {
                ?>
                <option value="<?=$value['id']?>"><?=$value['name']?></option>
                <?php
              }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label for="edit_operator">Operator </label>
            <select class="form-control" id="edit_operator" name="edit_operator">
              <option value="">Select</option>
              <option value="*">Multiply</option>
              <option value="/">Divide</option>
              <option value="+">Plus</option>
              <option value="-">Minus</option>
            </select>
          </div>
          <div class="form-group">
            <label for="edit_operation_value">Operation Value </label>
            <input type="number" step="any" class="form-control" id="edit_operation_value" name="edit_operation_value" placeholder="Operation Value">
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

<?php if(in_array('deleteUnit', $user_permission)): ?>
<!-- remove brand modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Remove Unit</h4>
      </div>

      <form role="form" action="<?php echo base_url('unit/remove') ?>" method="post" id="removeForm">
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

$(document).ready(function() {
  $("#unitNav").addClass('active');
  $("#mainManagementNav").addClass('active');
  
  // initialize the datatable 
  manageTable = $('#manageTable').DataTable({
    'ajax': 'fetchData',
    'order': [],
    dom: 'Bfrtip', // enables button layout
    buttons: [
      'copy', 'csv', 'excel'
    ]
  });

  // submit the create from 
  $("#createForm").unbind('submit').on('submit', function() {
    var form = $(this);

    // remove the text-danger
    $(".text-danger").remove();

    $.ajax({
      url: form.attr('action'),
      type: form.attr('method'),
      data: form.serialize(), // /converting the form data into array and sending it to server
      dataType: 'json',
      success:function(response) {

        manageTable.ajax.reload(null, false); 

        if(response.success === true) {
          $("#messages").html('<div class="alert alert-success alert-dismissible" role="alert">'+
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
            '<strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong>'+response.messages+
          '</div>');


          // hide the modal
          $("#addModal").modal('hide');

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
      
      $("#edit_name").val(response.name);
      $("#edit_code").val(response.code);
      $("#edit_operator").val(response.operator);
      $("#edit_operation_value").val(response.operation_value);
      $("#edit_base_unit_id").val(response.base_unit_id);

      // submit the edit from 
      $("#updateForm").unbind('submit').bind('submit', function() {
        var form = $(this);
        

        // remove the text-danger
        $(".text-danger").remove();

        $.ajax({
          url: form.attr('action') + '/' + id,
          type: form.attr('method'),
          data: form.serialize(), // /converting the form data into array and sending it to server
          dataType: 'json',
          success:function(response) {

            manageTable.ajax.reload(null, false); 

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

          manageTable.ajax.reload(null, false); 

          if(response.success === true) {
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


</script>
