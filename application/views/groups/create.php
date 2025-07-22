  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Manage
        <small>Roles</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Roles</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-md-12 col-xs-12">
          
          <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <?php echo $this->session->flashdata('success'); ?>
            </div>
          <?php elseif($this->session->flashdata('error')): ?>
            <div class="alert alert-error alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <?php echo $this->session->flashdata('error'); ?>
            </div>
          <?php endif; ?>

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Add Role</h3>
            </div>
            <form role="form" action="<?php base_url('groups/create') ?>" method="post">
              <div class="box-body">

                <?php echo validation_errors(); ?>

                <div class="form-group">
                  <label for="group_name">Role Name</label>
                  <input type="text" class="form-control" id="group_name" name="group_name" placeholder="Enter Role Name">
                </div>
                <div class="form-group">
                  <label for="permission">Permission</label>

                  <table class="table table-responsive">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Create</th>
                        <th>Update</th>
                        <th>View</th>
                        <th>Delete</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Users</td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="createUser" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="updateUser" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="viewUser" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="deleteUser" class="minimal"></td>
                      </tr>
                      <tr>
                        <td>Roles</td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="createGroup" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="updateGroup" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="viewGroup" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="deleteGroup" class="minimal"></td>
                      </tr>
                    
                      <tr>
                        <td>Category</td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="createCategory" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="updateCategory" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="viewCategory" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="deleteCategory" class="minimal"></td>
                      </tr>
                      <tr>
                        <td>Chambers</td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="createChamber" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="updateChamber" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="viewChamber" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="deleteChamber" class="minimal"></td>
                      </tr>
                      <tr>
                        <td>Rack</td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="createRack" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="updateRack" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="viewRack" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="deleteRack" class="minimal"></td>
                      </tr>
                      <tr>
                        <td>Customers</td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="createContact" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="updateContact" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="viewContact" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="deleteContact" class="minimal"></td>
                      </tr>
                      <tr>
                        <td>Units</td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="createUnit" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="updateUnit" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="viewUnit" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="deleteUnit" class="minimal"></td>
                      </tr>
                      <tr>
                        <td>Items</td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="createItem" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="updateItem" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="viewItem" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="deleteItem" class="minimal"></td>
                      </tr>
                      <tr>
                        <td>Stock In</td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="createCheckin" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="updateCheckin" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="viewCheckin" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="deleteCheckin" class="minimal"></td>
                      </tr>
                       <tr>
                        <td>Stock Out</td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="createCheckout" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="updateCheckout" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="viewCheckout" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="deleteCheckout" class="minimal"></td>
                      </tr>

                      <tr>
                        <td>Report</td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="createReport" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="updateReport" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="viewReport" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="deleteReport" class="minimal"></td>
                      </tr>

                       <tr>
                          <td>Expense</td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createExpense"></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateExpense"></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewExpense"></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteExpense"></td>
                      </tr>

                       <tr>
                          <td>Expense Heads</td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createExpenseHeads"></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateExpenseHeads"></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewExpenseHeads"></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteExpenseHeads"></td>
                      </tr>

                         <tr>
                          <td>Machines</td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createMachines" ></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateMachines" ></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewMachines"></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteMachines"></td>
                      </tr>

                      <tr>
                          <td>Machine Heads</td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createMachineHeads"></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateMachineHeads" ></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewMachineHeads"></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteMachineHeads"></td>
                      </tr>

                      <tr>
                          <td>Machine Service Type</td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createMachineServiceType"></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateMachineServiceType" ></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewMachineServiceType"></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteMachineServiceType"></td>
                      </tr>
                      
                      
                       <tr>
                          <td>Compliences</td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createCompliences"></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateCompliences"></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewCompliences"></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteCompliences"></td>
                      </tr>

                       <tr>
                          <td>Compliences Heads</td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createCompliencesHeads"></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateCompliencesHeads"></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewCompliencesHeads"></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteCompliencesHeads"></td>
                      </tr>

                       <tr>
                          <td>Compliences Service Type</td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createCompliencesServiceType"></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateCompliencesServiceType" ></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewCompliencesServiceType"></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteCompliencesServiceType"></td>
                      </tr>

                     
                      <!-- <tr>
                        <td>Attributes</td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="createAttribute" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="updateAttribute" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="viewAttribute" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="deleteAttribute" class="minimal"></td>
                      </tr> -->
                      <!-- <tr>
                        <td>Products</td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="createProduct" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="updateProduct" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="viewProduct" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="deleteProduct" class="minimal"></td>
                      </tr> -->
                      <!-- <tr>
                        <td>Orders</td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="createOrder" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="updateOrder" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="viewOrder" class="minimal"></td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="deleteOrder" class="minimal"></td>
                      </tr> -->
                      <!-- <tr>
                        <td>Reports</td>
                        <td> - </td>
                        <td> - </td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="viewReports" class="minimal"></td>
                        <td> - </td>
                      </tr>
                      <tr>
                        <td>Company</td>
                        <td> - </td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="updateCompany" class="minimal"></td>
                        <td> - </td>
                        <td> - </td>
                      </tr> -->
                      <tr>
                        <td>Profile</td>
                        <td> - </td>
                        <td> - </td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="viewProfile" class="minimal"></td>
                        <td> - </td>
                      </tr>
                      <tr>
                        <td>Setting</td>
                        <td>-</td>
                        <td><input type="checkbox" name="permission[]" id="permission" value="updateSetting" class="minimal"></td>
                        <td> - </td>
                        <td> - </td>
                      </tr>
                     
                      
                    </tbody>
                  </table>
                  
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="<?php echo base_url('groups/') ?>" class="btn btn-warning">Back</a>
              </div>
            </form>
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
  $(document).ready(function() {
    $("#mainUserNav").addClass('active');
    $("#addGroupNav").addClass('active');

    $('input[type="checkbox"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    });
  });
</script>

