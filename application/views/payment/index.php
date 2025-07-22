<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manage
      <small>Income</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
      <li class="active">Income</li>
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
            <h3 class="box-title">Manage Income</h3>
          </div>
          <!-- /.box-header -->

          <div class="box-body">


          <form id="report_form">
            <div class="row">
            <input type="hidden" class="form-control" value="1" placeholder="" name="filter_submit" id="filter_submit">
            <div class="col-md-4">
            <div class="form-group">
            <label for="contact">Query</label>
            <input type="text" class="form-control" value="<?php echo $filter_q;  ?>" placeholder="Serach by / ID/  Contact Name/Email/Phone /Item Name " name="filter_q" id="filter_q">

            </div>
            </div>

            <div class="col-md-2">
            <div class="form-group">
            <label for="contact">Contact</label>
            <select class="form-control" id="filter_contact_id" name="filter_contact_id">
            <option value="">Select</option>
              <?php if($contactList){
              foreach($contactList as $row){  
              ?>
              <option value="<?php echo $row['id']; ?>" <?php if($filter_contact_id==$row['id']){ echo "selected"; }  ?>><?php echo $row['name']; ?></option>
              <?php }} ?>
            </select>
            </div>
            </div>
            </div>
            <div class="row">

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

             <?php if($filter_submit && $is_report_download && $reports){ ?>
              <div class="box-footer">
                  <a href="javascript:void(0)" class="btn btn-danger" onclick="exportIncome()" >Download Report</a>
              </div>
             <?php } ?>

          </form>

          </div>
          <div class="box-body tableResponsDiv ">
            <table id="income-tbl" class="table table-bordered table-striped">
              <thead>
                <tr>
                    <th>Id</th>
                    <th>Date</th>
                    <th>Contact</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Pay. Mode</th>
                    <th>Amount</th>
                    <th>Invoice</th>
                </tr>
              </thead>
              <tbody>
                <?php if($reports){ 
                foreach($reports as $row){  
                ?>
                <tr>
                    <td><?php echo $row['checkout_id'];  ?></td>
                    <td><?php echo date("d/m/Y",strtotime($row['payment_date']));  ?></td>
                    <td><?php echo $row['customer_name'];  ?></td>
                    <td><?php echo $row['customer_phone'];  ?></td>
                    <td><?php echo $row['customer_email'];  ?></td>
                    <td>
                      <span class="badge badge-success"><?php echo $row['payment_type'];  ?></span>
                    </td>
                    <td>
                      <span class="badge badge-success">₹<?php echo $row['amount'];  ?></span>
                    </td>
                    <td><a href="<?=base_url('payment/invoice/'.$row['id'])?>" target="_blank"><button type="button" class="btn btn-default"><i class="glyphicon glyphicon-print"></i></button></a></td>
                </tr>
                <?php }}else{ ?>

                  <tr>
                      <td colspan="9">No Record Found</td>
                </tr>

                <?php } ?>
              </tbody>
            </table>
             <div><?php echo $pagination; ?></div>
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
    $("#paymentNav").addClass('active');
  });

  function resetSearch(){
    window.location.replace("<?php echo base_url('payment'); ?>");
  }

  function exportIncome(){
       
      $("#report_form").attr("action","<?php echo base_url('payment/exportPayment'); ?>").submit().attr("action","");
     
    
  }
</script>
