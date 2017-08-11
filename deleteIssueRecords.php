<?php
 

// array for JSON response
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rawPostData = file_get_contents("php://input");
    $postData = (array)json_decode($rawPostData);
}

$response = array();
require_once __DIR__ . '/db_config.php';
 
    // connecting to db
    //$db = new DB_CONNECT();
	$con = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysql_error());
	$search = array();	
	$query = mysqli_query($con, "UPDATE book_details SET Issues = '0'");
    // check if row inserted or not
    if ($search= mysqli_query($con, "DELETE FROM book_issue") or die ("Could not search"))
	{
        // successfully inserted into database
    $response["success"]=1;
	$response["message"]="Issue Table Deleted";
 
    // echoing JSON response
    echo json_encode($response);
    }
	else {
		$response["success"] = 0;
        $response["message"] = "Error Occured";
 
        // echoing JSON response
        echo json_encode($response);
	}
	mysqli_close($con);
?>