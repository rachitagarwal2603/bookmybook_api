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
//die($_GET['name']);
// check for required fields	
//if (isset($_POST['bookName']) && isset($_POST['userName']) && isset($_POST['content']) && isset($_POST['rating'])) {
if (isset($postData['bookName']) && isset($postData['userName']) && isset($postData['content']) && isset($postData['rating'])) {

	//die("error");
    $bookName = $postData['bookName'];
    //$bookName = $_POST['bookName'];
    $userName = $postData['userName'];
    //$userName = $_POST['userName'];
    //$content = $_POST['content'];
    $content = $postData['content'];
    $rating = $postData['rating'];
    //$rating = $_POST['rating'];
 
    // include db config class
    require_once __DIR__ . '/db_config.php';
 
    // connecting to db
    //$db = new DB_CONNECT();
	$con = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysql_error());
	
	//die("$search");
	
    // mysql inserting a new row
   // $result = mysqli_query($con, "INSERT INTO reviews(Book_Name, User_Name, Content) VALUES('$bookName', '$userName', '$content')");
 
	
    // check if row inserted or not
    if ($result = mysqli_query($con, "INSERT INTO reviews(Book_Name, User_Name, Content) VALUES('$bookName', '$userName', '$content')") or die ("Could not add Review")) {
        
		mysqli_query($con, "UPDATE book_details SET Total_Rating = Total_Rating+1, Rating='$rating' WHERE Book_Name = '$bookName'");
		// successfully inserted into database
        $response["success"] = "1";
        $response["message"] = "Review successfully added.";
 
        // echoing JSON response
        echo json_encode($response);
    } else {
        // failed to insert row
        $response["success"] = "0";
        $response["message"] = "Review not added.";
 
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