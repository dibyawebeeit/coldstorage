<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Checkin
      <small>Items</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Checkin Items</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12 col-xs-12">

        <div id="messages"></div>


        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Checkin Items</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body tableResponsDivProItem tableResponsDivProItemEdit">
            <table id="manageTable" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>Sl</th>
                <th>Item</th>
                <th>Contact</th>
                <th>Stock</th>
                <th>Service Charge Per Day</th>
                <th>Chamber</th>
                <th>Rack</th>
                <th>Date</th>
              </tr>
              </thead>
              <tbody>
                <?php
                foreach ($itemList as $key => $value) {
                    ?>  
                        <tr>
                            <td><?=++$key?></td>
                            <td><?=$value['item_name']?></td>
                            <td><?=$value['contact_name']?></td>
                            <td><?=$value['stock']?> <?=$value['unit_code']?></td>
                            <td>₹ <?=$value['price']?></td>
                            <td><?=$value['chamber']?></td>
                            <td><?=$value['rack']?></td>
                            <td><?=$value['checkin_date']?></td>
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







<script type="text/javascript">
var manageTable;

$(document).ready(function() {
  $("#itemNav").addClass('active');
  $("#mainProductNav").addClass('active');
  
  // initialize the datatable 
  manageTable = $('#manageTable').DataTable({
    // 'ajax': 'fetchData',
    'order': [],
    dom: 'Bfrtip', // enables button layout
    buttons: [
      'copy', 'csv', 'excel'
    ]
  });


});


</script>
