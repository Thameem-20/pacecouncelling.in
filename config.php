
<?php 
$host = $_SERVER['HTTP_HOST'];

if (strpos($host, 'intelexsolutions-test.site') !== false) {
    // If the URL contains 'intelexsolutions-test.site', use this connection
    $conn = new mysqli("localhost", "u593219986_couselling", "2rBnBsRbZ@Gq", "u593219986_couselling");
    // echo "this is in server";

} else {
    // Otherwise, use this connection
    $conn = new mysqli("localhost", "root", "", "counselling");
    // echo "this is in local host";

}

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);

}

?>