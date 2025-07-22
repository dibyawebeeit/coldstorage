<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manage
      <small>Stock Out</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Stock Out</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12 col-xs-12">

        <div id="messages"></div>

        <?php if(in_array('createCheckout', $user_permission)): ?>
          <a href="<?=base_url('checkout/create')?>">
            <button class="btn btn-primary">Create New Stock Out</button>
          </a>
          <br /> <br />
        <?php endif; ?>

        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Manage Stock Out</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body tableResponsDivCheckout">
            <table id="manageTable" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>ID</th>
                <th>Stock Out ID</th>
                <th>Stock In Date</th>
                <th>Stock Out Date</th>
                <th>Customer</th>
                <th>Bill Amount</th>
                <th>Paid Amount</th>
                <th>Deduction Amount</th>
                
                <?php if(in_array('updateCheckout', $user_permission) || in_array('deleteCheckout', $user_permission)): ?>
                  <th>Payment Status</th>
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


<!-- payment modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="paymentModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Pay Now :
          <b><span id="checkout_unique_id"></span></b>
        </h4>
      </div>

      <form role="form" action="<?php echo base_url('checkout/payment') ?>" method="post" id="paymentForm">

        <div class="modal-body">
          <div id="messages"></div>

          <input type="hidden" id="checkoutId" name="checkoutId">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="payment_date">Date <sup>*</sup></label>
                <input type="date" class="form-control" id="payment_date" name="payment_date" autocomplete="off">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="payment_type">Payment Type </label>
                <input type="text" class="form-control" name="payment_type" placeholder="eg: cash, cheque etc." autocomplete="off">
              </div>
            </div>
          </div>
          
          <div class="form-group">
            <label for="store_cost">Store Cost (₹)<sup>*</sup></label>
            <input type="number" step="any" class="form-control" id="store_cost" name="store_cost" placeholder="Store Cost" min="1" autocomplete="off">
          </div>
          <div class="form-group">
            <label for="transportation_cost">Transportation Cost (₹)<sup>*</sup></label>
            <input type="number" step="any" class="form-control" id="transportation_cost" name="transportation_cost" min="0" placeholder="Amount" autocomplete="off">
          </div>
          <button class="btn btn-info" id="add_deduction" type="button">Add Deduction</button>

          <div id="deductions_container">
          </div>

          <div class="form-group">
            <label for="gst_amount">GST (₹)<sup>*</sup></label>
            <input type="hidden" id="gst_percent">
            <input type="number" step="any" class="form-control" id="gst_amount" name="gst_amount" placeholder="GST" readonly>
          </div>
          <div class="form-group">
            <label for="total_amount">Total Amount (₹)<sup>*</sup></label>
            <input type="number" step="any" class="form-control" id="total_amount" name="total_amount" placeholder="Total Amount" readonly>
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



<script type="text/javascript">
var manageTable;

$(document).ready(function() {
  $("#mainStockNav").addClass('active');
  $("#checkoutNav").addClass('active');
  
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


function paymentFunc(button)
{ 

  const checkoutId = button.getAttribute('data-checkoutid');
  const dataAmount = button.getAttribute('data-amount');
  const dataGst = button.getAttribute('data-gst');
  const id = button.getAttribute('data-id');

  const gstAmount = parseFloat(((dataAmount * dataGst) / 100).toFixed(2));
  const transportaionCost = 0.00;
  const totalAmount = parseFloat(dataAmount) + parseFloat(gstAmount) + parseFloat(transportaionCost);

  $("#checkout_unique_id").text(checkoutId);
  $("#checkoutId").val(checkoutId);

  store_cost.value = dataAmount;
  gst_amount.value = gstAmount;
  gst_percent.value = dataGst;
  transportation_cost.value = transportaionCost;
  total_amount.value = totalAmount;

  document.getElementById('store_cost').setAttribute('max', dataAmount);

  // submit payment
  $("#paymentForm").unbind('submit').bind('submit', function() {
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
          $("#paymentModal").modal('hide');
          // reset the form 
          $("#paymentForm")[0].reset();
          $("#paymentForm .form-group").removeClass('has-error').removeClass('has-success');

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


document.addEventListener('DOMContentLoaded', function () {
  const addBtn = document.getElementById('add_deduction');
  const container = document.getElementById('deductions_container');
  const store_cost = document.getElementById("store_cost");
  const gst_amount = document.getElementById("gst_amount");
  const transportation_cost = document.getElementById("transportation_cost");
  const total_amount = document.getElementById("total_amount");
  const gst_percent = document.getElementById("gst_percent");

  function calculateTotal() {
    const storeCost = parseFloat(store_cost.value) || 0;
    const transportationCost = parseFloat(transportation_cost.value) || 0;
    const gstPercent = parseFloat(gst_percent.value) || 0;

    let totalDeduction = 0;

    document.querySelectorAll('input[name="deduct_amount[]"]').forEach(input => {
      const val = parseFloat(input.value) || 0;
      totalDeduction += val;
    });

    const gstAmount = parseFloat(((storeCost * gstPercent) / 100).toFixed(2));
    const total = (storeCost + gstAmount + transportationCost + totalDeduction);

    gst_amount.value = gstAmount.toFixed(2);
    total_amount.value = total.toFixed(2);
  }

  // Attach listeners to static fields
  store_cost.addEventListener('input', calculateTotal);
  transportation_cost.addEventListener('input', calculateTotal);

  // Add deduction row
  addBtn.addEventListener('click', function () {
    const deductionHTML = `
      <div class="deduct_section">
        <div class="row mt-2">
          <div class="col-md-5">
            <div class="form-group">
              <label>Deduct Reason<sup>*</sup></label>
              <input type="text" class="form-control" name="deduct_reson[]" placeholder="Type reason" autocomplete="off" required>
            </div>
          </div>
          <div class="col-md-5">
            <div class="form-group">
              <label>Deduct Amount (₹)<sup>*</sup></label>
              <input type="number" step="any" class="form-control deduction-input" name="deduct_amount[]" placeholder="Amount" min="0" autocomplete="off" required>
            </div>
          </div>
          <div class="col-md-2">
            <button type="button" class="btn btn-danger remove-deduction"><i class="fa fa-trash"></i></button>
          </div>
        </div>
      </div>`;

    container.insertAdjacentHTML('beforeend', deductionHTML);
    attachDeductionListeners(); // Attach event to new inputs
  });

  // Remove deduction section
  container.addEventListener('click', function (e) {
    if (e.target.closest('.remove-deduction')) {
      const section = e.target.closest('.deduct_section');
      if (section) {
        section.remove();
        calculateTotal(); // Recalculate after removal
      }
    }
  });

  // Attach input listener to all deduction amount fields
  function attachDeductionListeners() {
    const inputs = document.querySelectorAll('input[name="deduct_amount[]"]');
    inputs.forEach(input => {
      input.removeEventListener('input', calculateTotal); // avoid duplicate listeners
      input.addEventListener('input', calculateTotal);
    });
  }

  // Initial call for static page load
  attachDeductionListeners();
});



</script>
