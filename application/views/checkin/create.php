  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>New <small>Stock In</small></h1>
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

          <div class="box">
            <!-- <div class="box-header">
              <h3 class="box-title">New Stock In</h3>
            </div> -->
            <!-- /.box-header -->
             <div id="messages"></div>
            <form role="form" action="<?php echo base_url('checkin/store') ?>" method="post" id="createForm">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                            <!-- <label for="contact">Customer <sup>*</sup></label> -->
                                <div style="display: flex;gap: 10px;">
                                    <div class="form-check">
                                    <input class="form-check-input" type="radio" name="customerType" id="newCustomer" value="new">
                                    <label class="form-check-label" for="newCustomer">
                                        New Customer
                                    </label>
                                    </div>
                                    <div class="form-check">
                                    <input class="form-check-input" type="radio" name="customerType" id="oldCustomer" value="old" checked>
                                    <label class="form-check-label" for="oldCustomer">
                                        Old Customer
                                    </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 contact_dropdown">
                            <div class="form-group">
                            <select class="form-control" id="contact_id" name="contact_id" style="width: 200px;">
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
                        <div class="col-md-12 new_contact_section">
                            <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                <div class="card">
                                    <form role="form" id="createCustomer">
                                    <div class="modal-body">
                                        <div class="form-group">
                                        <label for="name">Name <sup>*</sup></label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" autocomplete="off">
                                        </div>
                                        <div class="form-group">
                                        <label for="email">Email <sup>*</sup></label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" autocomplete="off">
                                        </div>
                                        <div class="form-group">
                                        <label for="phone">Phone <sup>*</sup></label>
                                        <input type="tel" class="form-control" pattern="[0-9]{3}[0-9]{3}[0-9]{4}" onkeypress="if(this.value.length==10) return false;" id="phone" name="phone" placeholder="Enter Phone" autocomplete="off">
                                        </div>
                                        <div class="form-group">
                                        <label for="brand_name">Details</label>
                                        <textarea class="form-control" name="details" placeholder="Enter Details"></textarea>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                        <div class="form-group">
                            <label for="item">Items </label>
                            <input type="text" class="form-control" id="searchDropdown" placeholder="Type to search..." autocomplete="off">
                            <ul class="dropdown-menu" id="dropdownResults" style="display: none; width: 100%; max-height: 200px; overflow-y: auto;"></ul>
                        </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                        <table class="table" id="item_table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Item</th>
                                <th scope="col">Unit</th>
                                <th scope="col">Service Charge</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Chamber</th>
                                <th scope="col">Rack</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        </div>
                    </div>
                <div class="box-body">
       
                <div class="box-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>

            </form>
          </div>
          <!-- /.box -->
        </div>
       
      </div>
      <!-- /.row -->
      

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <script>
$(document).ready(function () {

    $("#mainStockNav").addClass('active');
    $("#checkinNav").addClass('active');
    $('#contact_id').select2({
        // dropdownParent: $('#addModal'),
        placeholder: "Select",
        allowClear: false
    });

  $('#searchDropdown').on('input', function () {
    let query = $(this).val();
    if (query.length === 0) {
      $('#dropdownResults').hide().empty();
      return;
    }

    $.ajax({
      url: '<?= base_url("checkin/fetchItem") ?>',
      method: 'POST',
      data: { query: query },
      dataType: 'json',
      success: function (data) {
        const $dropdown = $('#dropdownResults');
        $dropdown.empty();
        if (data.length) {
          data.forEach(item => {
            $dropdown.append(`<li><a href="#" data-id="${item.id}" class="dropdown-item">${item.name}</a></li>`);
          });
          $dropdown.show();
        } else {
          $dropdown.hide();
        }
      }
    });
  });

  // Handle selection
  $('#dropdownResults').on('click', '.dropdown-item', function (e) {
    e.preventDefault();
    const name = $(this).text();
    const id = $(this).data('id');

    // Optionally, you can store more data in data attributes or fetch by ID
    $.ajax({
      url: '<?= base_url("checkin/get_item_by_id") ?>',
      method: 'POST',
      data: { id: id },
      dataType: 'json',
      success: function (item) {
        addItemToTable(item);
      }
    });
    
    $('#searchDropdown').val(name);
    $('#dropdownResults').hide();
    //console.log("Selected ID:", id); // Do something with the ID
  });

  // Hide on outside click
  $(document).on('click', function (e) {
    if (!$(e.target).closest('.form-group').length) {
      $('#dropdownResults').hide();
    }
  });

});

