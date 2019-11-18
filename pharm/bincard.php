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

mysql_select_db($database_pharmConn, $pharmConn);
$query_stockSet = "SELECT *, sum(stock.stock_qty_in)-sum(stock.stock_qty_out) AS avqty, stock.stockcost, stock.stockprice, registerdrug.drugid, registerdrug.drugname, (@rownum:=@rownum + 1) AS sn FROM stock LEFT JOIN  registerdrug ON  stock.stockdid=registerdrug.drugid, (SELECT @rownum := 0)t GROUP BY stock.stockdid";
$stockSet = mysql_query($query_stockSet, $pharmConn) or die(mysql_error());
$row_stockSet = mysql_fetch_assoc($stockSet);
$totalRows_stockSet = mysql_num_rows($stockSet);
?>
<div id="page-wrapper">

<div class="container-fluid">

                <div class="row">
                    <div class="col-lg-8">
                        <h2>New Stock</h2>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Drug Name</th>
                                        <th>Price</th>
                                        <th>Cost</th>
                                        <th>Available Qty</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php do { ?>
                                    <tr>
                                        <td><?php echo $row_stockSet['sn']; ?></td>
                                        <td><?php echo $row_stockSet['drugname']; ?></td>
                                        <td><?php echo number_format($row_stockSet['stockprice'], 2); ?></td>
                                        <td><?php echo number_format($row_stockSet['stockcost'], 2); ?></td>
                                        <td><?php echo $row_stockSet['avqty']; ?></td>
                                        <td><a href="bincardhistory.php?did=<?php echo $row_stockSet['drugid']; ?>" rel="facebox" class="btn btn-default">View</a></td>
                                      </tr>
                                      <?php } while ($row_stockSet = mysql_fetch_assoc($stockSet)); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

               

            </div>
            <!-- /.container-fluid -->

        </div>
<?php
mysql_free_result($stockSet);
?>
