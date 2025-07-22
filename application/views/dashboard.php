  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Reports At A Glance
        <!-- <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <?php if($is_admin == true): ?>

        <div class="row dashboardColor-row ">
          
        
          <!-- ./col -->
          <div class="col-lg-4 col-xs-6 dashboardColorBox">
            <!-- small box -->
            <div class="dashboardColorBox-inr">
              <div class="dashColorBox-heading">
                <h3><span>&#8377;</span> <?php echo $current_month_total_income; ?></h3>
                <p>Total Income This Month</p>
              </div>
              <div class="dashColorBox-content">
              
              	<div class="dashColorBox-tbl">
                	<div class="dashColorBox-tr">
                    	<div class="dashColorBox-td">Paid</div>
                    	<div class="dashColorBox-td"><span>&#8377;</span>  <?php echo $current_month_total_paid_income; ?></div>
                    </div>                
                	<div class="dashColorBox-tr">
                    	<div class="dashColorBox-td">Unpaid</div>
                    	<div class="dashColorBox-td"><span>&#8377;</span>  <?php echo $current_month_unpaid_income; ?></div>
                    </div>                 
                           
                </div>
                
              </div>              
            </div>
          </div>

          <div class="col-lg-4 col-xs-6 dashboardColorBox">
            <!-- small box -->
            <div class="dashboardColorBox-inr">
              <div class="dashColorBox-heading">
                <h3><span>&#8377;</span> <?php echo $current_month_expenses; ?></h3>
                <p>Total Expenses This Month</p>
              </div>
              <div class="dashColorBox-content">
              
              	<div class="dashColorBox-tbl">
                             
                	<div class="dashColorBox-tr">
                    	<div class="dashColorBox-td">Last Month</div>
                    	<div class="dashColorBox-td"><span>&#8377;</span>  <?php echo $last_month_expenses; ?></div>
                    </div>                 
                	<div class="dashColorBox-tr">
                    	<div class="dashColorBox-td">This Year</div>
                    	<div class="dashColorBox-td"><span>&#8377;</span> <?php echo $this_year_expenses; ?></div>
                    </div>               
                </div>
                
              </div>              
            </div>
          </div>
          
          <div class="col-lg-4 col-xs-6 dashboardColorBox">
            <!-- small box -->
            <div class="dashboardColorBox-inr">
              <div class="dashColorBox-heading">
                <h3><?php echo $current_month_total_stock_stored; ?> KG</h3>
                <p>Storage This Month</p>
              </div>
              <div class="dashColorBox-content">
              
              	<div class="dashColorBox-tbl">
                	<div class="dashColorBox-tr">
                    	<div class="dashColorBox-td">Stock In </div>
                    	<div class="dashColorBox-td"><?php echo $current_month_stock_in; ?> KG</div>
                    </div>                
                	<div class="dashColorBox-tr">
                    	<div class="dashColorBox-td">Stock Out</div>
                    	<div class="dashColorBox-td"><?php echo $current_month_total_stock_out; ?> KG</div>
                    </div>                 
                	              
                </div>
                
              </div>              
            </div>
          </div>
          
          <div class="col-lg-4 col-xs-6 dashboardColorBox">
            <!-- small box -->
            <div class="dashboardColorBox-inr">
              <div class="dashColorBox-heading">
                <h3><?php echo $total_contacts; ?></h3>
                <p>Total Customers</p>
              </div>
              <div class="dashColorBox-content">
              
              	<div class="dashColorBox-tbl">
                   <div class="dashColorBox-tr">
                    	<div class="dashColorBox-td">Today</div>
                    	<div class="dashColorBox-td"><?php echo $today_total_contacts; ?></div>
                    </div>  
                	  <div class="dashColorBox-tr">
                    	<div class="dashColorBox-td">This Month</div>
                    	<div class="dashColorBox-td"><?php echo $current_month_contacts; ?></div>
                    </div>  

                	  <div class="dashColorBox-tr">
                    	<div class="dashColorBox-td">Last Month</div>
                    	<div class="dashColorBox-td"><?php echo $last_month_contacts; ?></div>
                    </div>  

                	  <div class="dashColorBox-tr">
                    	<div class="dashColorBox-td">This Year</div>
                    	<div class="dashColorBox-td"><?php echo $this_year_contacts; ?></div>
                    </div>               
                </div>
                
              </div>              
            </div>
          </div>

          <div class="col-lg-4 col-xs-6 dashboardColorBox">
            <!-- small box -->
            <div class="dashboardColorBox-inr">
              <div class="dashColorBox-heading">
                <h3> <?php echo $total_categories; ?></h3>
                <p>Total Categories</p>
              </div>
              <div class="dashColorBox-content">
              
              	<div class="dashColorBox-tbl">
                	<!--div class="dashColorBox-tr">
                    	<div class="dashColorBox-td">Kolkata</div>
                    	<div class="dashColorBox-td"><span>&#8377;</span> 696528.00</div>
                    </div>                
                	<div class="dashColorBox-tr">
                    	<div class="dashColorBox-td">Store 1</div>
                    	<div class="dashColorBox-td"><span>&#8377;</span> 0.00</div>
                    </div>                 
                	<div class="dashColorBox-tr">
                    	<div class="dashColorBox-td">Store 2</div>
                    	<div class="dashColorBox-td"><span>&#8377;</span> 0.00</div>
                    </div-->               
                </div>
                
              </div>              
            </div>
          </div>

          

          <div class="col-lg-4 col-xs-6 dashboardColorBox">
            <!-- small box -->
            <div class="dashboardColorBox-inr">
              <div class="dashColorBox-heading">
                <h3> <?php echo $current_month_renewal; ?></h3>
                <p>Upcoming Renewal This Month</p>
              </div>
              <div class="dashColorBox-content">
              
              	<div class="dashColorBox-tbl">
                	               
                	<div class="dashColorBox-tr">
                    	<div class="dashColorBox-td">Last Month</div>
                    	<div class="dashColorBox-td"><?php echo $last_month_renewal; ?></div>
                    </div>                 
                	<div class="dashColorBox-tr">
                    	<div class="dashColorBox-td">This Year</div>
                    	<div class="dashColorBox-td"><?php echo $this_year_renewal; ?></div>
                    </div>               
                </div>
                
              </div>              
            </div>
          </div>

          <div class="col-lg-4 col-xs-6 dashboardColorBox">
            <!-- small box -->
            <div class="dashboardColorBox-inr">
              <div class="dashColorBox-heading">
                <h3> <?php echo $current_month_service; ?></h3>
                <p>Upcoming Service This Month</p>
              </div>
              <div class="dashColorBox-content">
              
              	<div class="dashColorBox-tbl">
                              
                	<div class="dashColorBox-tr">
                    	<div class="dashColorBox-td">Last Month</div>
                    	<div class="dashColorBox-td"><?php echo $last_month_service; ?></div>
                    </div>                 
                	<div class="dashColorBox-tr">
                    	<div class="dashColorBox-td">This Year</div>
                    	<div class="dashColorBox-td"> <?php echo $this_year_service; ?></div>
                    </div>               
                </div>
                
              </div>              
            </div>
          </div>

          

          <div class="col-lg-4 col-xs-6 dashboardColorBox">
            <!-- small box -->
            <div class="dashboardColorBox-inr">
              <div class="dashColorBox-heading">
                <h3><?php echo $storage_capacity; ?> KG</h3>
                <p>Storage Capacity</p>
              </div>
              <div class="dashColorBox-content">
              
              	<div class="dashColorBox-tbl">
                   
                	  <div class="dashColorBox-tr">
                    	<div class="dashColorBox-td">Total Storage</div>
                    	<div class="dashColorBox-td"> <?php echo $total_storage_till_date; ?> KG</div>
                    </div>    

                	  <div class="dashColorBox-tr">
                    	<div class="dashColorBox-td">Available Storage Capacity</div>
                    	<div class="dashColorBox-td"><?php echo $available_storage_capacity; ?> KG</div>
                    </div>                 
                	             
                </div>
                
              </div>              
            </div>
          </div>

          

          

           

           

           
         
        </div>
        <!-- /.row -->
      <?php endif; ?>
      

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <script type="text/javascript">
    $(document).ready(function() {
      $("#dashboardMainMenu").addClass('active');
    }); 
  </script>
