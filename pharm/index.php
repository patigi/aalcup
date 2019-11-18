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
$query_login_set = "SELECT * FROM pharm_users";
$login_set = mysql_query($query_login_set, $pharmConn) or die(mysql_error());
$row_login_set = mysql_fetch_assoc($login_set);
$totalRows_login_set = mysql_num_rows($login_set);
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "access_level";
  $MM_redirectLoginSuccess = "index2.php?x1";
  $MM_redirectLoginFailed = "index.php?status=fail";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_pharmConn, $pharmConn);
  	
  $LoginRS__query=sprintf("SELECT user_name, user_password, access_level FROM pharm_users WHERE user_name=%s AND user_password=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $pharmConn) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'access_level');
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
   $_SESSION['MM_Username'] = $loginUsername;
   $_SESSION['MM_UserGroup'] = $loginStrGroup;
	

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Nababa Pharmacy Version 1.1</title>
  
<script src="js/jquery.js"></script>

<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/bootstrap.css">
  
</head>

<body>
  <?php $status = $_GET['status'];
  if($status == 'fail'){
	?>
    <div class="pen-title alert-danger"><h2>Your Username or Password is not correct</h2></div>
    
	<?php  
  }
  
  ?>
<div class="module form-module">
  <div class="toggle"><i class="fa fa-times fa-pencil"></i>
  </div>
  <div class="form">
    <h2>Login to your account</h2>

    <form name="login_form" ACTION="<?php echo $loginFormAction; ?>" METHOD="POST">
      <input type="text" name="username"  placeholder="Username"/>
      <input type="password" name="password" placeholder="Password"/>
      <button type="submit">Login</button>
    </form>
  </div>
</div>
<div class="pen-title">Nababa Pharmacy version 1.1</div>


</body>

<?php
mysql_free_result($login_set);
?>
</html>