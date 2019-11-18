<?php require_once('Connections/pharmConn.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$query_delete = mysql_query("Delete from scheduleorder where id={$_GET['orderid']}") or die(mysql_error());


if($query_delete = TRUE){
	$_SESSION['message'] = "Item successfully deleted";
	header("Location: index2.php?x2");
}else
{
	$_SESSION['message'] = "Item failed to delete";
	header("Location: index2.php?x2");
}

?>