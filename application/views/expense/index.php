<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manage
      <small>Expense</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Expense</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12 col-xs-12">

        <div id="messages"></div>


        <?php if(in_array('createExpense', $user_permission)): ?>
          <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">Add Expense</button>
          <br /> <br />
        <?php endif; ?>

        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Manage Expense</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">





          <form id="report_form">
            <div class="row">

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="contact">Expense Head</label>
                            <select class="form-control" id="filter_expense_heads_id" name="filter_expense_heads_id">
                              <option value=''>-select-</option>
                              <?php if($expense_heads){ 
                                foreach($expense_heads as $row){
                                ?>
                                  <option <?php if($filter_expense_heads_id==$row['id']){ echo "selected";} ?> value='<?php echo $row['id'];  ?>'><?php echo $row['name'];  ?></option>
                                <?php }} ?>
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

              <?php if($is_report_download && $expenses){ ?>
                <div class="box-footer">
                    <a href="javascript:void(0)" class="btn btn-danger" onclick="exportReport()" >Download Report</a>
                </div>
              <?php } ?>
             </form>













            <div class="box-body tableResponsDiv ">
              <table id="expense-tbl"  class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Expense Heads</th>
                  <th>Expense Amount</th>
                  <th>From Date</th>
                  <th>To Date</th>
                  <th>File</th>
                  <th>Details</th>
                  <!-- <th>Status</th> -->
                  <?php if(in_array('updateExpense', $user_permission) || in_array('deleteExpense', $user_permission)): ?>
                    <th>Action</th>
                  <?php endif; ?>
                </tr>
                </thead>
                <tbody>
                  <?php if($expenses){ 
                  foreach($expenses as $row){  
                  ?>
                  <tr>
                    <td><?php echo $row['expense_heads_name'];  ?></td>
                    <td>₹ <?php echo $row['amount'];  ?></td>
                    <td><?php echo date("d/m/Y",strtotime($row['from_date']));  ?></td>
                    <td><?php echo date("d/m/Y",strtotime($row['to_date']));  ?></td>
                    <td><?php if($row['receipt_file']){ ?><a href="<?php echo base_url($row['receipt_file']); ?>" target="_blank">View / Download File</a><?php }else{ echo "--";} ?></td>
                    <td><?php echo $row['details'];  ?></td>
                    <?php if(in_array('updateExpense', $user_permission) || in_array('deleteExpense', $user_permission)): ?>
                    <td>
                      <?php if(in_array('updateExpense', $user_permission)): ?>
                        <button type="button" class="btn btn-default" onclick="editFunc(<?php echo $row['id'];  ?>)" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i></button>
                      <?php endif; ?>
                      <?php if(in_array('deleteExpense', $user_permission)): ?>
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

<?php if(in_array('createExpense', $user_permission)): ?>
<!-- create brand modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="addModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Expense</h4>
      </div>

      <form role="form" action="<?php echo base_url('expense/create') ?>" method="post" id="createForm" enctype="multipart/form-data">

        <div class="modal-body">

        <div class="row">
           <div class="col-md-6">
              <div class="form-group">
                <label for="name">Expense Heads <sup>*</sup></label>
                <select class="form-control" name="expense_heads_id" id="expense_heads_id">
                  <option value="">-select-</option>
                  <?php if($expense_heads){
                    foreach($expense_heads as $row){
                    ?>
                      <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                    <?php }} ?>
                </select>
              </div>
          </div>

           <div class="col-md-6">
            <div class="form-group">
              <label for="amount">Expense Amount <sup>*</sup></label>
              <input type="amount" class="form-control" id="amount" name="amount" placeholder="Enter Expense Amount" autocomplete="off">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="from_date">From Date <sup>*</sup></label>
              <input type="date" class="form-control" id="from_date" name="from_date" placeholder="Enter From Date" autocomplete="off">
            </div>
           </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="to_date">To Date <sup>*</sup></label>
                <input type="date" class="form-control" id="to_date" name="to_date" placeholder="Enter To Date" autocomplete="off">
              </div>
           </div>

           <div class="col-md-12">
              <div class="form-group">
                <label for="reciept_file">Upload A File <sup>*</sup></label>
                <input type="file" class="form-control" id="reciept_file" name="reciept_file">
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
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>

      </form>


    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php endif; ?>

<?php if(in_array('updateExpense', $user_permission)): ?>
<!-- edit brand modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="editModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Expense</h4>
      </div>

      <form role="form" action="<?php echo base_url('expense/update') ?>" method="post" id="updateForm" enctype="multipart/form-data">

        <div class="modal-body">
              <div id="messages"></div>

              <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="edit_name">Expense Heads <sup>*</sup></label>
                        <select class="form-control" name="edit_expense_heads_id" id="edit_expense_heads_id">
                          <option value="">--</option>
                          <?php if($expense_heads){
                          foreach($expense_heads as $row){
                          ?>
                              <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                          <?php }} ?>
                        </select>
                      </div>
                    </div>
                     <div class="col-md-6">
                      <div class="form-group">
                        <label for="edit_amount">Expense Amount <sup>*</sup></label>
                        <input type="text" class="form-control" id="edit_amount" name="edit_amount" placeholder="Enter Expense Amount" autocomplete="off">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="edit_from_date">From Date <sup>*</sup></label>
                        <input type="date" class="form-control" id="edit_from_date" name="edit_from_date" placeholder="Enter From Date" autocomplete="off">
                      </div>
                   </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="edit_to_date">To Date <sup>*</sup></label>
                        <input type="date" class="form-control" id="edit_to_date" name="edit_to_date" placeholder="Enter To Date" autocomplete="off">
                      </div>
                   </div>

                  <div class="col-md-12">
                      <div class="form-group">
                        <label for="edit_reciept_file">Upload A File <sup>*</sup></label>
                        <input type="file" class="form-control" id="edit_reciept_file" name="edit_reciept_file">
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

<?php if(in_array('deleteExpense', $user_permission)): ?>
<!-- remove brand modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Remove Expense</h4>
      </div>

      <form role="form" action="<?php echo base_url('expense/remove') ?>" method="post" id="removeForm">
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
    window.location.replace("<?php echo base_url('expense'); ?>");
  }

  function exportReport(){
      $("#report_form").attr("action","<?php echo base_url('expense/export_report'); ?>").submit().attr("action","");
     
  }

$(document).ready(function() {
    $("#mainExpenseNav").addClass('active');
  $("#expenseNav").addClass('active');
  
  // initialize the datatable 
  manageTable = $('#manageTable').DataTable({
    'ajax': 'fetchData',
    'order': []
  });

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
      

      $("#edit_expense_heads_id").val(response.expense_heads_id);
      $("#edit_amount").val(response.amount);
      $("#edit_from_date").val(response.from_date);
      $("#edit_to_date").val(response.to_date);
      $("#edit_details").val(response.details);

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