function addItemToTable(item) {
  const row = `
    <tr>
      <th scope="row"><i class="fa fa-trash delete-row" style="cursor:pointer;"></i></th>
      <td>${item.name}<input type="hidden" name="item_id[]" value="${item.id}"></td>
      <td>${item.unit}</td>
      <td>${item.price}</td>
      <td style="width: 15%;"><input type="number" step="any" class="form-control" name="stock[]" placeholder="Qty"></td>
      <td style="width: 15%;">
        <select class="form-control chamber_id" name="chamber_id[]">
          <option>Select</option>
            <?php
              foreach ($chamberList as $chamber) {
                ?>
                <option value="<?=$chamber['id']?>"><?=$chamber['name']?></option>
                <?php
              }
            ?>
        </select>
      </td>
      <td style="width: 15%;">
        <select class="form-control rack_id" name="rack_id[]">
          <option>Select</option>
        </select>
      </td>
    </tr>
  `;
  $('#item_table tbody').append(row);
}

$('#item_table').on('click', '.delete-row', function () {
  $(this).closest('tr').remove();
});

// Event delegation for dynamically added selects
$(document).on('change', '.chamber_id', function () {
  const $chamberSelect = $(this);
  const chamberId = $chamberSelect.val();
  
  if (chamberId !== '') {
    $.ajax({
      url: "<?=base_url('checkin/getRackList')?>",
      type: 'post',
      data: { chamberId: chamberId },
      dataType: 'json',
      success: function (response) {
        // Find the corresponding rack select in the same row
        const $row = $chamberSelect.closest('tr');
        const $rackSelect = $row.find('.rack_id');
        
        // Clear previous options
        $rackSelect.html('<option value="">Select</option>');

        // Populate if successful
        if (response.success === true) {
          const racks = response.data;
          racks.forEach(rack => {
            $rackSelect.append(`<option value="${rack.id}">${rack.name}</option>`);
          });
        }
      }
    });
  }
});



let contact_dropdown = document.querySelector(".contact_dropdown");
let new_contact_section = document.querySelector(".new_contact_section");
// Get all radio buttons with name 'customerType'
const radios = document.querySelectorAll('input[name="customerType"]');
new_contact_section.style.display = "none";

radios.forEach(radio => {
  radio.addEventListener('change', function () {
    if (this.checked) {
      if (this.value === "new") {
        new_contact_section.style.display = "block";
        contact_dropdown.style.display = "none";
      } else if (this.value === "old") {
        contact_dropdown.style.display = "block";
        new_contact_section.style.display = "none";
      }
    }
  });
});

