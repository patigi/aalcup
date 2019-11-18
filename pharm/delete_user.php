<?php require_once('Connections/pharmConn.php'); ?>
<?php 
$del_user = mysql_query("DELETE FROM pharm_users where user_id = '{$_GET['delid']}'")or die(mysql_error());
header("Location: index2.php?x11");
?>