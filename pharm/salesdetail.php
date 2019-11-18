<?php require_once('Connections/pharmConn.php'); ?>
<?php
$cusid = $_GET['cusid'];
$cuspid = $_GET['cuspid'];
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
$query_customersalesdetail = "SELECT *, customer.cusId, customer.cusPid, customer.cusFname, customer.cusLname, registerdrug.drugname, (@rownum := @rownum+1) AS sn
 FROM sale LEFT JOIN customer ON customer.cusPid=sale.scuspid LEFT JOIN registerdrug ON registerdrug.drugid=sale.scusDid, (SELECT @rownum:=0)t WHERE customer.cusId='$cusid' AND customer.cusPid='$cuspid'
";
$customersalesdetail = mysql_query($query_customersalesdetail, $pharmConn) or die(mysql_error());
$row_customersalesdetail = mysql_fetch_assoc($customersalesdetail);
$totalRows_customersalesdetail = mysql_num_rows($customersalesdetail);
?>
<div class="container-fluid">

<div class="row" style="width:600px">
                    <div>
                        <h2>Customer Sales Detail</h2>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Drug Name</th>
                                        <th>Drug Qty</th>
                                        <th>Drug Cost(N)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                 
                                  <?php do { ?>
                                  <tr>
                                    <td><?php echo $row_customersalesdetail['sn'];?></td>
                                    <td><?php echo $row_customersalesdetail['drugname']; ?></td>
                                    <td><?php echo $row_customersalesdetail['scusQty'];?></td>
                                    <td><?php echo $row_customersalesdetail['scusCost'];?></td>
                                  </tr>
                                    <?php } while ($row_customersalesdetail = mysql_fetch_assoc($customersalesdetail)); ?>
                                </tbody>
                                
                            </table>
                            
                        </div>
                    </div>
                    </div>
                    </div>
<?php
mysql_free_result($customersalesdetail);
?>
