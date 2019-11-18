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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_scheduleOrderSet = 10;
$pageNum_scheduleOrderSet = 0;
if (isset($_GET['pageNum_scheduleOrderSet'])) {
  $pageNum_scheduleOrderSet = $_GET['pageNum_scheduleOrderSet'];
}
$startRow_scheduleOrderSet = $pageNum_scheduleOrderSet * $maxRows_scheduleOrderSet;
if(isset($_POST['search']) && $_POST['search'] != ""){
	$search = $_POST['search'];
	mysql_select_db($database_pharmConn, $pharmConn);
$query_scheduleOrderSet = "SELECT scheduleorder.order_id,  scheduleorder.Schdrugid, scheduleorder.dSupplier, scheduleorder.dQty, scheduleorder.dUnit, scheduleorder.dCost, scheduleorder.datead, registerdrug.drugname, supplier.supName, scheduleorder.id, purchasetbl.purchase_id, (@rownum := @rownum+1) AS sn FROM scheduleorder LEFT JOIN registerdrug ON scheduleorder.Schdrugid=registerdrug.drugid  LEFT JOIN supplier ON scheduleorder.dSupplier=supplier.supId LEFT JOIN purchasetbl ON scheduleorder.order_id = purchasetbl.purchase_id, (SELECT @rownum:=0)t WHERE scheduleorder.datead = '$search' ORDER BY id DESC";
$query_limit_scheduleOrderSet = sprintf("%s LIMIT %d, %d", $query_scheduleOrderSet, $startRow_scheduleOrderSet, $maxRows_scheduleOrderSet);
$scheduleOrderSet = mysql_query($query_limit_scheduleOrderSet, $pharmConn) or die(mysql_error());
$row_scheduleOrderSet = mysql_fetch_assoc($scheduleOrderSet);

}else{
mysql_select_db($database_pharmConn, $pharmConn);
$query_scheduleOrderSet = "SELECT scheduleorder.id, scheduleorder.order_id,  scheduleorder.Schdrugid, scheduleorder.dSupplier, scheduleorder.dQty, scheduleorder.dUnit, scheduleorder.dCost,  registerdrug.drugname, supplier.supName, scheduleorder.id, purchasetbl.purchase_id, (@rownum := @rownum+1) AS sn FROM scheduleorder LEFT JOIN registerdrug ON scheduleorder.Schdrugid=registerdrug.drugid  LEFT JOIN supplier ON scheduleorder.dSupplier=supplier.supId LEFT JOIN purchasetbl ON scheduleorder.order_id = purchasetbl.purchase_id, (SELECT @rownum:=0)t ORDER BY id DESC";
$query_limit_scheduleOrderSet = sprintf("%s LIMIT %d, %d", $query_scheduleOrderSet, $startRow_scheduleOrderSet, $maxRows_scheduleOrderSet);
$scheduleOrderSet = mysql_query($query_limit_scheduleOrderSet, $pharmConn) or die(mysql_error());
$row_scheduleOrderSet = mysql_fetch_assoc($scheduleOrderSet);
}
if (isset($_GET['totalRows_scheduleOrderSet'])) {
  $totalRows_scheduleOrderSet = $_GET['totalRows_scheduleOrderSet'];
} else {
  $all_scheduleOrderSet = mysql_query($query_scheduleOrderSet);
  $totalRows_scheduleOrderSet = mysql_num_rows($all_scheduleOrderSet);
}
$totalPages_scheduleOrderSet = ceil($totalRows_scheduleOrderSet/$maxRows_scheduleOrderSet)-1;

$queryString_scheduleOrderSet = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_scheduleOrderSet") == false && 
        stristr($param, "totalRows_scheduleOrderSet") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_scheduleOrderSet = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_scheduleOrderSet = sprintf("&totalRows_scheduleOrderSet=%d%s", $totalRows_scheduleOrderSet, $queryString_scheduleOrderSet);

