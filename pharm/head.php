<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['cusId']);
  unset($_SESSION['cusPid']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "index.php?logout";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php require_once('Connections/pharmConn.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = array("Manager", "Director", "Dispensary");
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $strGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index.php?status=fail";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("", $MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}

//================== current sales balance ==============================================
$to_date =date('Y-m-d');
  mysql_select_db($database_pharmConn, $pharmConn);
$current_sales ="SELECT sum(scusCost * scusQty) AS current_sales from sale where scusDate = '$to_date' GROUP BY  username";
$row_current_sales = mysql_query($current_sales, $pharmConn) or die(mysql_error());
$result_current_sales = mysql_fetch_array($row_current_sales);
$total_customer_check = mysql_num_rows($row_current_sales);


?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Nababa Pharmacy Management</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="css/plugins/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="bootstrap-datepicker/bootstrap-datepicker.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

<script src="facefiles/jquery-1.2.2.pack.js" type="text/javascript"></script>
<link href="facefiles/facebox.css" media="screen" rel="stylesheet" type="text/css" />
<script src="facefiles/facebox.js" type="text/javascript"></script>

<script type="text/javascript">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox() 
    })
</script>

<script>  
function loaddrug(){
	var given;
	if (window.XMLHttpRequest)
	{ 
	given=new XMLHttpRequest();
	}else{
	given=new ActiveXObject("Microsoft.XMLHTTP");
	}
	given.onreadystatechange=function()
	{
		if (given.readyState==4 && given.status==200){
			document.getElementById('drugname').innerHTML = given.responseText;
	}
	}
	var cat = document.getElementById('category').value;
	var vrb = "?drugcat=" + cat;
	given.open("GET", "postcat.php" + vrb, true);
	given.send(null);
}
</script>
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index2.php?x1">Nababa Phamarcy Management</a>
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bank"><?php echo 'N'.number_format($result_current_sales['current_sales'], 2); ?></i> <b class="caret"></b></a>
                    
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $_SESSION['MM_Username']; ?> <b class="caret"></b></a>
                  <ul class="dropdown-menu">
                        <li>
                            <a href="#"><i class="fa fa-fw fa-user"></i> Profile</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="<?php echo $logoutAction ?>"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li class="active">
                        <a href="index2.php?x1"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="index2.php?x2" style="color:#FFF"><i class="fa fa-fw fa-binoculars"></i>Procurement</a>
                    </li>
                    <li>
                        <a style="color:#FFF" href="index2.php?x5"><i class="fa fa-fw fa-newspaper-o"></i>New Stock</a>
                    </li> <li>
                        <a style="color:#FFF" href="index2.php?x10"><i class="fa fa-fw fa-newspaper-o"></i>Store</a>
                    </li>
                    <li>
                        <a href="index2.php?x3" style="color:#FFF"><i class="fa fa-fw fa-plus"></i>Add Supplier</a>
                    </li>
                    <li>
                        <a href="index2.php?x4&set" style="color:#FFF"><i class="fa fa-fw fa-share"></i>Dispense Drug</a>
                    </li>
                    <li>
                        <a href="bootstrap-grid.html"><i class="fa fa-fw fa-wrench"></i> Returned Order</a>
                    </li>
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-arrows-v"></i> Damaged Stock<i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo" class="collapse">
                            <li>
                                <a href="#">Dropdown Item</a>
                            </li>
                            <li>
                                <a href="#">Dropdown Item</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="index2.php?x6" style="color:#FFF"><i class="fa fa-fw fa-folder"></i>Register Drug!</a>
                    </li>
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#demo2"><i class="fa fa-fw fa-arrows-v"></i> Report<i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo2" class="collapse">
                        <li>
                        <a href="index2.php?x8" style="color:#FFF"><i class="fa fa-fw fa-folder"></i>Reports!</a>
                        </li>
                        <li>
                        <a href="index2.php?x9" style="color:#FFF"><i class="fa fa-fw fa-folder"></i>Bincard!</a>
                        </li>
                        </ul>
                    </li>
                    <li>
                        <a href="index2.php?x11"><i class="fa fa-fw fa-user"></i> Add User</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>

        
