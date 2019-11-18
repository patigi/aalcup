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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
	$trans_id = rand(1,1000).rand(1,10000);
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "schedulefrm")) {
  $insertSQL = sprintf("INSERT INTO scheduleorder (transaction_id, Schdrugid, dSupplier, dQty, dCost, in_store, datead, expiry) VALUES (%s, %s, %s, %s, %s, 'NO', %s, %s)",
                       GetSQLValueString($_POST['tranxid'], "text"),
                       GetSQLValueString($_POST['Schdrugid'], "text"),
                       GetSQLValueString($_POST['dSupplier'], "text"),
                       GetSQLValueString($_POST['dQty'], "int"),
                       GetSQLValueString($_POST['dCost'], "text"),
                       GetSQLValueString($_POST['date'], "text"),
                       GetSQLValueString($_POST['expiry'], "text"));

  mysql_select_db($database_pharmConn, $pharmConn);
  $Result1 = mysql_query($insertSQL, $pharmConn) or die(mysql_error());
   ?>
  <script>
  window.location = "index2.php?x2";
  </script>
  <?php
}

mysql_select_db($database_pharmConn, $pharmConn);
$query_supplierSet = "SELECT supplier.supId, supplier.supName FROM supplier";
$supplierSet = mysql_query($query_supplierSet, $pharmConn) or die(mysql_error());
$row_supplierSet = mysql_fetch_assoc($supplierSet);
$totalRows_supplierSet = mysql_num_rows($supplierSet);

mysql_select_db($database_pharmConn, $pharmConn);
$query_drugcat = "SELECT drug_category.id, drug_category.cat_name FROM drug_category";
$drugcat = mysql_query($query_drugcat, $pharmConn) or die(mysql_error());
$row_drugcat = mysql_fetch_assoc($drugcat);
$totalRows_drugcat = mysql_num_rows($drugcat);
?>
<html>
<head>
<script type="text/javascript" src="datetimepicker_css.js" ></script>

</head>
<body>

					<div style="width: 600px">
                      <form action="<?php echo $editFormAction; ?>" method="POST" name="schedulefrm">
                        <div class="modal-header">
                            <h4 class="modal-title" id="exampleFormModalLabel">Stock Schedule</h4>
                        </div>
                        <div class="modal-body">
                          <div class="row">
                            <div class="col-lg-4 form-group">
                              <input type="hidden" class="form-control" name="tranxid" value="<?php echo $trans_id; ?>">
                              <input type="hidden" class="form-control" name="date" value="<?php echo date('Y-m-d'); ?>">
                            <label>Category</label>
                              <select class="form-control" name="category" id="category" onChange="loaddrug();">
                                <option value="">Select Category</option>
                                <?php
do {  
?>
                                <option value="<?php echo $row_drugcat['id']?>"><?php echo $row_drugcat['cat_name']?></option>
                                <?php
} while ($row_drugcat = mysql_fetch_assoc($drugcat));
  $rows = mysql_num_rows($drugcat);
  if($rows > 0) {
      mysql_data_seek($drugcat, 0);
	  $row_drugcat = mysql_fetch_assoc($drugcat);
  }
?>
                              </select>
                            </div>
                            <div class="col-lg-4 form-group">
                            <label>Select Drugs</label>
                              <select class="form-control" name="Schdrugid" id="drugname">
                                <option value="">Select Drugs</option>
                              </select>
                            </div>
							<div class="col-lg-4 form-group">
                            <label>Select Unit</label>
                              <select class="form-control" name="Schdunit" id="Schdunit">
                                <option value="">Select Unit</option>
                                <option value="Pcs">Pcs</option>
                                <option value="Bottle">Bottle</option>
                                <option value="Pack">Pack</option>
                                <option value="Sacchet">Sacchet</option>
                              </select>
                            </div>
                            <div class="col-lg-4 form-group">
                            <label>Quantity</label>
                              <input type="text" class="form-control" name="dQty" placeholder="Quantity">
                            </div>
                            <div class="col-lg-4 form-group">
                            <label>Amount</label>
                              <input type="text" class="form-control" name="dCost" placeholder="Amount">
                            </div>
                            <div class="col-lg-4 form-group">
                            <label>Supplier Name</label>
                              <select class="form-control" name="dSupplier">
                                <option value="">Supplier Name</option>
                                <option value="Others">Others</option>
                                <?php
do {  
?>
                                <option value="<?php echo $row_supplierSet['supId']?>"><?php echo $row_supplierSet['supName']?></option>
                                <?php
} while ($row_supplierSet = mysql_fetch_assoc($supplierSet));
  $rows = mysql_num_rows($supplierSet);
  if($rows > 0) {
      mysql_data_seek($supplierSet, 0);
	  $row_supplierSet = mysql_fetch_assoc($supplierSet);
  }
?>
                              </select>
                            </div>
                                                                                
                            <div class="col-sm-12 pull-right">
                              <button class="btn btn-primary btn-outline" type="submit">Save</button>
                            </div>
                            
                          </div>
                        </div>
                        <input type="hidden" name="MM_insert" value="schedulefrm" />
                      </form>
		</div>
        </body>
</html>
					<?php
mysql_free_result($supplierSet);

mysql_free_result($drugcat);
?>
