<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manage
      <small>Stock In</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Stock In</li>
    </ol>
  </section>


  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12 col-xs-12">

        <div id="messages"></div>

        <?php if(in_array('createCheckin', $user_permission)): ?>
          <!-- <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">Create New Checkin</button> -->
          <a href="<?=base_url('checkin/create')?>"><button class="btn btn-primary">Create New Stock In</button></a>
          <br /> <br />
        <?php endif; ?>

        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Manage Stock In</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body tableResponsDivCheckin">
            <table id="manageTable" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>Sl</th>
                <th>Stock In ID</th>
                <th>Date</th>
                <th>Customer</th>
                
                <?php if(in_array('updateCheckin', $user_permission) || in_array('deleteCheckin', $user_permission)): ?>
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

   



<?php if(in_array('updateCheckin', $user_permission)): ?>
<!-- edit brand modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="editModal">
 
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Stock In</h4>
      </div>

      <form role="form" action="<?php echo base_url('checkin/update') ?>" method="post" id="updateForm">

        <div class="modal-body">
          <div id="messages"></div>
          <div class="form-group">
              <label for="contact">Contact <sup>*</sup></label>
              <select id="edit_contact_id" name="edit_contact_id" style="width: 300px;">
                <option value="">Select</option>
              <?php
                      foreach ($contactList as $contact) {
                        ?>
                        <option value="<?=$contact['id']?>"><?=$contact['name']?></option>
                        <?php
                      }
                      ?>
              </select>
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

<?php if(in_array('deleteCheckin', $user_permission)): ?>
<!-- remove brand modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Remove Check In</h4>
      </div>

      <form role="form" action="<?php echo base_url('checkin/remove') ?>" method="post" id="removeForm">
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

	$("#mainStockNav").addClass('active');
  $("#checkinNav").addClass('active');

  //  $('#contact_id').select2({
  //   dropdownParent: $('#addModal'),
  //   placeholder: "Select",
  //   allowClear: false
  // });
  
  // initialize the datatable 
  manageTable = $('#manageTable').DataTable({
    'ajax': 'fetchData',
    'order': [],
    dom: 'Bfrtip', // enables button layout
    buttons: [
      'copy', 'csv', 'excel'
    ]
  });

 

});

// edit function
function editFunc(id)
{ 

   $('#edit_contact_id').select2({
    dropdownParent: $('#updateForm'),
    placeholder: "Choose an option",
    allowClear: false
  });

  
  $.ajax({
    url: 'fetchDataById/'+id,
    type: 'post',
    dataType: 'json',
    success:function(response) {
      

      $("#edit_date").val(response.date);
      $("#edit_contact_id").val(response.contact_id);
      $('#edit_contact_id').val(response.contact_id).trigger('change');
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


   $('#mySelect').select2({
      placeholder: "Select a fruit",
      allowClear: true
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








