<?php require_once('Connections/pharmConn.php'); ?>
<?php 
if (!isset($_SESSION)) {
  session_start();
}
mysql_select_db($database_pharmConn, $pharmConn);
$query_stockSet = "SELECT *, sum(store.storedrgQty)-sum(store.storedrgqtyout) AS avqty, scheduleorder.dUnit, (@rownum:=@rownum + 1) AS SN FROM store INNER JOIN  registerdrug ON  store.storeDid= registerdrug.drugid INNER JOIN  scheduleorder ON  store.store_order_id= scheduleorder.order_id, (SELECT @rownum:=0)t WHERE store.storeDid='{$_POST['drugId']}' GROUP BY store.storeDid";
$stockSet = mysql_query($query_stockSet, $pharmConn) or die(mysql_error());
$row_stockSet = mysql_fetch_assoc($stockSet);
$totalRows_stockSet = mysql_num_rows($stockSet);
$quantity_out = $_POST['quantity_out'];
$qty_in = $quantity_out;

if((isset($_POST['qty_out_form'])) & $_POST['quantity_out']!=""){
$insert_qtyout = mysql_query("INSERT INTO store (storeDid, store_order_id, storedrgCost, storedrgexpiry, storedrgqtyout, username) VALUES ('{$_POST['drugId']}', '{$_POST['storeOrderId']}', '{$_POST['drugCost']}', '{$_POST['expiry_date']}', '{$_POST['quantity_out']}', '{$_SESSION['MM_Username']}')") or die(mysql_error());	
$insert_to_stock = mysql_query("INSERT INTO stock (stockdid, stk_transaction_id, stockUnit, stockcost, stockprice, stock_qty_in, added_by) VALUES ('{$_POST['drugId']}', '{$_POST['storeOrderId']}', '{$_POST['drugUnit']}', '{$_POST['drugCost']}', '{$_POST['drugPrice']}', '$qty_in', '{$_SESSION['MM_Username']}')") or die(mysql_error());	
}
if($insert_qtyout = TRUE){
	$_SESSION['message'] = "Items successfully added to stock";
	header("Location: index2.php?x10");
}
?>