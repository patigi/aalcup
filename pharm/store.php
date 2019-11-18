<?php require_once('Connections/pharmConn.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_pharmConn, $pharmConn);
$query_stockSet = "SELECT *, sum(store.storedrgQty)-sum(store.storedrgqtyout) AS avqty, scheduleorder.dUnit, (@rownum:=@rownum + 1) AS SN FROM store INNER JOIN  registerdrug ON  store.storeDid= registerdrug.drugid INNER JOIN  scheduleorder ON  store.store_order_id= scheduleorder.order_id, (SELECT @rownum:=0)t GROUP BY store.storeDid";
$stockSet = mysql_query($query_stockSet, $pharmConn) or die(mysql_error());
$row_stockSet = mysql_fetch_assoc($stockSet);
$totalRows_stockSet = mysql_num_rows($stockSet);
?>
<div id="page-wrapper">

<div class="container-fluid">

                <div class="row">
                    <div class="col-lg-8">
					<h3 style="color: blue; float: right; margin-right: 100px;"><?php echo $_SESSION['message']; ?></h3>
                        <h2>Store Update</h2>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Drug Name</th>
                                        <th>Unit</th>
                                        <th>Cost</th>
                                        <th>Available Qty</th>
                                        <th>Price</th>
                                        <th colspan="2">Qty Out</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php do { ?>
                                    <tr>
                                        <td><?php echo $row_stockSet['SN']; ?></td>
                                        <td><?php echo $row_stockSet['drugname']; ?></td>
                                        <td><?php echo $row_stockSet['dUnit']; ?></td>
                                        <td><?php echo $row_stockSet['storedrgCost']; ?></td>
                                        <td><?php echo $row_stockSet['avqty']; ?></td>
                                        <form action="add_item_to_stock.php" method="POST">
										<td><input type="text" class="form-control input-sm" style="size: 4;" placeholder="Enter Price" name="drugPrice" maxlength="5" size="4" required /></td>
										<td><input type="text" class="form-control input-sm" style="size: 3;" placeholder="Enter Qty" name="quantity_out" maxlength="5" size="3" required /></td>
                                        <td><button class="btn btn-sm btn-default">Add to Stock</button></td>
										<input type="hidden" value="<?php echo $row_stockSet['storeDid']; ?>" name="drugId" />
										<input type="hidden" value="<?php echo $row_stockSet['dUnit']; ?>" name="drugUnit" />
										<input type="hidden" value="<?php echo $row_stockSet['store_order_id']; ?>" name="storeOrderId" />
										<input type="hidden" value="<?php echo $row_stockSet['storedrgCost']; ?>" name="drugCost" />
										<input type="hidden" value="<?php echo $row_stockSet['storedrgexpiry']; ?>"  name="expiry_date" />
										<input type="hidden" name="qty_out_form" />
										</form>
                                        <td><a href="store_bincard.php?did=<?php echo $row_stockSet['storeDid']; ?>" rel="facebox" class="btn btn-sm btn-default">Detail</a></td>
                                      </tr>
                                      <?php } while ($row_stockSet = mysql_fetch_assoc($stockSet)); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

               

            </div>
            <!-- /.container-fluid -->

        </div>
<?php
mysql_free_result($stockSet);
?>