//create checkin
document.addEventListener('DOMContentLoaded', function () {
  const createForm = document.getElementById('createForm');

  createForm.addEventListener('submit', async function (e) {
    e.preventDefault();

    const form = this;
    const selectedValue = document.querySelector('input[name="customerType"]:checked').value;
    let customer_id;
    let newCustomerData = null;

    // === 1. Handle NEW CUSTOMER ===
    if (selectedValue === 'new') {
      const name = document.getElementById('name').value.trim();
      const email = document.getElementById('email').value.trim();
      const phone = document.getElementById('phone').value.trim();
      const details = document.querySelector('textarea[name="details"]').value.trim();

      if (!name || !email || !phone) {
        alert('Please fill all required fields for new customer.');
        return;
      }

      newCustomerData = new FormData();
      newCustomerData.append('name', name);
      newCustomerData.append('email', email);
      newCustomerData.append('phone', phone);
      newCustomerData.append('details', details);

      // Optional: validate if customer already exists
      try {
        const customerValidation = await fetch('<?= base_url("checkin/checkCustomer") ?>', {
          method: 'POST',
          body: newCustomerData
        });

        const customerValue = await customerValidation.json();
        if (!customerValue.success) {
          alert(customerValue.message || 'Customer checking failed.');
          return;
        }
      } catch (error) {
        console.error('Error during customer check:', error);
        alert('Error occurred while checking customer.');
        return;
      }

    } else {
      // === 2. Handle EXISTING CUSTOMER ===
      customer_id = document.getElementById('contact_id').value.trim();
      if (!customer_id) {
        alert('Please choose a customer.');
        return;
      }
    }

    // === 3. Item Validation ===
    const itemRows = document.querySelectorAll('#item_table tbody tr');
    if (itemRows.length === 0) {
      alert("Please add at least one item.");
      return;
    }

    let isValid = true;

    itemRows.forEach(row => {
      const stockInput = row.querySelector('input[name="stock[]"]');
      const chamberSelect = row.querySelector('select[name="chamber_id[]"]');
      const rackSelect = row.querySelector('select[name="rack_id[]"]');

      if (!stockInput.value.trim()) {
        stockInput.classList.add("is-invalid");
        isValid = false;
      } else {
        stockInput.classList.remove("is-invalid");
      }

      if (!chamberSelect.value || chamberSelect.value === "Select") {
        chamberSelect.classList.add("is-invalid");
        isValid = false;
      } else {
        chamberSelect.classList.remove("is-invalid");
      }

      if (!rackSelect.value || rackSelect.value === "Select") {
        rackSelect.classList.add("is-invalid");
        isValid = false;
      } else {
        rackSelect.classList.remove("is-invalid");
      }
    });

    if (!isValid) {
      alert("Please fill all required fields (Stock, Chamber, Rack) for each item.");
      return;
    }

    // === 4. Create New Customer (if needed) ===
    if (selectedValue === 'new' && newCustomerData) {
      try {
        const customerRes = await fetch('<?= base_url("checkin/addCustomer") ?>', {
          method: 'POST',
          body: newCustomerData
        });

        const customerData = await customerRes.json();

        if (customerData.success) {
          customer_id = customerData.customer.id;
        } else {
          alert(customerData.message || 'Customer creation failed.');
          return;
        }
      } catch (error) {
        console.error('Error creating customer:', error);
        alert('Error occurred while creating customer.');
        return;
      }
    }

    // === 5. Final Submission ===
    const formData = new FormData(form);
    formData.append('customer_id', customer_id);

    const action = form.getAttribute("action");
    const method = form.getAttribute("method");

    // Remove previous error messages
    document.querySelectorAll(".text-danger").forEach(el => el.remove());

    fetch(action, {
      method: method,
      body: new URLSearchParams(formData),
      headers: {
        'Accept': 'application/json'
      }
    })
      .then(res => res.json())
      .then(response => {
        const messagesDiv = document.querySelector("#messages");

        if (typeof manageTable !== 'undefined') {
          manageTable.ajax.reload(null, false);
        }

        if (response.success) {
          messagesDiv.innerHTML = `
            <div class="alert alert-success alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <strong><span class="glyphicon glyphicon-ok-sign"></span></strong> ${response.messages}
            </div>
          `;

          // Clear item table and reset form
          document.querySelector("#item_table tbody").innerHTML = '';
          form.reset();

          form.querySelectorAll(".form-group").forEach(group => {
            group.classList.remove("has-error", "has-success");
          });

          contact_dropdown.style.display = "block";
          new_contact_section.style.display = "none";

        } else {
          if (typeof response.messages === "object") {
            Object.entries(response.messages).forEach(([fieldId, message]) => {
              const field = document.getElementById(fieldId);
              if (field) {
                const formGroup = field.closest(".form-group");
                formGroup.classList.remove("has-error", "has-success");
                formGroup.classList.add(message.length > 0 ? "has-error" : "has-success");

                const errorSpan = document.createElement("span");
                errorSpan.className = "text-danger";
                errorSpan.innerHTML = message;
                field.after(errorSpan);
              }
            });
          } else {
            messagesDiv.innerHTML = `
              <div class="alert alert-warning alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <strong><span class="glyphicon glyphicon-exclamation-sign"></span></strong> ${response.messages}
              </div>
            `;
          }
        }
      })
      .catch(error => {
        console.error("Final submission error:", error);
        alert("Something went wrong while submitting the form.");
      });
  });
});






</script>

 
