<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      New
      <small>Stock Out</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">New Stock Out</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12 col-xs-12">

        <div id="messages"></div>

          <form id="report_form">
              <div class="row">
                  <div class="col-md-3">
                      <div class="form-group">
                        <label for="contact">Stockin ID or Mobile No</label>

                        <?php if($this->session->userdata('checkout_contact_id')): ?>
                          <input type="text" class="form-control" value="<?= $this->session->userdata('searchData') ?>" placeholder="Enter Checkin ID / Mobile No" disabled >
                        <?php else: ?>
                          <input type="text" class="form-control" placeholder="Enter Stockin ID / Mobile No" id="checkin_id" >
                        <?php endif; ?>
                        
                        <span id="errorspan"></span>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div style="margin-top: 25px;">
                        <?php if($this->session->userdata('checkout_contact_id')): ?>
                          <a href="<?=base_url('checkout/reset_checkin_id')?>" class="btn btn-warning">Reset All</a>
                        <?php else: ?>
                          <button type="button" class="btn btn-primary" id="proceedBtn">Proceed</button>
                        <?php endif; ?>
                        
                        
                      </div>
                  </div>
              </div>
          </form>

          <?php if($this->session->userdata('checkout_contact_id')): ?>
          <div class="box">
              <div class="box-header">
                <h3 class="box-title">All Stock In List</h3>
              </div>
                <div class="box-body">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Stock In ID</th>
                        <th>Checkin Date</th>
                        <th>Action</th>

                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($checkinList as $key => $value): ?>  
                        <tr>
                          <td><?=$value['unique_id']?></td>
                          <td><?=$value['date']?></td>
                          <td>
                            <button class="btn btn-info" type="button" onclick="proceedCheckinId(<?=$value['unique_id']?>)" 
                            <?=$this->session->userdata('selected_checkin_id')==$value['unique_id']?'disabled':''?>
                            >Proceed</button>
                          </td>
                          <!-- <td>-</td> -->
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
          </div>
          <?php endif; ?>

        <?php if($this->session->userdata('selected_checkin_id')): ?>
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Manage Items : 
            <span style="background-color: yellow;padding-left:5px;padding-right:5px;"> <?=$this->session->userdata('selected_checkin_id')?> </span>
            </h3>
            
          </div>
          <form id="checkoutForm" method="post" action="<?= base_url('checkout/checkout_submit') ?>">
            <div class="box-body">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Item</th>
                    <th>Service Charge Per Day</th>
                    <th>Initial Stock</th>
                    <th>Avl Stock</th>
                    <th>Checkout Qty</th>

                    <!-- <?php //if(in_array('updateCheckout', $user_permission) || in_array('deleteCheckout', $user_permission)): ?>
                      <th>Action</th>
                    <?php //endif; ?> -->

                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($checkin_item_list as $key => $value): ?>  
                    <tr>
                      <td>
                        <input type="checkbox" name="selected_items[]" value="<?=$value['id']?>">
                        <input type="hidden" name="checkin_item_id[<?=$value['id']?>]" value="<?=$value['id']?>">
                      </td>
                      <td><?=$value['item_name']?></td>
                      <td>₹ <?=$value['price']?></td>
                      <td><?=$value['stock']?> <?=$value['unit_code']?></td>
                      <td><?=$value['avl_stock']?> <?=$value['unit_code']?></td>
                      <td>
                        <input type="number" step="any" name="checkout_qty[<?=$value['id']?>]" placeholder="Qty" value="0">
                      </td>
                      <!-- <td>-</td> -->
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>

              <button type="submit" class="btn btn-success" style="margin-top: 25px;">Check Out</button>
            </div>
          </form>
        </div>
        <!-- /.box -->
         <?php endif; ?>
      </div>
      <!-- col-md-12 -->
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->






<script type="text/javascript">
  var manageTable;
  $(document).ready(function() {
    $("#mainStockNav").addClass('active');
    $("#checkoutNav").addClass('active');
    
    // initialize the datatable 
    manageTable = $('#manageTable').DataTable({
      // 'ajax': 'fetchData',
      'order': []
    });
  });


let proceedBtn = document.querySelector("#proceedBtn");
let checkin_id = document.querySelector("#checkin_id");
let errorspan = document.querySelector("#errorspan");

proceedBtn.addEventListener('click', function (e) {
    e.preventDefault(); // Prevent default form submission

    const checkinValue = checkin_id.value.trim();

    if (checkinValue === '') {
        errorspan.style.color = "red";
        errorspan.textContent = "This field is required";
        return;
    }

    // Send data to backend using fetch
    fetch("<?=base_url('checkout/validate_checkin_id')?>", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json', // CI3 will need to handle JSON
        },
        body: JSON.stringify({ checkin_id: checkinValue })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            errorspan.style.color = "green";
            errorspan.textContent = data.message;
            setTimeout(function() {
                location.reload(); // refreshes the current page
            }, 1000); // 3000 milliseconds = 3 seconds
        } else {
            errorspan.style.color = "red";
            errorspan.textContent = data.message;
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        errorspan.style.color = "red";
        errorspan.textContent = "An error occurred.";
    });
});

function proceedCheckinId(id)
{
  // Send data to backend using fetch
    fetch("<?=base_url('checkout/proceedCheckinId')?>", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json', // CI3 will need to handle JSON
        },
        body: JSON.stringify({ checkin_id: id })
    })
    .then(response => response.json())
    .then(data => {
        setTimeout(function() {
                location.reload(); // refreshes the current page
            }, 1000); // 3000 milliseconds = 3 seconds
    })
    .catch(error => {
        console.error('Fetch error:', error);
        errorspan.style.color = "red";
        errorspan.textContent = "An error occurred.";
    });
}
</script>
<script>
  $('#checkoutForm').on('submit', function(e) {
    e.preventDefault(); // Stop normal form submission

    $.ajax({
      url: $(this).attr('action'),
      method: 'POST',
      data: $(this).serialize(),
      dataType: 'json', // Expect JSON response
      success: function(response) {
        if (response.success) {
          alert(response.messages); // e.g., "Successfully created"
          setTimeout(function() {
              window.location.href = "<?= base_url('checkout/index') ?>";
          }, 1000); // 2000 milliseconds = 2 seconds
        } else {
          alert("Failed: " + response.messages); // e.g., "No items selected."
        }
      },
      error: function(xhr, status, error) {
        alert("An error occurred: " + error);
        // console.error(xhr.responseText);
      }
    });
  });
</script>
