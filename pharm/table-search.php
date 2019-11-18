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
    $sql = "SELECT scheduleorder.id, scheduleorder.order_id,  scheduleorder.Schdrugid, scheduleorder.dSupplier, scheduleorder.dQty, scheduleorder.dUnit, scheduleorder.dCost,  registerdrug.drugname, supplier.supName, scheduleorder.id, purchasetbl.purchase_id, (@rownum := @rownum+1) AS sn FROM scheduleorder LEFT JOIN registerdrug ON scheduleorder.Schdrugid=registerdrug.drugid  LEFT JOIN supplier ON scheduleorder.dSupplier=supplier.supId LEFT JOIN purchasetbl ON scheduleorder.order_id = purchasetbl.purchase_id, (SELECT @rownum:=0)t WHERE scheduleorder.in_store = 'NO' AND registerdrug.drugname LIKE '$var%' ORDER BY id DESC";
	 }
	 else{
	    $sql = "SELECT scheduleorder.id, scheduleorder.order_id,  scheduleorder.Schdrugid, scheduleorder.dSupplier, scheduleorder.dQty, scheduleorder.dUnit, scheduleorder.dCost,  registerdrug.drugname, supplier.supName, scheduleorder.id, purchasetbl.purchase_id, (@rownum := @rownum+1) AS sn FROM scheduleorder LEFT JOIN registerdrug ON scheduleorder.Schdrugid=registerdrug.drugid  LEFT JOIN supplier ON scheduleorder.dSupplier=supplier.supId LEFT JOIN purchasetbl ON scheduleorder.order_id = purchasetbl.purchase_id, (SELECT @rownum:=0)t WHERE scheduleorder.in_store = 'NO' ORDER BY id DESC";
	 }
    if($stmt = mysqli_query($link, $sql)){
      
        
       
            if(mysqli_num_rows($stmt) > 0){ ?>
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
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
         <?php while($row_scheduleOrderSet = mysqli_fetch_array($stmt)){ ?>                         
  <tr>
    <td><?php echo $row_scheduleOrderSet['sn']; ?></td>
    <td><?php echo $row_scheduleOrderSet['drugname']; ?></td>
    <td><?php echo $row_scheduleOrderSet['supName']; ?></td>
    <td><?php echo $row_scheduleOrderSet['dUnit']; ?></td>
    <td><?php echo $row_scheduleOrderSet['dQty']; ?></td>
    <td><?php echo $row_scheduleOrderSet['dCost']; ?></td>
    <td><?php if(isset($row_scheduleOrderSet['purchase_id'])){ ?>Already Added<?php }else {?><a href="addtostore.php?id=<?php echo $row_scheduleOrderSet['id']; ?>&purchid=<?php echo $row_scheduleOrderSet['transaction_id']; ?>" class="btn btn-sm btn-primary" type="button" rel="facebox">Add to Store</a>
<?php }?></td>
    <td><a href="delete_order.php?orderid=<?php echo $row_scheduleOrderSet['id']; ?>" onclick="return confirm('Are you sure you want to delete this item');" class="btn btn-sm btn-danger" type="button">Delete</a>
      </td>
  </tr>
		 <?php }?>
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
	  <?php
                }
            } 
    }
?>
<script src="facefiles/jquery-1.2.2.pack.js" type="text/javascript"></script>
<link href="facefiles/facebox.css" media="screen" rel="stylesheet" type="text/css" />
<script src="facefiles/facebox.js" type="text/javascript"></script>

<script type="text/javascript">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox() 
    })
</script>

