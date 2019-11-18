<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_pharmConn = "localhost";
$database_pharmConn = "pharm";
$username_pharmConn = "root";
$password_pharmConn = "";
$pharmConn = mysql_pconnect($hostname_pharmConn, $username_pharmConn, $password_pharmConn) or trigger_error(mysql_error(),E_USER_ERROR); 
?>