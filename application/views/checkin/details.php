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
      <li class="active">Stock In Items</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12 col-xs-12">

        <div id="messages"></div>

        <p>
            <b>Date :</b> <?=$checkinDetails['date']?> |
            <b>ID :</b> <?=$checkinDetails['unique_id']?> |
            <b>Contact :</b> <?=$checkinDetails['contact']?> 
        </p>

    

        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Manage Items</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body tableResponsDivCheckin CheckinTblEdit">
            <table id="manageTable" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>ID</th>
                <th>Item</th>
               <!-- <th>Unit</th> -->
                <th>Chamber</th>
                <th>Rack</th>
                <th>Service Charge Per Day</th>
                <th>Initial Stock</th>
                <th>Avl Stock</th>
                <?php if(in_array('updateCheckin', $user_permission) || in_array('deleteCheckin', $user_permission)): ?>
                  <th>Action</th>
                  <th>Action</th>
                <?php endif; ?>
              </tr>
              </thead>
              <tbody>
                <?php
                foreach ($itemList as $key => $value) {
                    ?>  
                        <tr>
                            <td><?=++$key?></td>
                            <td><?=$value['item_name']?></td>
                          <!--  <td><?=$value['unit_code']?></td> -->
                            <td><?=$value['chamber']?></td>
                            <td><?=$value['rack']?></td>
                            <td>₹ <?=$value['price']?></td>
                            <td><?=$value['stock']?> <?=$value['unit_code']?></td>
                            <td><?=$value['avl_stock']?> <?=$value['unit_code']?></td>
                            <td>
                              <button type="button" class="btn btn-primary" onclick="editFunc(<?=$value['id']?>)" data-toggle="modal" data-target="#editModal">Damage</button>
                            </td>
                            <td>
                              <button type="button" class="btn btn-default" onclick="removeFunc(<?=$value['id']?>)" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    <?php
                }
                ?>
                
              </tbody>

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
        <h4 class="modal-title">Item Adjust : 
            <b><span id="item_display"></span></b>
        </h4>
        
      </div>

      <form role="form" action="<?php echo base_url('checkin/adjustItem') ?>" method="post" id="updateForm">

        <div class="modal-body">
          <div id="messages"></div>
          <input type="hidden" name="checkin_id" value="<?=$checkinDetails['unique_id']?>">
          <input type="hidden" name="item_id" id="item_id">
        
          <div class="form-group">
            <label for="stock">Stock <sup>*</sup></label>
            <input type="number" step="any" class="form-control" id="stock" name="stock" placeholder="Enter Stock">
          </div>
          <div class="form-group">
              <label for="store_id">Adjust <sup>*</sup></label>
              <select class="form-control" id="checkout_type" name="checkout_type">
                  <option value="">Select</option>
                  <!-- <option value="checkin">Check In</option> -->
                  <!-- <option value="checkout">Check Out</option> -->
                  <option value="damage">Damage</option>
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

      <form role="form" action="<?php echo base_url('checkin/removeCheckinItem') ?>" method="post" id="removeForm">
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
  
  // initialize the datatable 
  manageTable = $('#manageTable').DataTable({
    // 'ajax': 'fetchData',
    'order': []
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

          //hide table body
          $('#item_table tbody').empty();

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
    url: '<?=base_url('checkin/fetchCheckinItemById')?>/'+id,
    type: 'post',
    dataType: 'json',
    success:function(response) {
      // console.log(response);
      
      $("#item_id").val(response.item_id);
      $("#item_display").text(`${response.name} (${response.unit_code})`);
      

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

            // manageTable.ajax.reload(null, false); 

            if(response.success === true) {
              $("#messages").html('<div class="alert alert-success alert-dismissible" role="alert">'+
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                '<strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong>'+response.messages+
              '</div>');


              // hide the modal
              $("#editModal").modal('hide');
              // reset the form 
              $("#updateForm .form-group").removeClass('has-error').removeClass('has-success');

              setTimeout(function() {
                location.reload();
            }, 2000);

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

          // manageTable.ajax.reload(null, false); 

          if(response.success === true) {
            $("#messages").html('<div class="alert alert-success alert-dismissible" role="alert">'+
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
              '<strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong>'+response.messages+
            '</div>');

            // hide the modal
            $("#removeModal").modal('hide');

            setTimeout(function() {
                location.reload();
            }, 2000);

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
