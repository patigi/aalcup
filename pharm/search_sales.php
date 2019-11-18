<?php 
if (!isset($_SESSION)) {
  session_start();
}
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
/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
$link = mysqli_connect("localhost", "root", "", "pharm");
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
 
if(isset($_REQUEST["term"])){
	echo $var = $_REQUEST["term"];
    // Prepare a select statement
	 if(($_REQUEST["term"]!=" ") || !empty($_REQUEST["term"])){
    $sql = "SELECT *, sum(stock.stock_qty_in)-sum(stock.stock_qty_out) AS avqty, stock.stockcost, stock.stockprice, registerdrug.drugid, registerdrug.drugname, (@rownum:=@rownum + 1) AS sn FROM stock LEFT JOIN  registerdrug ON  stock.stockdid=registerdrug.drugid, (SELECT @rownum := 0)t WHERE registerdrug.drugname LIKE '$var%' GROUP BY stock.stockdid";
	 }
	 else{
	    $sql = "SELECT *, sum(stock.stock_qty_in)-sum(stock.stock_qty_out) AS avqty, stock.stockcost, stock.stockprice, registerdrug.drugid, registerdrug.drugname, (@rownum:=@rownum + 1) AS sn FROM stock LEFT JOIN  registerdrug ON  stock.stockdid=registerdrug.drugid, (SELECT @rownum := 0)t GROUP BY stock.stockdid";
	 }
    if($stmt = mysqli_query($link, $sql)){
      
        
      
            if(mysqli_num_rows($stmt) > 0){ ?>
         <?php while($row_scheduleOrderSet = mysqli_fetch_array($stmt)){ ?>                         
                                    <tr>
                                    <form id="formId" action="dispense.php" name="form" method="POST">

                                        <td>
										<input type="hidden" value="<?php echo $row_scheduleOrderSet['drugid']; ?>" name="did" id="did"  />
                                        <input type="hidden" value="<?php echo $_SESSION['cusPid']; ?>" name="cusPid" id="cusPid"  />
                                        <input type="hidden" value="<?php echo $_SESSION['cusId']; ?>" name="cusId" id="cusId"  />
                                        <input type="hidden" value="<?php echo $fname; ?>" name="fname" id="fname"  />
                                        <input type="hidden" value="<?php echo $lname; ?>" name="lname"  id="lname"  />
                                        <input type="hidden" value="<?php echo $row_scheduleOrderSet['stockprice']; ?>" name="dCost"  id="dCost"  />
										
										<?php echo $row_scheduleOrderSet['sn']; ?>
										</td>
                                        <td><?php echo $row_scheduleOrderSet['drugname']; ?></td>
                                        <td></td>
                                        <td><?php echo $row_scheduleOrderSet['stockcost']; ?></td>
                                        <td><?php echo $row_scheduleOrderSet['stockprice']; ?></td>
                                        <td><?php echo $row_scheduleOrderSet['avqty']; ?></td>
                                        <td><button class="btn" type="button" onclick="demo();">Submit</button></td>
                                        <td><input type="text" name="qty_order" id="qty_order" maxlength="5" size="3" /></td>
                                    </form>
                                    </tr>
		 <?php }?>
                                
	  <?php
                }
            } 
    }
?>
