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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "supplierFrm")) {
  $insertSQL = sprintf("INSERT INTO supplier (supName, supContName, supContPhone, supAddress) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['supName'], "text"),
                       GetSQLValueString($_POST['supContName'], "text"),
                       GetSQLValueString($_POST['SupContPhone'], "text"),
                       GetSQLValueString($_POST['supAddress'], "text"));

  mysql_select_db($database_pharmConn, $pharmConn);
  $Result1 = mysql_query($insertSQL, $pharmConn) or die(mysql_error());
}

$maxRows_supplierSet = 10;
$pageNum_supplierSet = 0;
if (isset($_GET['pageNum_supplierSet'])) {
  $pageNum_supplierSet = $_GET['pageNum_supplierSet'];
}
$startRow_supplierSet = $pageNum_supplierSet * $maxRows_supplierSet;

mysql_select_db($database_pharmConn, $pharmConn);
$query_supplierSet = "SELECT *, (@rownum:=@rownum+1) AS sn FROM supplier, (SELECT @rownum:=0)t";
$query_limit_supplierSet = sprintf("%s LIMIT %d, %d", $query_supplierSet, $startRow_supplierSet, $maxRows_supplierSet);
$supplierSet = mysql_query($query_limit_supplierSet, $pharmConn) or die(mysql_error());
$row_supplierSet = mysql_fetch_assoc($supplierSet);

if (isset($_GET['totalRows_supplierSet'])) {
  $totalRows_supplierSet = $_GET['totalRows_supplierSet'];
} else {
  $all_supplierSet = mysql_query($query_supplierSet);
  $totalRows_supplierSet = mysql_num_rows($all_supplierSet);
}
$totalPages_supplierSet = ceil($totalRows_supplierSet/$maxRows_supplierSet)-1;
?>
<div id="page-wrapper">

            <div class="container-fluid">

               

                <div class="row">
                    <div class="col-lg-8">
                        <h2>Suppliers List</h2>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Supplier Name</th>
                                        <th>Address</th>
                                        <th>Contact Person</th>
                                        <th>Phone No.</th>
                                    </tr>
                                </thead>
                                <tbody>
																  <?php do { ?>
                                  <tr>
                                    <td><?php echo $row_supplierSet['sn']; ?></td>
                                    <td><?php echo $row_supplierSet['supName']; ?></td>
                                    <td><?php echo $row_supplierSet['supAddress']; ?></td>
                                    <td><?php echo $row_supplierSet['supContName']; ?></td>
                                    <td><?php echo $row_supplierSet['supContPhone']; ?></td>
                                  </tr>
                                  <?php } while ($row_supplierSet = mysql_fetch_assoc($supplierSet)); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row row-lg">
            <div class="col-lg-4 col-md-6">
              <!-- Example Form Modal -->
              <div class="example-wrap">
                <div class="example">
                  <div class="example-well height-350">
                  </div>
                  <button class="btn btn-primary" data-target="#exampleFormModal" data-toggle="modal"
                  type="button">Add Supplier</button>
                  <!-- Modal -->
                  <div class="modal fade" id="exampleFormModal" aria-hidden="false" aria-labelledby="exampleFormModalLabel"
                  role="dialog" tabindex="-1">
                    <div class="modal-dialog">
                      <form method="POST" action="<?php echo $editFormAction; ?>" class="modal-content" name="supplierFrm">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                          </button>
                          <h4 class="modal-title" id="exampleFormModalLabel">Add Supplier</h4>
                        </div>
                        <div class="modal-body">
                          <div class="row">
                            <div class="col-lg-6 form-group">
                              <input type="text" class="form-control" name="supName" placeholder="Supplier Name">
                            </div>
                            <div class="col-lg-6 form-group">
                              <input type="text" class="form-control" name="supContName" placeholder="Contact Person">
                            </div>
                            <div class="col-lg-6 form-group">
                              <input type="int" class="form-control" name="SupContPhone" placeholder="Phone Number">
                            </div>                            
                            <div class="col-lg-6 form-group">
                              <input type="email" class="form-control" name="supEmail" placeholder="Email">
                            </div>
                            <div class="col-lg-8 form-group">
                              <textarea type="text" class="form-control" name="supAddress" placeholder="Address"></textarea>
                            </div>
                            <div class="col-sm-12 pull-right">
                              <button class="btn btn-primary btn-outline" type="submit">Add Comment</button>
                            </div>
                          </div>
                        </div>
                        <input type="hidden" name="MM_insert" value="supplierFrm" />
                      </form>
                    </div>
                  </div>
                  <!-- End Modal -->
                </div>
              </div>
              <!-- End Example Form Modal -->
            </div>

                </div>
                <!-- /.row -->

               

            </div>
            <!-- /.container-fluid -->

        </div>
        
        <?php
mysql_free_result($supplierSet);
?>
