<?php
if (!isset($_SESSION)) {
  session_start();
}

 require_once('Connections/pharmConn.php'); 
 ?>
<?php
echo  $_SESSION['MM_UserGroup'] ;
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
	mysql_select_db($database_pharmConn, $pharmConn);
$query_scheduleOrderSet = "SELECT *, (@rownum := @rownum+1) AS sn FROM pharm_users, (SELECT @rownum:=0)t ORDER BY user_id DESC";
$query_limit_scheduleOrderSet = sprintf("%s LIMIT %d, %d", $query_scheduleOrderSet, $startRow_scheduleOrderSet, $maxRows_scheduleOrderSet);
$scheduleOrderSet = mysql_query($query_limit_scheduleOrderSet, $pharmConn) or die(mysql_error());
$row_scheduleOrderSet = mysql_fetch_assoc($scheduleOrderSet);

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

//--------------------------supplier query-----------------------------------------
mysql_select_db($database_pharmConn, $pharmConn);
$query_supplierSet = "SELECT supplier.supId, supplier.supName FROM supplier";
$supplierSet = mysql_query($query_supplierSet, $pharmConn) or die(mysql_error());
$row_supplierSet = mysql_fetch_assoc($supplierSet);
$totalRows_supplierSet = mysql_num_rows($supplierSet);

//-------------------------- select last ID order_id -----------------------------------------
mysql_select_db($database_pharmConn, $pharmConn);
$query_orderId = "SELECT max(order_id) as orderId FROM scheduleorder";
$orderIdSet = mysql_query($query_orderId, $pharmConn) or die(mysql_error());
$row_orderIdSet = mysql_fetch_assoc($orderIdSet);
$totalRows_orderIdSet = mysql_num_rows($orderIdSet);
if($totalRows_orderIdSet<=0){
	$orderId = 00001;
}else{
	$orderId1 = $row_orderIdSet['orderId']+1;
	$orderId = "0000".$orderId1;
};

//--------------- Adding Procurement ------------------------------------------
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "schedulefrm")) {
  $insertSQL = sprintf("INSERT INTO pharm_users (firstname, lastname, position, user_name, user_password, access_level, datead) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['firstname'], "text"),
                       GetSQLValueString($_POST['lastname'], "text"),
                       GetSQLValueString($_POST['position'], "text"),
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['password'], "text"),
                       GetSQLValueString($_POST['access_level'], "text"),
                       GetSQLValueString($_POST['datead'], "text"));

  mysql_select_db($database_pharmConn, $pharmConn);
  $Result1 = mysql_query($insertSQL, $pharmConn) or die(mysql_error());
   ?>
  <script>
  window.location = "index2.php?x11";
  </script>
  <?php
}

