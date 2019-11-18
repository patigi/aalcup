<?php require_once('Connections/pharmConn.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "admin";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index.php?denied";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "storefrm")) {
  $insertSQL = sprintf("INSERT INTO store (storeDid, store_order_id, storedrgQty, storedrgCost, storedrgexpiry, username) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['storeDid'], "int"),
                       GetSQLValueString($_POST['storeOrderId'], "text"),
                       GetSQLValueString($_POST['storedrgQty'], "int"),
                       GetSQLValueString($_POST['storedrgCost'], "int"),
                       GetSQLValueString($_POST['storedrgexpiry'], "date"),
                       GetSQLValueString($_POST['username'], "text"));

  mysql_select_db($database_pharmConn, $pharmConn);
  $Result1 = mysql_query($insertSQL, $pharmConn) or die(mysql_error());

//Update scheduleorder table for drugs that has been successfully added to store--------------------------------
if($Result1=TRUE){
	echo $xpiry = $_POST['storedrgexpiry'];
$update_schedule_order = mysql_query("UPDATE scheduleorder set in_store = 'YES', expiry='$xpiry' where id = '{$_GET['id']}'");
}
  $insertGoTo = "index2.php?x2";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
$id = $_GET['id'];
mysql_select_db($database_pharmConn, $pharmConn);
$query_fromscheduleorder = "SELECT * FROM scheduleorder WHERE scheduleorder.id='$id'";
$fromscheduleorder = mysql_query($query_fromscheduleorder, $pharmConn) or die(mysql_error());
$row_fromscheduleorder = mysql_fetch_assoc($fromscheduleorder);
$totalRows_fromscheduleorder = mysql_num_rows($fromscheduleorder);
?>
<script src="datetimepicker_css.js"></script>
<form method="POST" action="<?php echo $editFormAction; ?>" name="storefrm">
                        <div class="modal-header">
                            <h4 class="modal-title" id="exampleFormModalLabel">Stock Schedule</h4>
                        </div>
                        <div class="modal-body">
                          <div class="row">
                              <input type="hidden" class="form-control" value="<?php echo $row_fromscheduleorder['Schdrugid']; ?>" name="storeDid">
                              <input type="hidden" class="form-control" value="<?php echo $row_fromscheduleorder['order_id']; ?>" name="storeOrderId">
                            <div class="col-lg-12 form-group">
                            <label>Quantity</label>
                              <input type="text" class="form-control" value="<?php echo $row_fromscheduleorder['dQty']; ?>" name="storedrgQty" placeholder="Quantity" readonly>
                            </div>
                            <div class="col-lg-12 form-group">
                            <label>Cost</label>
                              <input type="text" class="form-control" value="<?php echo $row_fromscheduleorder['dCost']; ?>" name="storedrgCost" placeholder="Amount">
                            </div>
							<div class="col-lg-12 form-group">
                            <label>Expiry</label>
                              <input type="date" id="demo1" class="form-control slimpicker" name="storedrgexpiry" placeholder="yyyy-mm-dd" onclick="javascript:NewCssCal('demo1','yyyyMMdd');" style="cursor:pointer" required="required">
                            </div> 
                              <input type="hidden" value="<?php echo $_SESSION['MM_Username']; ?>" name="username" required="required">
                            <div class="col-sm-12 pull-right">
                              <button class="btn btn-primary btn-outline" type="submit">Save</button>
                            </div>
                            
                          </div>
                        </div>
                        <input type="hidden" name="MM_insert" value="storefrm" />
                      </form>
<?php
mysql_free_result($fromscheduleorder);
?>
