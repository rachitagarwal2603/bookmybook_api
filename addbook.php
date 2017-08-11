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
//if (isset($_POST['name']) && isset($_POST['available']) && isset($_POST['issues'])) 
if (isset($postData['name']) && isset($postData['available']) && isset($postData['issues'])) 
{
	//die("error");
    $name = $postData['name'];
    //$name = $_POST['name'];
    $available = $postData['available'];
    //$available = $_POST['available'];
    //$issues = $_POST['issues'];
    $issues = $postData['issues'];
 
    // include db config class
    require_once __DIR__ . '/db_config.php';
 
    // connecting to db
    //$db = new DB_CONNECT();
	$con = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysql_error());
	
	$search= mysqli_query($con, "SELECT * FROM book_details WHERE Book_Name = '$name'") or die ("Could not search");
	
	//die("$search");
	
    // mysql inserting a new row
    if(mysqli_num_rows($search)>0)
		$result = mysqli_query($con, "UPDATE book_details SET Available = '$available', Issues = '$issues'	WHERE Book_Name = '$name'");
	else
		$result = mysqli_query($con, "INSERT INTO book_details(Book_Name, Available, Issues) VALUES('$name', '$available', '$issues')");
 
	mysqli_close($con);
    // check if row inserted or not
    if (mysqli_num_rows($search)==0) {
        // successfully inserted into database
        $response["success"] = 1;
        $response["message"] = "Book entry successfully created.";
 
        // echoing JSON response
        echo json_encode($response);
    } else if(mysqli_num_rows($search)==1){
        // failed to insert row
        $response["success"] = 0;
        $response["message"] = "Book Details Updated";
 
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