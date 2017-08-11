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
//die($_POST['name']);
// check for required fields	
//if (isset($_POST['bookName'])) {
if (isset($postData['bookName'])) {

	//die("error");
    $bookName = $postData['bookName'];
    //$bookName = $_POST['bookName'];
 
    // include db config class
    require_once __DIR__ . '/db_config.php';
 
    // connecting to db
    //$db = new DB_CONNECT();
	$con = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysql_error());
		
	//die("$search");
	$search = mysqli_query($con, "SELECT User_Name, Content FROM reviews WHERE Book_Name = '$bookName'") or die ("Could not add Review");
	$picsearch = mysqli_query($con, "SELECT image_path FROM book_pic WHERE Book_Name = '$bookName'");
	
	$response["pics"] = array();
		
		while($row=mysqli_fetch_array($picsearch)){
			$temp=array();
			$temp['image'] = $row['image_path'];
			array_push($response['pics'], $temp);
		}
	
	if(mysqli_num_rows($search)>0){
		
        $response["success"] = "1";
        $response["message"] = "Review successfully fetched.";
		$response["review"] = array();
		
		
		while($row=mysqli_fetch_array($search)){
			$temp=array();
			$temp['userName'] = $row['User_Name'];
			$temp['content'] = $row['Content'];
			array_push($response['review'],$temp);
		}
 
        // echoing JSON response
        echo json_encode($response);
    } else {
        // failed to insert row
        $response["success"] = "0";
        $response["message"] = "Review not available.";
 
        // echoing JSON response
        echo json_encode($response);
    } 
	mysqli_close($con);
} else {
    // required field is missing
	
    $response["success"] = "0";
    $response["message"] = "Required field(s) is missing";
    //$response["message"] = "" . ;
 
    // echoing JSON response
    echo json_encode($response);
}
?>