?>
<script type="text/javascript">
$(document).ready(function(){
    $('.search-box input[type="text"]').on("keyup input", function(){
        /* Get input value on change */
        var inputVal = $(this).val();
        var resultDropdown = $(this).siblings(".result");
		var resultInput = $(".inputId");
        if(inputVal.length){
            $.get("backend-search.php", {term: inputVal}).done(function(data){
                // Display the returned data in browser
				//var num = data.split(/(\d+)/);
				//alert(num[0]);
				//var letr = "foofo21".match(/a-zA-z/g);
				//alert(letter);
                resultDropdown.html(data);
            });
        } else{
            resultDropdown.empty();
        }
    });
    
    // Set search input value on click of result item
    $(document).on("click", ".result p", function(){
		//var splitt = $(".result p").text();
		var splitt = $(this).text();
		var splitted = splitt.split("-");
		var splitted1 = splitted[0];
		var splitted2 = splitted[1];
		$(this).parents(".search-box").find('input[type="text"]').val(splitted1);
        //$(this).parents(".search-box").find('input[type="text"]').val($(this).text());
        $(this).parent(".result").empty();
		$(".inputId").val(splitted[1]);

		
    });
});
$(document).ready(function(){
    $('.search_sch input[type="text"]').on("keyup input", function(){
        /* Get input value on change */
        var inputVal2 = $(this).val();
        var resultTable = $(".table_search");
        //if(inputVal2.length){
            $.get("table-search.php", {term: inputVal2}).done(function(data){
                // Display the returned data in browser
				//var num = data.split(/(\d+)/);
				//alert(num[0]);
				//var letr = "foofo21".match(/a-zA-z/g);
				//alert(letter);
				
                resultTable.html(data);
            });
        //}
    });
    
    
});
$(document).on("click", "a.facebox", function() {
  // I've never used facebox, but according to the docs (link below), this should work.
  $.facebox({ ajax: $(this).attr('href') });
});
</script>
<div id="page-wrapper" class="row">
	<div class="col-lg-5">
                      <form action="<?php echo $editFormAction; ?>" method="POST" name="schedulefrm">
                        <div class="modal-header">
                            <h4 class="modal-title" id="exampleFormModalLabel">Stock Schedule</h4>
                        </div>
                        <div class="modal-body">
                          <div class="row">
                           
                           <div class="col-lg-6 form-group">
                            <label>First Name</label>
								<input class="form-control" type="text" autocomplete="off" name="firstname" required="required" />
								<div class="result"></div>
						<input type="hidden" value="<?php echo date('Y-m-d'); ?>" name="datead" />
							</div>
                           <div class="col-lg-6 form-group">
                            <label>Last Name</label>
								<input class="form-control" type="text" autocomplete="off" name="lastname" required="required" />
								<div class="result"></div>
							</div>
                           <div class="col-lg-6 form-group ">
                            <label>Username</label>
								<input class="form-control" type="text" autocomplete="off" name="username" required="required" />
								<div class="result"></div>
							</div>
                           <div class="col-lg-6 form-group ">
                            <label>Password</label>
								<input class="form-control" type="text" autocomplete="off" name="password" required="required" />
								<div class="result"></div>
							</div>
                            <div class="col-lg-6 form-group">
                            <label>Position</label>
                              <select class="form-control" name="position" id="position" required="required">
                                <option value="">Select Position</option>
                                <option value="Director">Director</option>
                                <option value="Manager">Manager</option>
                                <option value="Dispensary">Dispensary</option>
                              </select>
                            </div>
                            <div class="col-lg-6 form-group">
                            <label>Access Level</label>
                              <select class="form-control" name="access_level" id="access_level" required="required">
                                <option value="">Select Access Level</option>
                                <option value="Director">Director</option>
                                <option value="Manager">Manager</option>
                                <option value="Dispensary">Dispensary</option>
                              </select>
                            </div>
                                                                                
                            <div class="col-sm-12 pull-right">
                              <button class="btn btn-primary btn-outline" type="submit">Save</button>
                            </div>
                            
                          </div>
                        </div>
                        <input type="hidden" name="MM_insert" value="schedulefrm" />
                      </form>
		</div>
            <div class="col-lg-7">
            <div class="row">
            
              <div class="row">
                    <div class="col-lg-12">
					<div class="modal-header">
                            <h4 class="modal-title" id="exampleFormModalLabel">Order Schedule</h4>
                    </div>
					<br/>
			<div class="input-group col-md-12">  
				<form class="" method="post" action="index2.php?x2">  
					<div class="form-group col-md-6 search_sch" >            
						<input class="form-control" data-plugin="datepicker" name="search" placeholder="Search..." type="text">
                    </div>
                    <div class="col-md-2">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                    </div>
                </form>
                     <!--<a class="btn btn-primary pull-right" href="addorder.php" rel="facebox">Add more Drug</a>--->
            </div>                        <div class="table-responsive table_search">
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>First Name</th>
                                        <th>Surname</th>
                                        <th>Position</th>
                                        <th>Access Level</th>
                                        <th>Username</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php do { ?>
  <tr>
    <td><?php echo $row_scheduleOrderSet['sn']; ?></td>
    <td><?php echo $row_scheduleOrderSet['firstname']; ?></td>
    <td><?php echo $row_scheduleOrderSet['lastname']; ?></td>
    <td><?php echo $row_scheduleOrderSet['position']; ?></td>
    <td><?php echo $row_scheduleOrderSet['access_level']; ?></td>
    <td><?php echo $row_scheduleOrderSet['user_name']; ?></td>
    <td><a href="edit_user.php?edid=<?php echo $row_scheduleOrderSet['user_id']; ?>" rel="facebox" class="btn btn-sm btn-info" type="button">Edit</a>
	<a href="delete_user.php?delid=<?php echo $row_scheduleOrderSet['user_id']; ?>" onclick="return confirm('Are you sure you want to delete this item');" class="btn btn-sm btn-danger" type="button">Delete</a>
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
		</div>
<?php
mysql_free_result($scheduleOrderSet);


?>
