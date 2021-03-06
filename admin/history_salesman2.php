<?php include 'includes/admin_header.php'; ?>
<script type="text/javascript" src="js/loader.js"></script>
<?php 

if (isset($_POST['submit'])) {
  $salesmanID = $_POST['selectSalesman'];
}

$getInfo = "SELECT * FROM users WHERE user_id = $salesmanID";
$getInfoE = mysqli_query($connection, $getInfo);

while ($row = mysqli_fetch_array($getInfoE)) {
  $firstname = $row['firstname'];
  $lastname = $row['lastname'];
}

 ?>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">Salesman History Today</h3>
      </div>
      <div class="panel-body">
        <div class="form-group">
        <h3>Salesman name: <strong><?php echo $firstname . " " . $lastname; ?></strong></h3>
        <hr>
          <table class="table table-condensed table-hover table-bordered">
            <thead>
              <tr>
                <th>Customer Items</th>
                <th>Qty Delivered</th>
                <th>Empties</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $sql = "SELECT {$prefix}customer.*, sum(customer_product_qty) as TQ,
                        sum(customer_empty_qty) as TE FROM {$prefix}customer 
                        WHERE customer_salesman_id = $salesmanID
                        GROUP BY customer_sws_id, customer_product_code";
                $sqlE = mysqli_query($connection, $sql);

                while ($row = mysqli_fetch_array($sqlE)) {
                  $customer_product_name = $row['customer_product_name']; 
                  $customer_product_qty = $row['customer_product_qty']; 
                  $customer_empty_qty = $row['customer_empty_qty']; 
              ?>                  
              <tr>
                <td><?php echo $customer_product_name; ?></td>
                <td><?php echo $customer_product_qty; ?></td>
                <td><?php echo $customer_empty_qty; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <a href="history_salesman.php" class="btn btn-default">Back</a>
      </div>
    </div>
  </div>
</div>
<!-- /.row -->
</div>
<!-- /.container-fluid -->

<!-- /#page-wrapper -->

<?php include 'includes/admin_footer.php'; ?>
