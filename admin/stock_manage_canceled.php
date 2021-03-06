<?php include 'includes/admin_header.php'; ?>
<div class="row">
  <div class="col-md-12">
    <h1> Manage Stock Requests </h1>
    <hr>
    <div class="well well-sm">
      <ol class="breadcrumb" style="margin-bottom: -3px;">
        <li><a href="stock_manage.php">Pending Requests</a> <span class="badge"><?php echo $fromPending; ?></span></li>
        <li class="active">
          <a href="stock_manage_partial.php">Partially Transfered Requests</a> <span class="badge" style="background-color: #f89406"><?php echo $fromProgress; ?></span>
        </li>
        <li><a href="stock_manage_complete.php">Completed Requests</a> <span class="badge" style="background-color:#468847"><?php echo $fromComplete; ?></span></li>
        <li><strong>Canceled Requests</strong> <span class="badge" style="background-color: #b94a48"><?php echo $fromCanceled; ?></span></li>
      </ol>
    </div>

    <div class="panel panel-danger">
          <div class="panel-body">
            <h3>Canceled Requests</h3><hr>

            <table class="table table-condensed table-hover table-bordered table-striped" id="example">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Transfer Number</th>
                  <th>Requested by Branch</th>
                  <th>To Branch</th>
                  <th>Status</th>
                  <th>Date of Placement</th>
                  <th width="20%">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php 

                  $sql = "SELECT DISTINCT * FROM stock_transfer WHERE transfer_frombranch = '$capitalPrefix' AND transfer_status = 'Canceled' GROUP BY (transfer_number);";
                  $sqlE = mysqli_query($connection, $sql);

                  $tableCount = 0;

                  while ($row = mysqli_fetch_array($sqlE)) {
                    $transfer_id = $row['transfer_id'];
                    $transfer_number = $row['transfer_number'];
                    $from = $row['transfer_frombranch'];
                    $quantity = $row['transfer_quantity'];
                    $to = $row['transfer_tobranch'];
                    $transfer_status = $row['transfer_status'];
                    $date = $row['transfer_date'];

                    $tableCount++;
                 ?>
                <tr>
                  <td><?php echo $tableCount; ?></td>
                  <td><?php echo sprintf('%08d', $transfer_number); ?></td>
                  <td><?php echo $from; ?></td>
                  <td><?php echo $to; ?></td>
                  <td><?php echo $transfer_status; ?></td>
                  <td><?php echo $date; ?></td>
                  <td>
                    <a href="stock_details.php?id=<?php echo $transfer_number;?>&to=<?php echo $to; ?>" class="btn btn-xs btn-block btn-primary"><i class="glyphicon glyphicon-list"></i> Details</a>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
            </table>

            <a href="stock_transfer.php" class="btn btn-default">Back</a>
          </div>
        </div>


      <hr>


      <hr>
    
  </div>

</div>
<?php 
  
  if (isset($_GET['cancel'])) {
      
      $cancelID = $_GET['cancel'];

      $sqlD = "UPDATE stock_transfer SET transfer_status = 'Canceled' WHERE transfer_number = $cancelID;";
      $sqlDE = mysqli_query($connection, $sqlD);

      header("Refresh:0; url=stock_manage.php");
  }



?>
<?php include 'includes/admin_footer.php'; ?>