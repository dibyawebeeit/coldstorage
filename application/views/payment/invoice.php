<style>
    .invoice-box {
      padding: 30px;
      border: 1px solid #eee;
      margin: 50px auto;
      max-width: 800px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
    }
    .invoice-title h2, .invoice-title h3 {
      display: inline-block;
    }
    .table > tbody > tr > .no-line {
      border-top: none;
    }
    .table > thead > tr > .no-line {
      border-bottom: none;
    }
    .table > tbody > tr > .thick-line {
      border-top: 2px solid #ddd;
    }
    @media print {
        button, .btn {
        display: none !important;
        }
    }
  </style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Stock Out<small>Invoice</small></h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Stock Out Invoice</li>
        </ol>
    </section>

    <section class="content">
        <div class="text-right" style="margin: 20px;">
            <button class="btn btn-primary" onclick="window.print();">
                <i class="glyphicon glyphicon-print"></i> Print Invoice
            </button>
        </div>

        <div class="container invoice-box">
            <div class="row">
                <div class="col-xs-6">
                    <h2>Cold Storage</h2>
                </div>
                <div class="col-xs-6 text-right">
                    <h3>Invoice #<?=$checkoutDetails['unique_id']?></h3>
                    <strong>Date:</strong> <?= $paymentDetails['payment_date'] ?>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-xs-6">
                <strong>Billed To:</strong><br>
                <?=$checkoutDetails['name']?><br>
                <?=$checkoutDetails['email']?><br>
                <?=$checkoutDetails['phone']?>
                </div>
                <div class="col-xs-6 text-right">
                <strong>Stock In Date:</strong> <?=$checkoutDetails['checkin_date']?><br>
                <strong>Stock Out Date:</strong> <?=$checkoutDetails['checkout_date']?><br>
                <strong>Duration:</strong> <?=$checkoutDetails['duration_in_days']?> days
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-xs-12">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <th>Unit</th>
                        <th>Rate/Day</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                
                    $duration = $checkoutDetails['duration_in_days'];
                    $grand_total = 0;
                    foreach ($itemList as $index => $item):
                        $total = $item['price'] * $item['stock'] * $duration;
                        $grand_total += $total;
                    ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= $item['item_name'] ?></td>
                        <td><?= $item['unit_code'] ?></td>
                        <td>₹<?= number_format($item['price'], 2) ?></td>
                        <td><?= $item['stock'] ?></td>
                        <td>₹<?= number_format($total, 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="5" class="text-right"><strong>Bill Amount</strong></td>
                        <td><strong>₹<?= number_format($checkoutDetails['bill_amount'], 2) ?></strong></td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-right"><strong>Paid Amount</strong></td>
                        <td><strong>₹<?= number_format($paymentDetails['store_cost'], 2) ?></strong></td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-right"><strong>Transportation Cost</strong></td>
                        <td><strong>₹<?= number_format($paymentDetails['transportation_cost'], 2) ?></strong></td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-right"><strong>Deduction Cost</strong></td>
                        <td><strong>₹<?= number_format($paymentDetails['deduction_cost'], 2) ?></strong></td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-right"><strong>GST</strong></td>
                        <td><strong>₹<?= number_format($paymentDetails['gst_amount'], 2) ?></strong></td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-right"><strong>Total Paid</strong></td>
                        <td><strong>₹<?= number_format($paymentDetails['amount'], 2) ?></strong></td>
                    </tr>
                    
                    </tbody>
                </table>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 text-center">
                <p>Thank you for your business!</p>
                </div>
            </div>
        </div>
    </section>
</div>