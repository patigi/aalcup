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

$maxRows_salesset = 10;
$pageNum_salesset = 0;
if (isset($_GET['pageNum_salesset'])) {
  $pageNum_salesset = $_GET['pageNum_salesset'];
}
$startRow_salesset = $pageNum_salesset * $maxRows_salesset;

mysql_select_db($database_pharmConn, $pharmConn);
$query_salesset = "SELECT sale.saleId, sale.salecusId, sale.scusPid, SUM(sale.scusQty) AS QTY, SUM( sale.scusCost) AS COST, sale.scusDate, customer.cusPid, customer.cusFname, customer.cusLname, (@rownum := @rownum+1) AS sn FROM sale LEFT JOIN customer ON customer.cusPid = sale.scusPid, (SELECT @rownum:=0)t   GROUP BY sale.scusPid ";
$query_limit_salesset = sprintf("%s LIMIT %d, %d", $query_salesset, $startRow_salesset, $maxRows_salesset);
$salesset = mysql_query($query_limit_salesset, $pharmConn) or die(mysql_error());
$row_salesset = mysql_fetch_assoc($salesset);

if (isset($_GET['totalRows_salesset'])) {
  $totalRows_salesset = $_GET['totalRows_salesset'];
} else {
  $all_salesset = mysql_query($query_salesset);
  $totalRows_salesset = mysql_num_rows($all_salesset);
}
$totalPages_salesset = ceil($totalRows_salesset/$maxRows_salesset)-1;
?>
<div id="page-wrapper">

            <div class="container-fluid">
            <div class="row">
            <div class="input-group col-md-8">   
            <form class="" method="post" action="index2.php?x7">  
            <div class="form-group col-md-6" >            
                <input class="form-control" data-plugin="datepicker" name="search" placeholder="Search..." type="text">
                     </div>
                    <div class="col-md-2">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                  
                    </div>
                    </form>
            </div>
            
            </div>
              <div class="row">
                    <div class="col-lg-8">
                        <h2>Order Schedule</h2>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Customer Name</th>
                                        <th>Qty</th>
                                        <th>Cost(N)</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php do { ?>
  <tr>
    <td><?php echo $row_salesset['sn']; ?></td>
    <td><?php echo $row_salesset['cusFname']; ?>&nbsp;<?php echo $row_salesset['cusLname']; ?></td>
    <td><?php echo $row_salesset['QTY']; ?></td>
    <td><?php echo $row_salesset['COST']; ?></td>
    <td><a href="salesdetail.php?cusid=<?php echo $row_salesset['salecusId']; ?>&cuspid=<?php echo $row_salesset['scusPid']; ?>" rel="facebox">DETAIL</a></td>
  </tr>
  <?php } while ($row_salesset = mysql_fetch_assoc($salesset)); ?>
                                </tbody>
                                
                            </table>
                            
                        </div>
                    </div>
              </div>
            <!-- /.container-fluid -->

        </div>
<?php
mysql_free_result($salesset);
?>
