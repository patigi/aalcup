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

$maxRows_drugset = 10;
$pageNum_drugset = 0;
if (isset($_GET['pageNum_drugset'])) {
  $pageNum_drugset = $_GET['pageNum_drugset'];
}
$startRow_drugset = $pageNum_drugset * $maxRows_drugset;

mysql_select_db($database_pharmConn, $pharmConn);
$query_drugset = "SELECT *, (@rownum := @rownum+1) AS sn  FROM registerdrug, (SELECT @rownum:=0)t";
$query_limit_drugset = sprintf("%s LIMIT %d, %d", $query_drugset, $startRow_drugset, $maxRows_drugset);
$drugset = mysql_query($query_limit_drugset, $pharmConn) or die(mysql_error());
$row_drugset = mysql_fetch_assoc($drugset);

if (isset($_GET['totalRows_drugset'])) {
  $totalRows_drugset = $_GET['totalRows_drugset'];
} else {
  $all_drugset = mysql_query($query_drugset);
  $totalRows_drugset = mysql_num_rows($all_drugset);
}
$totalPages_drugset = ceil($totalRows_drugset/$maxRows_drugset)-1;

mysql_select_db($database_pharmConn, $pharmConn);
$query_drugcatset = "SELECT drug_category.id, drug_category.cat_name FROM drug_category";
$drugcatset = mysql_query($query_drugcatset, $pharmConn) or die(mysql_error());
$row_drugcatset = mysql_fetch_assoc($drugcatset);
$totalRows_drugcatset = mysql_num_rows($drugcatset);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "newproductFrm")) {
  $insertSQL = sprintf("INSERT INTO registerdrug (drugname, drugcategory, ddescription) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['drugname'], "text"),
                       GetSQLValueString($_POST['drugcat'], "text"),
                       GetSQLValueString($_POST['ddescription'], "text"));

  mysql_select_db($database_pharmConn, $pharmConn);
  $Result1 = mysql_query($insertSQL, $pharmConn) or die(mysql_error());
   //$selectDid = mysql_query("SELECT drugid FROM registerdrug where drugname = '{$_POST['drugname']}'");
 	$drudname = $_POST['drugname'];
	echo $drugname;
   //$insertDid = sprintf("INSERT INTO stock (stockdid) VALUES (%s)",
   mysql_query("INSERT INTO stock (stockdid) SELECT drugid FROM registerdrug WHERE drugname ='$drudname' ") or die (mysql_error());

  $insertGoTo = "index.php?x6";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
}
?>
<div id="page-wrapper">

            <div class="container-fluid">

                

                <div class="row">
                    <div class="col-lg-8">
                        <h2>Drug List</h2>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                  <tr>
                                    <th>S/N</th>
                                    <th>Drug Name</th>
                                    <th>Description</th>
                                    </tr>
                                  </thead>
                                <tbody>
                            <?php do { ?>
                                  <tr>
                                    <td><?php echo $row_drugset['sn']; ?></td>
                                    <td><?php echo $row_drugset['drugname']; ?></td>
                                    <td><?php echo $row_drugset['ddescription']; ?></td>
                                    </tr>
                              <?php } while ($row_drugset = mysql_fetch_assoc($drugset)); ?>
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
                      <form action="<?php echo $editFormAction; ?>" method="POST" class="modal-content" name="newproductFrm">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                          </button>
                          <h4 class="modal-title" id="exampleFormModalLabel">Add New Drug</h4>
                        </div>
                        <div class="modal-body">
                          <div class="row">
                            <div class="col-lg-6 form-group">
                              <select type="text" class="form-control" name="drugcat">
                                <option value="">Select Category</option>
                                <?php
do {  
?>
                                <option value="<?php echo $row_drugcatset['id']?>"><?php echo $row_drugcatset['cat_name']?></option>
                                <?php
} while ($row_drugcatset = mysql_fetch_assoc($drugcatset));
  $rows = mysql_num_rows($drugcatset);
  if($rows > 0) {
      mysql_data_seek($drugcatset, 0);
	  $row_drugcatset = mysql_fetch_assoc($drugcatset);
  }
?>
                              </select>
                            </div>
                            <div class="col-lg-6 form-group">
                              <input type="text" class="form-control" name="drugname" placeholder="Name Name">
                            </div>
                            <div class="col-lg-8 form-group">
                              <textarea type="text" class="form-control" name="ddescription" placeholder="Drug Description"></textarea>
                            </div>
                            <div class="col-sm-12 pull-right">
                              <button class="btn btn-primary btn-outline" type="submit">Add Comment</button>
                            </div>
                          </div>
                        </div>
                        <input type="hidden" name="MM_insert" value="newproductFrm" />
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
mysql_free_result($drugset);

mysql_free_result($drugcatset);
?>
