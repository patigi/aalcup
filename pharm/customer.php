<?php require_once('Connections/pharmConn.php'); ?>
<?php 
$fname = isset($_POST['cusFname']) ? $_POST['cusFname'] : $_SESSION['fname'];
$_SESSION['fname'] = $fname;
$lname = isset($_POST['cusLname']) ? $_POST['cusLname'] : $_SESSION['lname'];
$_SESSION['lname'] = $lname;

$rand = rand(1, 1000000);
$rand2 = 'p'.rand(1, 1000000);
$_SESSION['cusPid'] = !isset($_SESSION['cusPid']) ? $rand : $_SESSION['cusPid'];
$_SESSION['cusId'] = !isset($_SESSION['cusId']) ? $rand2 : $_SESSION['cusId'];

if(isset($_GET['status']) && $_GET['status']='dispense'){
$cusPid = $_GET['cusPid'];
$cusId = $_GET['cusId'];
$fname = $_GET['fname'];
$lname = $_GET['lname'];

}
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

//=====Check cutomer detail for duplicate entry ==============================================
  mysql_select_db($database_pharmConn, $pharmConn);
$cusId=$_SESSION['cusId'];
$cusPid=$_SESSION['cusPid'];
$check_customer ="SELECT cusId, cusPid from customer where cusId='$cusId' AND cusPid='$cusPid'";
$row_check_customer = mysql_query($check_customer, $pharmConn) or die(mysql_error());
$result_check_customer = mysql_fetch_array($row_check_customer);
$total_customer_check = mysql_num_rows($row_check_customer);

//=====insert new  customer==============================================
if($total_customer_check<1){
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "cusrecord")) {
  $insertSQL = sprintf("INSERT INTO customer (cusId, cusPid, cusFname, cusLname) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_SESSION['cusId'], "text"),
                       GetSQLValueString($_SESSION['cusPid'], "text"),
                       GetSQLValueString($_POST['cusFname'], "text"),
                       GetSQLValueString($_POST['cusLname'], "text"));

  mysql_select_db($database_pharmConn, $pharmConn);
  $Result1 = mysql_query($insertSQL, $pharmConn) or die(mysql_error());

}
}
//----- Recordset of drugs from the stock------------------------------------
mysql_select_db($database_pharmConn, $pharmConn);

$query_stockSet = "SELECT *, sum(stock.stock_qty_in)-sum(stock.stock_qty_out) AS avqty, stock.stockcost, stock.stockprice, registerdrug.drugid, registerdrug.drugname, (@rownum:=@rownum + 1) AS sn FROM stock LEFT JOIN  registerdrug ON  stock.stockdid=registerdrug.drugid, (SELECT @rownum := 0)t GROUP BY stock.stockdid";
$stockSet = mysql_query($query_stockSet, $pharmConn) or die(mysql_error());
$row_stockSet = mysql_fetch_assoc($stockSet);
$totalRows_stockSet = mysql_num_rows($stockSet);

mysql_select_db($database_pharmConn, $pharmConn);
$query_customerOrder = "SELECT sale.scusDid, sale.scusQty, sale.scusCost, sale.saleId, registerdrug.drugname, (@rownum:=@rownum + 1) AS sn FROM sale LEFT JOIN registerdrug ON  sale.scusDid= registerdrug.drugid, (SELECT @rownum:=0)t WHERE sale.salecusId = '$cusId' AND sale.scusPid = '$cusPid'";
$customerOrder = mysql_query($query_customerOrder, $pharmConn) or die(mysql_error());
$row_customerOrder = mysql_fetch_assoc($customerOrder);
$totalRows_customerOrder = mysql_num_rows($customerOrder);

mysql_select_db($database_pharmConn, $pharmConn);
$query_customerOrder_sum = "SELECT sale.scusDid, sale.scusQty, sum(sale.scusCost*sale.scusQty) as sum FROM sale WHERE sale.salecusId = '$cusId' AND sale.scusPid = '$cusPid' GROUP BY sale.salecusId";
$customerOrder_sum = mysql_query($query_customerOrder_sum, $pharmConn) or die(mysql_error());
$row_customerOrder_sum = mysql_fetch_assoc($customerOrder_sum);
$totalRows_customerOrder_sum = mysql_num_rows($customerOrder_sum);
?>
<script>
function showModal(){
$("#exampleFormModal").modal('show');
}
</script>

