<?php require_once('Connections/pharmConn.php'); ?>
<?php
$sid = $_GET['stk']; 
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
	mysql_select_db($database_pharmConn, $pharmConn);
$query_stockIdSet = "SELECT stock.stockid, stock.stockdid, stock.availableqty FROM stock WHERE stock.stockdid='$sid'";
$stockIdSet = mysql_query($query_stockIdSet, $pharmConn) or die(mysql_error());
$row_stockIdSet = mysql_fetch_assoc($stockIdSet);
$totalRows_stockIdSet = mysql_num_rows($stockIdSet);

$stockid = $row_stockIdSet['stockid'];
$availableqty = $row_stockIdSet['availableqty'];

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "addStock")) {

	$schqty=$_POST['availableqty'];
	$total=$schqty+$availableqty;
  $updateSQL = sprintf("UPDATE stock SET stockcost=%s, stockprice=%s, availableqty='$total' WHERE stockid='$stockid'",
                       GetSQLValueString($_POST['stockcost'], "text"),
                       GetSQLValueString($_POST['stockprice'], "text"),
                       GetSQLValueString($_POST['availableqty'], "int"));

  mysql_select_db($database_pharmConn, $pharmConn);
  $Result3 = mysql_query($updateSQL, $pharmConn) or die(mysql_error());
  $insertSQL3 = sprintf("INSERT INTO bincard (bindid, qtyin, qtybal) VALUES ('$sid', '$schqty', '$total')");
    mysql_select_db($database_pharmConn, $pharmConn);
  $Result3 = mysql_query($insertSQL3, $pharmConn) or die(mysql_error());

  mysql_select_db($database_pharmConn, $pharmConn);
  $Result1 = mysql_query($updateSQL, $pharmConn) or die(mysql_error());
  $insertSQL = sprintf("INSERT INTO purchasetbl (purchase_id) VALUES ('$purchid')");
    mysql_select_db($database_pharmConn, $pharmConn);
  $Result2 = mysql_query($insertSQL, $pharmConn) or die(mysql_error());
  ?>
  <script>
  window.location="index2.php?x2";
  </script>
  <?php 
}
mysql_select_db($database_pharmConn, $pharmConn);
$query_scheduleDrugSet = "SELECT scheduleorder.Schdrugid, scheduleorder.dSupplier, scheduleorder.dQty, scheduleorder.dCost, registerdrug.drugname, supplier.supName, scheduleorder.transaction_id FROM scheduleorder LEFT JOIN registerdrug ON scheduleorder.Schdrugid=registerdrug.drugid LEFT JOIN supplier ON scheduleorder.dSupplier=supplier.supId WHERE scheduleorder.transaction_id='$purchid'";
$scheduleDrugSet = mysql_query($query_scheduleDrugSet, $pharmConn) or die(mysql_error());
$row_scheduleDrugSet = mysql_fetch_assoc($scheduleDrugSet);
$totalRows_scheduleDrugSet = mysql_num_rows($scheduleDrugSet);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<form action="<?php echo $editFormAction; ?>"  method="POST" class="modal-content" name="addStock">
                        <div class="modal-header" style="width: 600px;">
                          <h4 class="modal-title" id="exampleFormModalLabel">Add to Stock</h4>
                        </div>
                        <div class="modal-body">
                          <div class="row">
                            <div class="col-lg-4 form-group">
                            <label>Drug Name</label>
                              <input class="form-control" type="text" value="<?php echo $row_scheduleDrugSet['drugname']; ?>" name="Schdrugid" readonly/>
                              <input type="hidden" value="<?php echo $row_scheduleDrugSet['Schdrugid']; ?>" name="stockdid" />
                            </div>
                            <div class="col-lg-4 form-group">
                            <label>Drug Qty</label>
                              <input type="text" class="form-control" value="<?php echo $row_scheduleDrugSet['dQty']; ?>" name="availableqty" placeholder="Quantity">
                            </div>
                            <div class="col-lg-4 form-group">
                            <label>Drug Cost</label>
                              <input type="text" class="form-control" value="<?php echo $row_scheduleDrugSet['dCost']; ?>" name="stockcost" placeholder="Amount">
                            </div>
                            <div class="col-lg-6 form-group">
                            <label>Drug Supplier</label>
                              <input class="form-control" type="text" value="<?php echo $row_scheduleDrugSet['supName']; ?>" name="dSupplier" readonly/>
							<hr />
                            <label>Drug Price</label>
                              <input class="form-control" type="text" value="" placeholder="Set Price" name="stockprice" />
                            </div>
                            <div class="col-sm-12 pull-right">
                              <button class="btn btn-primary btn-outline" type="submit">Add Comment</button>
                            </div>
                            
                          </div>
                        </div>
                        <input type="hidden" name="MM_insert2" value="schedulefrm" />
                        <input type="hidden" name="MM_update" value="addStock" />
  </form>
</body>
</html>
<?php
mysql_free_result($stockIdSet);

mysql_free_result($scheduleDrugSet);

?>
