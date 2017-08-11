<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rawPostData = file_get_contents("php://input");
    $postData = (array)json_decode($rawPostData);
}
	$response = array();
	
	
//if (isset($postData['name']) && isset($postData['rollno']) && isset($postData['contact']) && isset($postData['bookname']) && isset($postData['hostel'])) 
if (isset($_POST['name']) && isset($_POST['rollno']) && isset($_POST['contact']) && isset($_POST['bookname']) && isset($_POST['hostel']))
 {
   /* $name = $postData['name'];
    $rollno = $postData['rollno'];
	$contact = $postData['contact'];
	$bookname = $postData['bookname'];
	$hostel = $postData['hostel'];
	*/
	
	$name = $_POST['name'];
    $rollno = $_POST['rollno'];
	$contact = $_POST['contact'];
	$bookname = $_POST['bookname'];
	$hostel = $_POST['hostel'];
 
    // include db config class
    require_once __DIR__ . '/db_config.php';
 
    // connecting to db
    //$db = new DB_CONNECT();
	$con = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysql_error());
	
	$searchForUpdate= mysqli_query($con, "SELECT bookIssued FROM book_issue WHERE rollNo = '$rollno'") or die ("Could not search");
	$searchForAvailability= mysqli_query($con, "SELECT * FROM book_details WHERE Available > Issues AND Book_Name = '$bookname'");
	mysqli_query($con, "UPDATE book_issue SET name = '$name', phoneNo = '$contact', rollNo= '$rollno', hostel = '$hostel' WHERE rollNo = '$rollno'" );
		
	if(mysqli_num_rows($searchForUpdate)>0){
		if(mysqli_num_rows($searchForAvailability)>0){
			// Add query to decrement value of issues on updating new book
			$arr = mysqli_fetch_array($searchForUpdate);
			$oldBookName = $arr["bookIssued"];
			mysqli_query($con, "UPDATE book_details SET Issues = Issues-1 WHERE Book_Name = '$oldBookName'");
			
			mysqli_query($con, "UPDATE book_details SET Issues = Issues+1 WHERE Book_Name = '$bookname'");
			$result = mysqli_query($con, "UPDATE book_issue SET name = '$name', phoneNo = '$contact', rollNo= '$rollno', hostel = '$hostel', bookIssued='$bookname' WHERE rollNo = '$rollno'" );
		}
	} else if(mysqli_num_rows($searchForAvailability)>0){
		mysqli_query($con, "UPDATE book_details SET Issues = Issues+1 WHERE Book_Name = '$bookname'");
	    $result = mysqli_query($con, "INSERT INTO book_issue(name, phoneNo, rollNo, hostel, bookIssued) VALUES('$name', '$contact', '$rollno', '$hostel', '$bookname')");
	}
	mysqli_close($con);
	
	if (mysqli_num_rows($searchForUpdate)==0 && mysqli_num_rows($searchForAvailability)>0) {
        // successfully inserted into database
        $response["success"] = 1;
        $response["message"] = "Book issued successfully.";
 
        // echoing JSON response
        echo json_encode($response);
    } else if(mysqli_num_rows($searchForAvailability)==0){
		$response["success"] = 0;
        $response["message"] = "Book Unavailable";
 
        // echoing JSON response
        echo json_encode($response);
	}
	else if(mysqli_num_rows($searchForUpdate)>0){
        // failed to insert row
        $response["success"] = 1;
        $response["message"] = "We have updated your book choice. Thank you.";
 
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
	
	
	
	
	
	