<script>
$(document).ready(function(){
    $('.search_sch input[type="text"]').on("keyup input", function(){
        /* Get input value on change */
        var inputVal2 = $(this).val();
        var resultTable = $(".table_search");
        //if(inputVal2.length){
            $.get("search_sales.php", {term: inputVal2}).done(function(data){
                // Display the returned data in browser
				//var num = data.split(/(\d+)/);
				//alert(num[0]);
				//var letr = "foofo21".match(/a-zA-z/g);
				//alert(letter);
				
                resultTable.html(data);
            });
        //}
    });
    
    
	$("body").delegate('#del', 'click', function(){
		var dat = $(this).data('id');
			
			$.ajax({
		type: "post",
		url: "dispense.php",
		async: false,
		data: {
			"deletedata" : 1,
			"delid": dat
		},
		success: function(de){
		if(de==1){
			alert("Successfully delete data");
			show();
			return false;
			//show2();
		}else
		{
			alert("Failed to delete data");
			return false;
		}
		
			
		
		}
		});
			
		});
});
$(document).on("click", "a.facebox", function() {
  // I've never used facebox, but according to the docs (link below), this should work.
  $.facebox({ ajax: $(this).attr('href') });
});

function demo() {
		show();
		var cusPid = $('#cusPid').val();
		var cusId = $('#cusId').val();
		var fname = $('#fname').val();
		var lname = $('#lname').val();
		var dCost = $('#dCost').val();
		var qty_order = $('#qty_order').val();
		var did = $('#did').val();
		
		$.ajax({
		type: "post",
		url: "dispense.php",
		async: false,
		data: {
			"savedata" : 1,
			"cusPid" : cusPid,
			"cusId" : cusId,
			"fname" : fname,
			"lname" : lname,
			"dCost" : dCost,
			"did" : did,
			"qty_order" : qty_order
			
		},
		success: function(e){
		if(e==1){
			alert("Successfully save data");
			show();
			show2();
		}else
		{
			alert("Failed to save data");
		}
		
			
		
		}
		});
		
    }
	function show() {
				
		$.ajax({
		type: "post",
		url: "dispense.php",
		async: false,
		data: {
			"showdata" : 1
			
		},
		success: function(re){
			//alert(re);
		$('.customer_order').html(re);
		return false;
		}
		});
		
    }
	function show2() {
		var search_input = $('.search_sch input[type="text"]').val();		
		//alert(search_input);
		$.ajax({
		type: "post",
		url: "search_sales.php",
		async: false,
		data: {
			"term" : search_input
			
		},
		success: function(re){
			//alert(re);
		$('.table_search').html(re);
		return false;
		}
		});
		
    }
	
</script>
<?php
if((isset($_GET['savedata']))&&($_GET['savedata']==2)){
	?>
	<script>
	$('document').ready(function(){
	alert('Successfully save data');
	});
	</script>
	<?php
}
?>

<body>
<div id="page-wrapper">

