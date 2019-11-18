<?php require_once('Connections/pharmConn.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "admin, salespoint";
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

$MM_restrictGoTo = "index.php?fail";
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

 $did = $_POST['did'];
 $cusid = $_POST['cusId'];
 $cuspid = $_POST['cusPid'];
 $fname = $_POST['fname'];
 $lname = $_POST['lname'];
 $qty_order = $_POST['qty_order'];
 $dCost = $_POST['dCost'];
 


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

if ((isset($_POST['savedata']))) {
  $insertSQL = sprintf("INSERT INTO sale (salecusId, scusPid, scusDid, scusQty, scusCost, scusDate, username) VALUES (%s, %s, %s, %s, %s, %s, '{$_SESSION['MM_Username']}')",
                       GetSQLValueString($_POST['cusId'], "text"),
                       GetSQLValueString($_POST['cusPid'], "int"),
                       GetSQLValueString($_POST['did'], "int"),
                       GetSQLValueString($_POST['qty_order'], "int"),
                       GetSQLValueString($_POST['dCost'], "int"),
                       GetSQLValueString(date('Y-m-d'), "date"));

  mysql_select_db($database_pharmConn, $pharmConn);
  $Result1 = mysql_query($insertSQL, $pharmConn) or die(mysql_error());

mysql_select_db($database_pharmConn, $pharmConn);
  $query_availableQty = "SELECT * FROM stock WHERE stock.stockdid = '$did' ORDER BY stockid DESC";
$availableQty = mysql_query($query_availableQty, $pharmConn) or die(mysql_error());
$row_availableQty = mysql_fetch_assoc($availableQty);
$totalRows_availableQty = mysql_num_rows($availableQty);
 
$unit = $row_availableQty['stockUnit'];
$stockcost = $row_availableQty['stockcost'];
$stockprice = $row_availableQty['stockprice'];

mysql_select_db($database_pharmConn, $pharmConn);
$update_query = "INSERT INTO stock (stockdid, stk_transaction_id, stockUnit, stockcost, stockprice, stock_qty_out, added_by) VALUES('{$_POST['did']}', '{$_POST['cusPid']}', '$unit', '$stockcost', '$stockprice', '{$_POST['qty_order']}', '{$_SESSION['MM_Username']}')";
    mysql_select_db($database_pharmConn, $pharmConn);
  $Result4 = mysql_query($update_query, $pharmConn) or die(mysql_error());
if($Result1){	
if($Result4){	
echo 1;
if((isset($_POST['savedata'])) && $_POST['savedata']==2){
header("Location: index2.php?x4&savedata=2");
}
}
}
/*mysql_select_db($database_pharmConn, $pharmConn);
  $insertSQL3 = sprintf("INSERT INTO bincard (bindid, qtyout, qtybal, user) VALUES ('$did', '$qty_order', '$newqty', '{$_SESSION['MM_Username']}')");
    mysql_select_db($database_pharmConn, $pharmConn);
  $Result3 = mysql_query($insertSQL3, $pharmConn) or die(mysql_error());*/
  exit;
 
  

}

if ((isset($_POST['showdata']))) {
	$cusId=$_SESSION['cusId'];
	$cusPid=$_SESSION['cusPid'];
mysql_select_db($database_pharmConn, $pharmConn);
$query_customerOrder = "SELECT sale.scusDid, sale.scusQty, sale.scusCost, sale.saleId, registerdrug.drugname, (@rownum:=@rownum + 1) AS sn FROM sale LEFT JOIN registerdrug ON  sale.scusDid= registerdrug.drugid, (SELECT @rownum:=0)t WHERE sale.salecusId = '$cusId' AND sale.scusPid = '$cusPid'";
$customerOrder = mysql_query($query_customerOrder, $pharmConn) or die(mysql_error());
$row_customerOrder = mysql_fetch_assoc($customerOrder);
$totalRows_customerOrder = mysql_num_rows($customerOrder);

mysql_select_db($database_pharmConn, $pharmConn);
$query_customerOrder_sum = "SELECT sale.scusDid, sale.scusQty, sum(sale.scusCost*sale.scusQty) as sum FROM sale WHERE sale.salecusId = '$cusId' AND sale.scusPid = '$cusPid' GROUP BY sale.salecusId";
$customerOrder_sum = mysql_query($query_customerOrder_sum, $pharmConn) or die(mysql_error());
$row_customerOrder_sum = mysql_fetch_assoc($customerOrder_sum);
$totalRows_customerOrder_sum = mysql_num_rows($customerOrder_sum);

  do { 
  ?>
  <tr>
    <td><?php echo $row_customerOrder['sn']; ?></td>
    <td><?php echo $row_customerOrder['drugname']; ?></td>
    <td></td>
    <td><?php echo $row_customerOrder['scusQty']; ?></td>
    <td><?php echo $row_customerOrder['scusCost']; ?></td>
    <td><?php echo $row_customerOrder['scusCost']*$row_customerOrder['scusQty']; ?></td>
    <td><button id="del" data-id="<?php echo $row_customerOrder['saleId']; ?>" >Delete</button></td>
  </tr>
  <?php } while ($row_customerOrder = mysql_fetch_assoc($customerOrder)); ?>
                				<td colspan="5">Total</td>
                				<td colspan="3"><?php echo 'N'.number_format($row_customerOrder_sum['sum'],2); ?></td>
<?php
exit;
}
 if ((isset($_POST['deletedata']))) {
	 $sql = mysql_query("SELECT * FROM sale where saleId='{$_POST['delid']}'");
	 $result = mysql_fetch_assoc($sql);
	 $Pid = $result['scusPid'];
	 $Did = $result['scusDid'];
	 $delq = "DELETE FROM sale where saleId='{$_POST['delid']}'";
	 $delq2 = "DELETE FROM stock where stk_transaction_id='$Pid' AND stockdid='$Did'";
	if(mysql_query($delq)){
	if(mysql_query($delq2)){
		echo 1;
	}
	}
	exit;
 }

?>
