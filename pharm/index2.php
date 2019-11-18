
<?php 
$x1= $_GET['x1']; //Dashboard
$x2= $_GET['x2']; //Schedule Order
$x3= $_GET['x3']; //Add Supplier
$x4= $_GET['x4']; //Add Customer
$x5= $_GET['x5']; //Store Drug
$x6= $_GET['x6'];
$x7= $_GET['x7'];
$x8= $_GET['x8'];
$x9= $_GET['x9'];
$x10= $_GET['x10'];
$x11= $_GET['x11']; //Add User
 ?>
<?php include_once("head.php");?>
<?php
if(isset ($x1)){
 include_once("dashboard.php");
}
 ?>
 
<?php 
if(isset ($x2)){
	include("schedule.php");	
}
?>
<?php 
if(isset ($x3)){
	include("supplier.php");	
}
?>
<?php 
if(isset ($x4)){
	include("customer.php");	
}
?>
<?php 
if(isset ($x5)){
	include("stock.php");	
}
?>
<?php 
if(isset ($x6)){
	include("newproduct.php");	
}
?>
<?php 
if(isset ($x7)){
	include("sales.php");	
}
?>
<?php 
if(isset ($x8)){
	include("generatereport.php");	
}
?>
<?php 
if(isset ($x9)){
	include("bincard.php");	
}
?>
<?php 
if(isset ($x10)){
	include("store.php");	
}
?>
<?php 
if(isset ($x11)){
	include("add_user.php");	
}
?>
<?php include_once("foot.php");?>

