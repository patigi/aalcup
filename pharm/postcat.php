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
$drugcat = $_GET['drugcat'];
mysql_select_db($database_pharmConn, $pharmConn);
$query_drugnameset = "SELECT registerdrug.drugid, registerdrug.drugname, registerdrug.drugcategory FROM registerdrug WHERE registerdrug.drugcategory = '$drugcat'";
$drugnameset = mysql_query($query_drugnameset, $pharmConn) or die(mysql_error());
$row_drugnameset = mysql_fetch_assoc($drugnameset);
$totalRows_drugnameset = mysql_num_rows($drugnameset);
?>
<select>
  <?php
do {  
?>
  <option value="<?php echo $row_drugnameset['drugid']?>"><?php echo $row_drugnameset['drugname']?></option>
  <?php
} while ($row_drugnameset = mysql_fetch_assoc($drugnameset));
  $rows = mysql_num_rows($drugnameset);
  if($rows > 0) {
      mysql_data_seek($drugnameset, 0);
	  $row_drugnameset = mysql_fetch_assoc($drugnameset);
  }
?>
</select>
        <?php

mysql_free_result($drugnameset);
?>
