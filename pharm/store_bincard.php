<?php require_once('Connections/pharmConn.php'); ?>
<?php
$did = $_GET['did'];
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

$maxRows_bincardset = 20;
$pageNum_bincardset = 0;
if (isset($_GET['pageNum_bincardset'])) {
  $pageNum_bincardset = $_GET['pageNum_bincardset'];
}
$startRow_bincardset = $pageNum_bincardset * $maxRows_bincardset;

mysql_select_db($database_pharmConn, $pharmConn);
 
$query_bincardset = "SELECT x.storeDid
     , x.storedrgQty
     , x.storedrgqtyout
	 , x.username
	 , SUM(y.bal) as balance
  FROM(SELECT *, storedrgQty-storedrgqtyout as bal from store)y JOIN 
  (SELECT *, storedrgQty-storedrgqtyout as bal from store)x ON y.storeId <= x.storeId
  where x.storeDid='$did' AND y.storeDid='$did' GROUP BY x.storeId ORDER BY x.storeId DESC";
$query_limit_bincardset = sprintf("%s LIMIT %d, %d", $query_bincardset, $startRow_bincardset, $maxRows_bincardset);
$bincardset = mysql_query($query_limit_bincardset, $pharmConn) or die(mysql_error());
$row_bincardset = mysql_fetch_assoc($bincardset);

if (isset($_GET['totalRows_bincardset'])) {
  $totalRows_bincardset = $_GET['totalRows_bincardset'];
} else {
  $all_bincardset = mysql_query($query_bincardset);
  $totalRows_bincardset = mysql_num_rows($all_bincardset);
}
$totalPages_bincardset = ceil($totalRows_bincardset/$maxRows_bincardset)-1;

$item_name = mysql_query("select drugname from registerdrug where drugid ='$did'");
$result_item_name = mysql_fetch_array($item_name);
?>
<div id="page-wrapper">
                        
<div class="container-fluid">

                <div class="row">
                <div  style="width: 600px;">
                        <h2>Store Bincard for <?php echo $result_item_name['drugname']; ?></h2>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Quantity In</th>
                                        <th>Quantity Out</th>
                                        <th>Quantity Balance</th>
                                        <th>User</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php do { ?>
  <tr>
    <td><?php echo $row_bincardset['sn']; ?></td>
    <td><?php echo $row_bincardset['storedrgQty']; ?></td>
    <td><?php echo $row_bincardset['storedrgqtyout']; ?></td>
    <td><?php echo $row_bincardset['balance']; ?></td>
    <td><?php echo $row_bincardset['username']; ?></td>
  </tr>
  <?php } while ($row_bincardset = mysql_fetch_assoc($bincardset)); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

               

            </div>
            <!-- /.container-fluid -->

        </div>
<?php
mysql_free_result($bincardset);
?>
