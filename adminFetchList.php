<?php
 
/*
 * Following code will create a new product row
 * All product details are read from HTTP Post Request
 */
 
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
	$search= mysqli_query($con, "SELECT Book_Name, Available, Issues FROM book_details") or die ("Could not search");
	
    // check if row inserted or not
    if (mysqli_num_rows($search)>0) {
        // successfully inserted into database
		$response["success"]=1;
		$response["message"]="List generated";
		$response["bookList"]=array();
 
		while($row =mysqli_fetch_array($search)){
			$temp = array();
			$temp['bookName'] = $row['Book_Name'];
			$temp['available'] = $row['Available'];
			$temp['issues'] = $row['Issues'];
			array_push($response['bookList'],$temp);
		}
 
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