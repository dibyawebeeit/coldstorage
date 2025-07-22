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
        <li><a href="<?php echo base_url('groups/') ?>">Roles</a></li>
        <li class="active">Edit</li>
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
              <h3 class="box-title">Edit Role</h3>
            </div>
            <form role="form" action="<?php base_url('groups/update') ?>" method="post">
              <div class="box-body">

                <?php echo validation_errors(); ?>

                <div class="form-group">
                  <label for="group_name">Role Name</label>
                  <input type="text" class="form-control" id="group_name" name="group_name" placeholder="Enter Role Name" value="<?php echo $group_data['group_name']; ?>">
                </div>
                <div class="form-group">
                  <label for="permission">Permission</label>

                  <?php $serialize_permission = unserialize($group_data['permission']); ?>
                 
                  
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
                        <td><input type="checkbox" class="minimal" name="permission[]" id="permission" class="minimal" value="createUser" <?php if($serialize_permission) {
                          if(in_array('createUser', $serialize_permission)) { echo "checked"; } 
                        } ?> ></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateUser" <?php 
                        if($serialize_permission) {
                          if(in_array('updateUser', $serialize_permission)) { echo "checked"; } 
                        }
                        ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewUser" <?php 
                        if($serialize_permission) {
                          if(in_array('viewUser', $serialize_permission)) { echo "checked"; }   
                        }
                        ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteUser" <?php 
                        if($serialize_permission) {
                          if(in_array('deleteUser', $serialize_permission)) { echo "checked"; }  
                        }
                         ?>></td>
                      </tr>
                      <tr>
                        <td>Roles</td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createGroup" <?php 
                        if($serialize_permission) {
                          if(in_array('createGroup', $serialize_permission)) { echo "checked"; }  
                        }
                         ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateGroup" <?php 
                        if($serialize_permission) {
                          if(in_array('updateGroup', $serialize_permission)) { echo "checked"; }  
                        }
                         ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewGroup" <?php 
                        if($serialize_permission) {
                          if(in_array('viewGroup', $serialize_permission)) { echo "checked"; }  
                        }
                         ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteGroup" <?php 
                        if($serialize_permission) {
                          if(in_array('deleteGroup', $serialize_permission)) { echo "checked"; }  
                        }
                         ?>></td>
                      </tr>
                     
                      <tr>
                        <td>Category</td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createCategory" <?php if($serialize_permission) {
                          if(in_array('createCategory', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateCategory" <?php if($serialize_permission) {
                          if(in_array('updateCategory', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewCategory" <?php if($serialize_permission) {
                          if(in_array('viewCategory', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteCategory" <?php if($serialize_permission) {
                          if(in_array('deleteCategory', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                      </tr>

                      <tr>
                        <td>Chambers</td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createChamber" <?php if($serialize_permission) {
                          if(in_array('createChamber', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateChamber" <?php if($serialize_permission) {
                          if(in_array('updateChamber', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewChamber" <?php if($serialize_permission) {
                          if(in_array('viewChamber', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteChamber" <?php if($serialize_permission) {
                          if(in_array('deleteChamber', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                      </tr>

                      <tr>
                        <td>Racks</td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createRack" <?php if($serialize_permission) {
                          if(in_array('createRack', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateRack" <?php if($serialize_permission) {
                          if(in_array('updateRack', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewRack" <?php if($serialize_permission) {
                          if(in_array('viewRack', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteRack" <?php if($serialize_permission) {
                          if(in_array('deleteRack', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                      </tr>

                      <tr>
                        <td>Customers</td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createContact" <?php if($serialize_permission) {
                          if(in_array('createContact', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateContact" <?php if($serialize_permission) {
                          if(in_array('updateContact', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewContact" <?php if($serialize_permission) {
                          if(in_array('viewContact', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteContact" <?php if($serialize_permission) {
                          if(in_array('deleteContact', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                      </tr>

                      <tr>
                        <td>Units</td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createUnit" <?php if($serialize_permission) {
                          if(in_array('createUnit', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateUnit" <?php if($serialize_permission) {
                          if(in_array('updateUnit', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewUnit" <?php if($serialize_permission) {
                          if(in_array('viewUnit', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteUnit" <?php if($serialize_permission) {
                          if(in_array('deleteUnit', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                      </tr>

                      <tr>
                        <td>Items</td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createItem" <?php if($serialize_permission) {
                          if(in_array('createItem', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateItem" <?php if($serialize_permission) {
                          if(in_array('updateItem', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewItem" <?php if($serialize_permission) {
                          if(in_array('viewItem', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteItem" <?php if($serialize_permission) {
                          if(in_array('deleteItem', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                      </tr>

                      <tr>
                        <td>Stock In</td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createCheckin" <?php if($serialize_permission) {
                          if(in_array('createCheckin', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateCheckin" <?php if($serialize_permission) {
                          if(in_array('updateCheckin', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewCheckin" <?php if($serialize_permission) {
                          if(in_array('viewCheckin', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteCheckin" <?php if($serialize_permission) {
                          if(in_array('deleteCheckin', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                      </tr>
                      <tr>
                        <td>Stock Out</td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createCheckout" <?php if($serialize_permission) {
                          if(in_array('createCheckout', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateCheckout" <?php if($serialize_permission) {
                          if(in_array('updateCheckout', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewCheckout" <?php if($serialize_permission) {
                          if(in_array('viewCheckout', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteCheckout" <?php if($serialize_permission) {
                          if(in_array('deleteCheckout', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                      </tr>

                     
                        <tr>
                          <td>Income</td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createIncome" <?php if($serialize_permission) {
                            if(in_array('createIncome', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateIncome" <?php if($serialize_permission) {
                            if(in_array('updateIncome', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewIncome" <?php if($serialize_permission) {
                            if(in_array('viewIncome', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteIncome" <?php if($serialize_permission) {
                            if(in_array('deleteIncome', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                      </tr>
                      
                      
                       <tr>
                          <td>Expense</td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createExpense" <?php if($serialize_permission) {
                            if(in_array('createExpense', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateExpense" <?php if($serialize_permission) {
                            if(in_array('updateExpense', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewExpense" <?php if($serialize_permission) {
                            if(in_array('viewExpense', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteExpense" <?php if($serialize_permission) {
                            if(in_array('deleteExpense', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                      </tr>

                       <tr>
                          <td>Expense Heads</td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createExpenseHeads" <?php if($serialize_permission) {
                            if(in_array('createExpenseHeads', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateExpenseHeads" <?php if($serialize_permission) {
                            if(in_array('updateExpenseHeads', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewExpenseHeads" <?php if($serialize_permission) {
                            if(in_array('viewExpenseHeads', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteExpenseHeads" <?php if($serialize_permission) {
                            if(in_array('deleteExpenseHeads', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                      </tr>

                       <tr>
                          <td>Machines</td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createMachines" <?php if($serialize_permission) {
                            if(in_array('createMachines', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateMachines" <?php if($serialize_permission) {
                            if(in_array('updateMachines', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewMachines" <?php if($serialize_permission) {
                            if(in_array('viewMachines', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteMachines" <?php if($serialize_permission) {
                            if(in_array('deleteMachines', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                      </tr>


                      <tr>
                          <td>Machine Heads</td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createMachineHeads" <?php if($serialize_permission) {
                            if(in_array('createMachineHeads', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateMachineHeads" <?php if($serialize_permission) {
                            if(in_array('updateMachineHeads', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewMachineHeads" <?php if($serialize_permission) {
                            if(in_array('viewMachineHeads', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteMachineHeads" <?php if($serialize_permission) {
                            if(in_array('deleteMachineHeads', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                      </tr>

                      <tr>
                          <td>Machine Service Type</td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createMachineServiceType" <?php if($serialize_permission) {
                            if(in_array('createMachineServiceType', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateMachineServiceType" <?php if($serialize_permission) {
                            if(in_array('updateMachineServiceType', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewMachineServiceType" <?php if($serialize_permission) {
                            if(in_array('viewMachineServiceType', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteMachineServiceType" <?php if($serialize_permission) {
                            if(in_array('deleteMachineServiceType', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                      </tr>
                      
                      
                      
                       <tr>
                          <td>Compliences</td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createCompliences" <?php if($serialize_permission) {
                            if(in_array('createCompliences', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateCompliences" <?php if($serialize_permission) {
                            if(in_array('updateCompliences', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewCompliences" <?php if($serialize_permission) {
                            if(in_array('viewCompliences', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteCompliences" <?php if($serialize_permission) {
                            if(in_array('deleteCompliences', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                      </tr>


                       <tr>
                          <td>Compliences Heads</td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createCompliencesHeads" <?php if($serialize_permission) {
                            if(in_array('createCompliencesHeads', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateCompliencesHeads" <?php if($serialize_permission) {
                            if(in_array('updateCompliencesHeads', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewCompliencesHeads" <?php if($serialize_permission) {
                            if(in_array('viewCompliencesHeads', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteCompliencesHeads" <?php if($serialize_permission) {
                            if(in_array('deleteCompliencesHeads', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                      </tr>

                        <tr>
                          <td>Compliences Service Type</td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createCompliencesServiceType" <?php if($serialize_permission) {
                            if(in_array('createCompliencesServiceType', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateCompliencesServiceType" <?php if($serialize_permission) {
                            if(in_array('updateCompliencesServiceType', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewCompliencesServiceType" <?php if($serialize_permission) {
                            if(in_array('viewCompliencesServiceType', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                          <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteCompliencesServiceType" <?php if($serialize_permission) {
                            if(in_array('deleteCompliencesServiceType', $serialize_permission)) { echo "checked"; } 
                          } ?>></td>
                      </tr>





                       <tr>
                        <td>Report</td>
                        <td></td>
                        <td></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewReport" <?php if($serialize_permission) {
                          if(in_array('viewReport', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td></td>
                      </tr>
                      
                      <tr>
                        <td>Payment</td>
                        <td></td>
                        <td></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewPayment" <?php if($serialize_permission) {
                          if(in_array('viewPayment', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td></td>
                      </tr>

                      <!-- <tr>
                        <td>Attributes</td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createAttribute" <?php if($serialize_permission) {
                          if(in_array('createAttribute', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateAttribute" <?php if($serialize_permission) {
                          if(in_array('updateAttribute', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewAttribute" <?php if($serialize_permission) {
                          if(in_array('viewAttribute', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteAttribute" <?php if($serialize_permission) {
                          if(in_array('deleteAttribute', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                      </tr> -->
                      <!-- <tr>
                        <td>Products</td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createProduct" <?php if($serialize_permission) {
                          if(in_array('createProduct', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateProduct" <?php if($serialize_permission) {
                          if(in_array('updateProduct', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewProduct" <?php if($serialize_permission) {
                          if(in_array('viewProduct', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteProduct" <?php if($serialize_permission) {
                          if(in_array('deleteProduct', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                      </tr> -->
                      <!-- <tr>
                        <td>Orders</td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="createOrder" <?php if($serialize_permission) {
                          if(in_array('createOrder', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateOrder" <?php if($serialize_permission) {
                          if(in_array('updateOrder', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewOrder" <?php if($serialize_permission) {
                          if(in_array('viewOrder', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="deleteOrder" <?php if($serialize_permission) {
                          if(in_array('deleteOrder', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                      </tr> -->
                      <!-- <tr>
                        <td>Reports</td>
                        <td> - </td>
                        <td> - </td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewReports" <?php if($serialize_permission) {
                          if(in_array('viewReports', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td> - </td>
                      </tr> -->
                      <!-- <tr>
                        <td>Company</td>
                        <td> - </td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateCompany" <?php if($serialize_permission) {
                          if(in_array('updateCompany', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td> - </td>
                        <td> - </td>
                      </tr> -->
                    <!--  <tr>
                        <td>Profile</td>
                        <td> - </td>
                        <td> - </td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="viewProfile" <?php if($serialize_permission) {
                          if(in_array('viewProfile', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td> - </td>
                      </tr> -->
                      <tr>
                        <td>Setting</td>
                        <td>-</td>
                        <td><input type="checkbox" name="permission[]" id="permission" class="minimal" value="updateSetting" <?php if($serialize_permission) {
                          if(in_array('updateSetting', $serialize_permission)) { echo "checked"; } 
                        } ?>></td>
                        <td> - </td>
                        <td> - </td>
                      </tr>
                      
                     
                      
                    </tbody>
                  </table>
                  
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Update Changes</button>
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
    $("#manageGroupNav").addClass('active');

    $('input[type="checkbox"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    });
  });
</script>
