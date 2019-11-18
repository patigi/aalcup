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
$query_selectdrug = "SELECT registerdrug.drugid, registerdrug.drugname FROM registerdrug";
$selectdrug = mysql_query($query_selectdrug, $pharmConn) or die(mysql_error());
$row_selectdrug = mysql_fetch_assoc($selectdrug);
$totalRows_selectdrug = mysql_num_rows($selectdrug);

$maxRows_salesreportset = 20;
$pageNum_salesreportset = 0;
if (isset($_GET['pageNum_salesreportset'])) {
  $pageNum_salesreportset = $_GET['pageNum_salesreportset'];
}
$startRow_salesreportset = $pageNum_salesreportset * $maxRows_salesreportset;

mysql_select_db($database_pharmConn, $pharmConn);

if((isset($_POST['search'])) && $_POST['did']!=""){
$did = $_POST['did'];
$from = $_POST['from'];
$to = $_POST['to'];

$query_salesreportset = "SELECT *, customer.cusId, customer.cusPid, customer.cusFname, customer.cusLname, registerdrug.drugname, (@rownum := @rownum+1) AS sn  
FROM sale LEFT JOIN customer ON customer.cusPid=sale.scuspid LEFT JOIN registerdrug ON registerdrug.drugid=sale.scusDid, (SELECT @rownum:=0)t WHERE sale.scusDid = '$did' 
AND sale.scusDate BETWEEN '$from%' AND '$to%'";
	
}else{
$query_salesreportset = "SELECT *, customer.cusId, customer.cusPid, customer.cusFname, customer.cusLname, registerdrug.drugname, (@rownum := @rownum+1) AS sn  FROM sale LEFT JOIN customer ON customer.cusPid=sale.scuspid LEFT JOIN registerdrug ON registerdrug.drugid=sale.scusDid, (SELECT @rownum:=0)t  ";
}
$query_limit_salesreportset = sprintf("%s LIMIT %d, %d", $query_salesreportset, $startRow_salesreportset, $maxRows_salesreportset);
$salesreportset = mysql_query($query_limit_salesreportset, $pharmConn) or die(mysql_error());
$row_salesreportset = mysql_fetch_assoc($salesreportset);

if (isset($_GET['totalRows_salesreportset'])) {
  $totalRows_salesreportset = $_GET['totalRows_salesreportset'];
} else {
  $all_salesreportset = mysql_query($query_salesreportset);
  $totalRows_salesreportset = mysql_num_rows($all_salesreportset);
}
$totalPages_salesreportset = ceil($totalRows_salesreportset/$maxRows_salesreportset)-1;
?>
<div id="page-wrapper">

            <div class="container-fluid">
            <div class="row">
            <div class="input-group col-md-8">   
            <form class="" method="post" action="index2.php?x8">  
            <div class="form-group col-md-3" > 
            <label>Select Drug</label>           
                <select class="form-control" name="did">
                  <option value="">select</option>
                  <?php
do {  
?>
                  <option value="<?php echo $row_selectdrug['drugid']?>"><?php echo $row_selectdrug['drugname']?></option>
                  <?php
} while ($row_selectdrug = mysql_fetch_assoc($selectdrug));
  $rows = mysql_num_rows($selectdrug);
  if($rows > 0) {
      mysql_data_seek($selectdrug, 0);
	  $row_selectdrug = mysql_fetch_assoc($selectdrug);
  }
?>
                </select>
                     </div>
            <div class="form-group col-md-3" > 
            <label>Date From</label>           
                <input class="form-control" data-plugin="datepicker" name="from" placeholder="yyyy-mm-dd" type="text">
                     </div>
            <div class="form-group col-md-3" >   
            <label>Date To</label>           
                <input class="form-control" data-plugin="datepicker" name="to" placeholder="yyyy-mm-dd" type="text">
                     </div>
                    <div class="col-md-2">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                  
                    </div>
                    <input type="hidden" name="search" value="search" >
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
                                        <th>Drug Name</th>
                                        <th>Qty</th>
                                        <th>Price(N)</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php do { ?>
  <tr>
    <td><?php echo $row_salesreportset['sn']; ?></td>
    <td><?php echo $row_salesreportset['cusFname']; ?>&nbsp<?php echo $row_salesreportset['cusLname']; ?></td>
    <td><?php echo $row_salesreportset['drugname']; ?></td>
    <td><?php echo $row_salesreportset['scusQty']; ?></td>
    <td><?php echo $row_salesreportset['scusCost']; ?></td>
    <td><?php echo $row_salesreportset['scusDate']; ?></td>
  </tr>
  <?php } while ($row_salesreportset = mysql_fetch_assoc($salesreportset)); ?>
                              </tbody>
                                
                            </table>
                            
                        </div>
                    </div>
              </div>
            <!-- /.container-fluid -->

        </div>
            <?php
mysql_free_result($selectdrug);

mysql_free_result($salesreportset);
?>
