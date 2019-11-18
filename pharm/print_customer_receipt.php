<?php 
if (!isset($_SESSION)) {
  session_start();
  unset($_SESSION['cusId']);
  unset($_SESSION['cusPid']);
  unset($_SESSION['fname']);
  unset($_SESSION['lname']);
}
?>
<?php require_once('Connections/pharmConn.php'); ?>
<?php 
$cusPid = $_GET['cusPid'];
$cusId = $_GET['cusId'];
$fname = $_GET['fname'];
$lname = $_GET['lname'];

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

//=====insert cutomer detail into sale==============================================

//----- Recordset from the sales table------------------------------------

mysql_select_db($database_pharmConn, $pharmConn);
$query_customerOrder = "SELECT sale.scusDid, sale.scusQty, sale.scusCost, registerdrug.drugname, (@rownum:=@rownum + 1) AS sn FROM sale LEFT JOIN registerdrug ON  sale.scusDid= registerdrug.drugid, (SELECT @rownum:=0)t WHERE sale.salecusId = '$cusId' AND sale.scusPid = '$cusPid'";
$customerOrder = mysql_query($query_customerOrder, $pharmConn) or die(mysql_error());
$row_customerOrder = mysql_fetch_assoc($customerOrder);
$totalRows_customerOrder = mysql_num_rows($customerOrder);

mysql_select_db($database_pharmConn, $pharmConn);
$query_customerOrder_sum = "SELECT sale.scusDid, sale.scusQty, sum(sale.scusCost) as sum FROM sale WHERE sale.salecusId = '$cusId' AND sale.scusPid = '$cusPid' GROUP BY sale.salecusId";
$customerOrder_sum = mysql_query($query_customerOrder_sum, $pharmConn) or die(mysql_error());
$row_customerOrder_sum = mysql_fetch_assoc($customerOrder_sum);
$totalRows_customerOrder_sum = mysql_num_rows($customerOrder_sum);
?>
<html>
<body onLoad="window.print(); window.location.href='index2.php?x4'" style="font-size: 25px;">

<div id="page-wrapper" style="width: 300px; background-color: grey;">

<div class="container-fluid">

               

                <div class="row">
                        <div class="col-lg-4">
                        <table class="table table-bordered table-hover table-striped" style="font-size:24px">
                               <h3>Customer Orders</h3>
                               <h4><?php echo $fname; ?>&nbsp;<?php echo $lname; ?></h4>
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Drug</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php do { ?>
  <tr>
    <td><?php echo $row_customerOrder['sn']; ?>1	</td>
    <td><?php echo $row_customerOrder['drugname']; ?>Paracetamol</td>
    <td><?php echo $row_customerOrder['scusCost']; ?>200</td>
    <td><?php echo $row_customerOrder['scusQty']; ?>2000</td>
  </tr>
  <?php } while ($row_customerOrder = mysql_fetch_assoc($customerOrder)); ?>
                				<td colspan="2">Total</td>
                				<td colspan="3"><?php echo $row_customerOrder_sum['sum']; ?>'30,000</td>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    </div>
                    

               

            </div>
            <!-- /.container-fluid -->
           
</body>
</html>


        
        <?php

mysql_free_result($customerOrder);
?>
