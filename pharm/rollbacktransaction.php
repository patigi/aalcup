<?php require_once('Connections/pharmConn.php'); ?>
<?php
$stk = $_GET['stk'];
$purchid = $_GET['purchid'];

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
$query_scheduleset = "SELECT scheduleorder.Schdrugid, scheduleorder.dSupplier, scheduleorder.dQty, scheduleorder.dCost, registerdrug.drugname, supplier.supName FROM scheduleorder LEFT JOIN registerdrug ON scheduleorder.Schdrugid=registerdrug.drugid LEFT JOIN supplier ON scheduleorder.dSupplier=supplier.supId WHERE scheduleorder.transaction_id='$purchid'";
$scheduleset = mysql_query($query_scheduleset, $pharmConn) or die(mysql_error());
$row_scheduleset = mysql_fetch_assoc($scheduleset);
$totalRows_scheduleset = mysql_num_rows($scheduleset);

mysql_select_db($database_pharmConn, $pharmConn);
$query_stockset = "SELECT * FROM stock WHERE stockdid = '$stk'";
$stockset = mysql_query($query_stockset, $pharmConn) or die(mysql_error());
$row_stockset = mysql_fetch_assoc($stockset);
$totalRows_stockset = mysql_num_rows($stockset);

$stockid = $row_stockset['stockid'];

$availableqty = $row_stockset['availableqty'];


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "rollback")) {
	
	
	$dqty = $_POST['availableqty'];
	$total = $availableqty - $dqty;

  $updateSQL = sprintf("UPDATE stock SET availableqty=%s WHERE stockid=%s",
                       GetSQLValueString($total, "int"),
                       GetSQLValueString($_POST['did'], "int"));

  mysql_select_db($database_pharmConn, $pharmConn);
  $Result1 = mysql_query($updateSQL, $pharmConn) or die(mysql_error());

  $updateGoTo = "index2.php?x2";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
if ((isset($_POST['pid'])) && ($_POST['pid'] != "")) {
  $deleteSQL = sprintf("DELETE FROM purchasetbl WHERE purchase_id=%s",
                       GetSQLValueString($_POST['pid'], "int"));

  mysql_select_db($database_pharmConn, $pharmConn);
  $Result1 = mysql_query($deleteSQL, $pharmConn) or die(mysql_error());

  $deleteGoTo = "index2.php?x2";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}


?>



<form action="<?php echo $editFormAction; ?>"  method="POST" name="rollback">
                        <div class="modal-header" style="width: 600px;">
                          <h4 class="modal-title" id="exampleFormModalLabel">Add to Stock</h4>
                        </div>
                        <div class="modal-body">
                          <div class="row">
                            <div class="col-lg-4 form-group">
                            <label>Drug Name</label>
                              <input class="form-control" type="text" value="<?php echo $row_scheduleset['drugname']; ?>" name="drugname" readonly/>
                              <input class="form-control" type="hidden" value="<?php echo $stk; ?>" name="did" readonly/>
                              <input class="form-control" type="hidden" value="<?php echo $purchid; ?>" name="pid" readonly/>
                            </div>
                            <div class="col-lg-4 form-group">
                            <label>Drug Qty</label>
                              <input type="text" class="form-control" value="<?php echo $row_scheduleset['dQty']; ?>" name="availableqty" placeholder="Quantity">
                            </div>
                            <div class="col-lg-4 form-group">
                            <label>Drug Cost</label>
                              <input type="text" class="form-control" value="<?php echo $row_scheduleset['dCost']; ?>" name="stockcost" placeholder="Amount">
                            </div>
                            <div class="col-lg-6 form-group">
                            <label>Drug Supplier</label>
                              <input class="form-control" type="text" value="<?php echo $row_scheduleset['supName']; ?>" name="dSupplier" readonly/>
							<hr />
                            <div class="col-sm-12 pull-right">
                              <button class="btn btn-primary btn-outline" type="submit">Roll Back</button>
                            </div>
                            
                          </div>
                        </div>
                        <input type="hidden" name="MM_update" value="rollback" />
  </form>
<?php
mysql_free_result($scheduleset);

mysql_free_result($stockset);
?>