?>
<script type="text/javascript">
$(document).ready(function(){
    $('.search-box input[type="text"]').on("keyup input", function(){
        /* Get input value on change */
        var inputVal = $(this).val();
        var resultDropdown = $(this).siblings(".result");
        if(inputVal.length){
            $.get("backend-search.php", {term: inputVal}).done(function(data){
                // Display the returned data in browser
                resultDropdown.html(data);
            });
        } else{
            resultDropdown.empty();
        }
    });
    
    // Set search input value on click of result item
    $(document).on("click", ".result p", function(){
        $(this).parents(".search-box").find('input[type="text"]').val($(this).text());
        $(this).parent(".result").empty();
    });
});
</script>
<div id="page-wrapper">

            <div class="container-fluid">
            <div class="row">
            <div class="input-group col-md-8">   
            <form class="" method="post" action="index2.php?x2">  
            <div class="form-group col-md-6" >            
                <input class="form-control" data-plugin="datepicker" name="search" placeholder="Search..." type="text">
                     </div>
                    <div class="col-md-2">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                  
                    </div>
                    </form>
                     <a class="btn btn-primary pull-right" href="addorder.php" rel="facebox">Add more Drug</a>
            </div>
  <div class="col-lg-8 form-group search-box">
                            <label>Description</label>
								<input class="form-control" type="text" autocomplete="off" placeholder="Search country..." />
								<div class="result"></div>
							</div>            </div>
              <div class="row">
                    <div class="col-lg-8">
                        <h2>Order Schedule</h2>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Drug Name</th>
                                        <th>Supplier</th>
                                        <th>Unit</th>
                                        <th>Qty</th>
                                        <th>Cost(N)</th>
                                        <th>Add to Stock</th>
                                        <th>Roll Back</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php do { ?>
  <tr>
    <td><?php echo $row_scheduleOrderSet['sn']; ?></td>
    <td><?php echo $row_scheduleOrderSet['drugname']; ?></td>
    <td><?php echo $row_scheduleOrderSet['supName']; ?></td>
    <td><?php echo $row_scheduleOrderSet['dUnit']; ?></td>
    <td><?php echo $row_scheduleOrderSet['dQty']; ?></td>
    <td><?php echo $row_scheduleOrderSet['dCost']; ?></td>
    <td><?php if(isset($row_scheduleOrderSet['purchase_id'])){ ?>Already Added<?php }else {?><a href="addtostore.php?id=<?php echo $row_scheduleOrderSet['id']; ?>&purchid=<?php echo $row_scheduleOrderSet['transaction_id']; ?>" class="btn btn-primary" type="button" rel="facebox">Add to Store</a>
<?php }?></td>
    <td><?php if(!isset($row_scheduleOrderSet['purchase_id'])){ ?>Not Available<?php }else {?><a href="rollbacktransaction.php?stk=<?php echo $row_scheduleOrderSet['Schdrugid']; ?>&purchid=<?php echo $row_scheduleOrderSet['transaction_id']; ?>" rel="facebox" class="btn btn-info" type="button">Roll Back</a>
<?php }?></td>
    <td><a href="addStock.php?stk=<?php echo $row_scheduleOrderSet['id']; ?>" rel="facebox" class="btn btn-danger" type="button">Delete</a>
      </td>
  </tr>
  <?php } while ($row_scheduleOrderSet = mysql_fetch_assoc($scheduleOrderSet)); ?>
                                </tbody>
                                
                            </table>
                            <table border="0">
        <tr>
          <td><?php if ($pageNum_scheduleOrderSet > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_scheduleOrderSet=%d%s", $currentPage, 0, $queryString_scheduleOrderSet); ?>">First</a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_scheduleOrderSet > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_scheduleOrderSet=%d%s", $currentPage, max(0, $pageNum_scheduleOrderSet - 1), $queryString_scheduleOrderSet); ?>">Previous</a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_scheduleOrderSet < $totalPages_scheduleOrderSet) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_scheduleOrderSet=%d%s", $currentPage, min($totalPages_scheduleOrderSet, $pageNum_scheduleOrderSet + 1), $queryString_scheduleOrderSet); ?>">Next</a>
              <?php } // Show if not last page ?></td>
          <td><?php if ($pageNum_scheduleOrderSet < $totalPages_scheduleOrderSet) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_scheduleOrderSet=%d%s", $currentPage, $totalPages_scheduleOrderSet, $queryString_scheduleOrderSet); ?>">Last</a>
              <?php } // Show if not last page ?></td>
        </tr>
      </table>
                        </div>
                    </div>
              </div>
            <!-- /.container-fluid -->

        </div>
<?php
mysql_free_result($scheduleOrderSet);


?>
