<?php include 'includes/admin_header.php'; ?>
<?php 

//Retrieve VAT%
$getVat = "SELECT * FROM bs_settings;";
$getVatQ = mysqli_query($connection, $getVat);
while ($row = mysqli_fetch_array($getVatQ)) {
  $vatPercent = $row['vat_percent'];
}

$cartCount = 0;
$compute1 = 0;
$totalAmountCase = 0;
$totalAmountCase1 = 0;
$totalAmountCaseVATValue = 0;
$totalAmountCaseVAT = 0;
$computeVATValue = 0;


$dateFrom = $_SESSION['dateFrom'];
$dateTo = $_SESSION['dateTo'];
?>
<div class="row">
  <div class="col-md-12">
    <h1> Interbranch Report Today
      <hr>
    </h1>
    <div class="col-md-12">
      <div class="well well-sm">
        <ol class="breadcrumb" style="margin-bottom: -3px;">
          <li><strong>Baesa Branch</strong></li>
          <li class="active"><a href="report_date_fv.php">Fairview Branch </a></li>
          <li class="active"><a href="report_date_td.php">Tondo Branch</a></li>
          <li class="active pull-right"><a href="print_interbranch_date.php" class="btn btn-xs btn-default"><i class="glyphicon glyphicon-print"></i> Print Interbranch Report</a></li>
        </ol>
      </div>

      <div class="panel panel-primary">
        <div class="panel-heading clearfix">
          <strong>
            <span class="glyphicon glyphicon-calendar"></span>
            <span>Today's Report</span>
          </strong>
        </div>

        <div class="panel-body">
          <h3>Baesa Branch</h3>
          <h4>Products Sold:</h4>
          <table class="table table-hover table-striped table-condensed" id="orderNo">
            <thead>
              <tr>
                <th>Order #</th>
                <th>Salesman Name</th>
                <th>Date of Placement</th>
                <th>Product Code</th>
                <th>Product Name </th>
                <th>Product Description </th>
                <th>Expiry Date </th>
                <th class="text-right">Quantity </th>
                <th class="text-right">Unit Price </th>
                <th class="text-right">Total </th>
              </tr>
            </thead>
            <tbody>
             <?php 

             $cartNoVat = "SELECT DISTINCT bs_customer.*, 
             sum(customer_product_qty * customer_product_price) as TotalSales,
             sum(customer_product_qty) AS TotalQty, 
             bs_orders.*
             FROM bs_customer 
             INNER JOIN bs_orders ON bs_orders.order_num = bs_customer.customer_sws_id
             WHERE bs_orders.status = 'Completed' 
             AND bs_orders.date BETWEEN '$dateFrom' AND '$dateTo'
             GROUP BY customer_sws_id, customer_product_code";

             $cartNoVatE = mysqli_query($connection, $cartNoVat);

             $tableCount = 0;
             while ($row = mysqli_fetch_array($cartNoVatE)) {
               $customer_product_name   = $row['customer_product_name'];
               $salesman_name   = $row['salesman_name'];
               $customer_product_des   = $row['customer_product_des'];
               $customer_product_price   = $row['customer_product_price'];
               $order_num   = $row['order_num'];
               $date   = $row['date'];
               $customer_product_exp  = $row['customer_product_exp'];
               $customer_product_qty  = $row['customer_product_qty'];
               $customer_product_code  = $row['customer_product_code'];
               $TotalSales  = $row['TotalSales'];
               $TotalQty  = $row['TotalQty'];
               $tableCount++;

                                               // calculate total and vat
               $compute = $customer_product_price * $TotalQty;
               $compute1 += $customer_product_price * $TotalQty;
               $totalAmount = number_format((float)$compute, 2, '.', '');

               $computeVATValue = $compute1 - ($compute1/(1+($vatPercent/100)));

               ?>

               <tr>
                 <td><?php echo sprintf('%08d',$order_num); ?></td>
                 <td><?php echo $salesman_name; ?></td>
                 <td><?php echo $date; ?></td>
                 <td><?php echo $customer_product_code; ?></td>
                 <td><?php echo $customer_product_name; ?></td>
                 <td><?php echo $customer_product_des; ?></td>
                 <td><?php echo $customer_product_exp; ?></td>
                 <td><?php echo $TotalQty; ?></td>
                 <td>₱ <?php echo number_format($customer_product_price, 2); ?></td>
                 <td>₱ <?php echo number_format($TotalSales, 2); ?></td>
               </tr>

               <?php } ?>
             </tbody>
           </table>
           <div class="row text-right">
            <div class="col-xs-2 col-xs-offset-8">
              <p>
                <strong>
                  Sub Total : <br>
                  TAX : <br>
                  Total : <br>
                </strong>
              </p>
            </div>
            <div class="col-xs-2">

              ₱ <?php echo number_format($compute1 - $computeVATValue,2); ?> <br>
              ₱ <?php echo number_format($computeVATValue, 2); ?> <br> 
              <strong>
                ₱ <?php echo number_format($compute1,2); ?> <br>
              </strong>
            </div>
          </div>

          <h4>Case Sold:</h4>
          <table class="table table-hover table-striped table-condensed" id="orderNoC">
            <thead>
              <tr>
                <th>Order #</th>
                <th>Salesman Name</th>
                <th>Date of Placement</th>
                <th>Case Code</th>
                <th>Case Name </th>
                <th>Case Description </th>
                <th>Expiry Date </th>
                <th class="text-right">Quantity </th>
                <th class="text-right">Case Price </th>
                <th class="text-right">Total </th>
              </tr>
            </thead>
            <tbody>
              <?php 

              $cartNoVat = "SELECT * FROM bs_orders INNER JOIN bs_customer ON order_num = customer_sws_id 
              WHERE status='Completed'AND bs_orders.date BETWEEN '$dateFrom' AND '$dateTo' AND customer_case_id > 0";
              $cartNoVatE = mysqli_query($connection, $cartNoVat);

              while ($row = mysqli_fetch_array($cartNoVatE)) {
                $customer_product_name   = $row['customer_product_name'];
                $salesman_name   = $row['salesman_name'];
                $customer_product_des   = $row['customer_product_des'];
                $customer_product_price   = $row['customer_product_price'];
                $order_num   = $row['order_num'];
                $date   = $row['date'];
                $customer_product_exp  = $row['customer_product_exp'];
                $customer_product_qty  = $row['customer_product_qty'];
                $customer_product_code  = $row['customer_product_code'];

                $totalAmountCase = $customer_product_price * $customer_product_qty;
                $totalAmountCase1 += $customer_product_price * $customer_product_qty;

                $totalAmountCaseVAT = ($totalAmountCase1*(1+($vatPercent/100)));
                $totalAmountCaseVATValue = $totalAmountCase1 - ($totalAmountCase1/(1+($vatPercent/100)));

                ?>

                <tr>
                  <td><?php echo sprintf('%08d',$order_num); ?></td>
                  <td><?php echo $salesman_name; ?></td>
                  <td><?php echo $date; ?></td>
                  <td><?php echo $customer_product_code; ?></td>
                  <td><?php echo $customer_product_name; ?></td>
                  <td><?php echo $customer_product_des; ?></td>
                  <td><?php echo $customer_product_exp; ?></td>
                  <td><?php echo $customer_product_qty; ?></td>
                  <td>₱ <?php echo number_format($customer_product_price, 2); ?></td>
                  <td>₱ <?php echo number_format($totalAmountCase, 2); ?></td>
                </tr>

                <?php } ?>
              </tbody>
            </table>
            <div class="row text-right">
              <div class="col-xs-2 col-xs-offset-8">
                <p>
                  <strong>
                    Sub Total : <br>
                    TAX : <br>
                    Total : <br>
                  </strong>
                </p>
              </div>
              <div class="col-xs-2">

                ₱ <?php echo number_format($totalAmountCaseVAT - $totalAmountCaseVATValue,2); ?> <br>
                ₱ <?php echo number_format($totalAmountCaseVATValue, 2); ?> <br> 
                <strong>
                  ₱ <?php echo number_format($totalAmountCaseVAT,2); ?> <br>
                </strong>
              </div>
            </div>

          </div>
        </div>

      </div>

    </div>

  </div>

</div>
<?php include 'includes/admin_footer.php'; ?>