<div class="container-fluid">

                <div class="row">
                    <div class="col-lg-7">
                        <h2>Available Stock</h2>
                        <div class="table-responsive">
							<form class="" method="post" action="index2.php?x2">  
								<div class="form-group col-md-8 search_sch" >            
									<input class="form-control" data-plugin="datepicker" name="search" placeholder="Search..." type="text">
								</div>
							</form>
							
                            <table class="table table-responsive table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Item</th>
                                        <th>Unit</th>
                                        <th>Unit Price</th>
                                        <th>Unit Cost</th>
                                        <th>Available Qty</th>
                                        <th>Dispense</th>
                                        <th>Qty Order</th>
                                    </tr>
                                </thead>
                                <tbody class="table_search">
                                    <?php do {  ?>
                                    
                                    <tr>
                                    <form id="formId" action="dispense.php" name="form" method="post">
                                        <td>
										<input type="hidden" value="<?php echo $row_stockSet['drugid']; ?>" name="did" id="did"  />
                                        <input type="hidden" value="<?php echo $_SESSION['cusPid']; ?>" name="cusPid" id="cusPid"  />
                                        <input type="hidden" value="<?php echo $_SESSION['cusId']; ?>" name="cusId" id="cusId"  />
                                        <input type="hidden" value="<?php echo $fname; ?>" name="fname" id="fname"  />
                                        <input type="hidden" value="<?php echo $lname; ?>" name="lname"  id="lname"  />
                                        <input type="hidden" value="<?php echo $row_stockSet['stockprice']; ?>" name="dCost"  id="dCost"  />
										
										<?php echo $row_scheduleOrderSet['sn']; ?>
										</td>
                                        <td><?php echo $row_stockSet['drugname']; ?></td>
                                        <td></td>
                                        <td><?php echo $row_stockSet['stockcost']; ?></td>
                                        <td><?php echo $row_stockSet['stockprice']; ?></td>
                                        <td><?php echo $row_stockSet['avqty']; ?></td>
                                        <td><button class="btn" type="submit">Submit</button></td>
                                        <td><input type="text" class="qty_order" name="qty_order" id="qty_order" maxlength="5" size="3" /></td>
										<input type="hidden" name="savedata" value="2" />
                                    </form>
                                      </tr>
                                      <?php } while ($row_stockSet = mysql_fetch_assoc($stockSet)); ?>
                                </tbody>
                            </table>
                        </div>
                        </div>
                        <div class="col-lg-5">
                        <table class="table table-bordered table-hover table-striped">
                               <h3>Customer Orders</h3>
                               <h4><a href="#" onclick="showModal();"><?php echo $_SESSION['fname']; ?>&nbsp;<?php echo $_SESSION['lname']; ?></a>&nbsp;&nbsp;<a href="#" onclick="showModal();">Edit</a></h4>
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>item</th>
                                        <th>Unit</th>
                                        <th>Qty</th>
                                        <th>Unit Price</th>
                                        <th>Cost</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="customer_order">
                                  <?php do { ?>
  <tr>
    <td><?php echo $row_customerOrder['sn']; ?></td>
    <td><?php echo $row_customerOrder['drugname']; ?></td>
    <td></td>
    <td><?php echo $row_customerOrder['scusQty']; ?></td>
    <td><?php echo $row_customerOrder['scusCost']; ?></td>
    <td><?php echo $row_customerOrder['scusCost']*$row_customerOrder['scusQty']; ?></td>
    <td><button id="del" data-id="<?php echo $row_customerOrder['saleId']; ?>" >Delete</button></td>
  </tr>
  <?php } while ($row_customerOrder = mysql_fetch_assoc($customerOrder)); ?>
                				<td colspan="5">Total</td>
                				<td colspan="3"><?php echo 'N'.number_format($row_customerOrder_sum['sum'],2); ?></td>
                                </tbody>
                            </table>
                                <a href="print_customer_receipt.php?cusPid=<?php echo $cusPid; ?>&cusId=<?php echo $cusId; ?>&fname=<?php echo $fname; ?>&lname=<?php echo $lname; ?>" class="btn btn-info">Print</a>                        </div>
                    </div>
                    </div>
                    
                  <div class="row row-lg">
            <div class="col-lg-4 col-md-6">
              <!-- Example Form Modal -->
              <div class="example-wrap">
                <div class="example">
                  <div class="example-well height-350">
                  </div>
                  <!-- Modal -->
                  <div class="modal fade" id="exampleFormModal" aria-hidden="false" aria-labelledby="exampleFormModalLabel"
                  role="dialog" tabindex="-1">
                    <div class="modal-dialog">
                      <form class="modal-content" method="POST" name="cusrecord" action="index2.php?x4">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                          </button>
                          <h4 class="modal-title" id="exampleFormModalLabel">Stock Schedule</h4>
                        </div>
                        <div class="modal-body">
                          <div class="row">
                            <div class="col-lg-4 form-group">
                              <input type="text" class="form-control" name="cusFname" placeholder="Firs Name" value="<?php echo $_SESSION['fname']; ?>" required="required">
                            </div>
                            <div class="col-lg-4 form-group">
                              <input type="text" class="form-control" name="cusLname" placeholder="Last Name" value="<?php echo $_SESSION['lname']; ?>" required="required">
                              <input type="hidden" value="<?php echo $_SESSION['cusPid']; ?>" name="cusPid" />
                              <input type="hidden" value="<?php echo $_SESSION['cusId']; ?>" name="cusId"/>
                            </div>
                            <div class="col-sm-12 pull-right">
                              <button class="btn btn-primary btn-outline" type="submit">Proceed</button>
                              <input type="hidden" name="MM_insert" value="cusrecord"/>
                            </div>
                          </div>
                        </div>
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
</body>
<?php 
$set = $_GET['set'];
if(isset($set)){ ?>
<script>
$(document).ready(function(){

$("#exampleFormModal").modal('show');
	
});
</script>

<?php 
}
?>

        
        <?php
mysql_free_result($stockSet);

mysql_free_result($customerOrder);
?>
