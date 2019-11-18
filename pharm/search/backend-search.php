<?php
/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
$link = mysqli_connect("localhost", "root", "", "country");
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
 
if(isset($_REQUEST["term"])){
	$var = $_REQUEST["term"];
    // Prepare a select statement
    $sql = "SELECT * FROM countries WHERE name LIKE '$var%'";
    
    if($stmt = mysqli_query($link, $sql)){
      
        
       
            if(mysqli_num_rows($stmt) > 0){
                // Fetch result rows as an associative array
                while($row = mysqli_fetch_array($stmt)){
                    echo "<p>" . $row["name"] . "</p>";
                }
            } else{
                echo "<p>No matches found</p>";
            }
         
    }
     
}
 
?>