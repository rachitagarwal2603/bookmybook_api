<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rawPostData = file_get_contents("php://input");
    $postData = (array)json_decode($rawPostData);
}
	$response = array();
	
	if (isset($postData['username']) && isset($postData['password']))
	{
	  $username = $postData['username'];
      $password = $postData['password'];
	
	
	    // include db config class
    require_once __DIR__ . '/db_config.php';
 
    // connecting to db
    //$db = new DB_CONNECT();
	$con = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysql_error());
	
	$search= mysqli_query($con, "SELECT * FROM admin WHERE username = '$username' AND password = '$password'") or die ("Could not search");
	
	mysqli_close($con);
	
    // check if row inserted or not
    if (mysqli_num_rows($search)==0) {
        // successfully inserted into database
        $response["success"] = 0;
        $response["message"] = "LOGIN FAILED.";
 
        // echoing JSON response
        echo json_encode($response);
    } else if(mysqli_num_rows($search)==1){
        // failed to insert row
        $response["success"] = 1;
        $response["message"] = "Hello Admin.";
 
        // echoing JSON response
        echo json_encode($response);
    } else {
		$response["success"] = 0;
        $response["message"] = "Error Occured";
 
        // echoing JSON response
        echo json_encode($response);
	}
	} else {
    // required field is missing
	
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";
    //$response["message"] = "" . ;
 
    // echoing JSON response
    echo json_encode($response);
}
?>