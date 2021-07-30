<?php
//設定header 
    // header("Content-type:bitmap; charset=utf-8");  //Content-type:text/json
    mb_internal_encoding('UTF-8');
    mb_http_input('UTF-8');
    mb_http_output('UTF-8');
// $uploaddir = 'C:\xampp\htdocs\getfile';
$uploaddir = 'C:\xampp\htdocs\getImg';

if (is_uploaded_file($_FILES['uploadHeadShot']['tmp_name'])){
	// var_dump($_FILES['uploadHeadShot']);
	
	// var_dump($img_string)
	// var_dump($_FILES['uploadHeadShot']);
	if ($_FILES['uploadHeadShot']['error'] > 0)
    {
    	echo "Return Code:" . $_FILES["uploadHeadShot"]["error"] . "<br>";
    }
    // $username = $_FILES['uploadHeadShot']['name'];
    $ImagName = basename($_FILES['uploadHeadShot']['name']);
	$uploadfile = $uploaddir."\\".$ImagName;
	if (move_uploaded_file($_FILES['uploadHeadShot']['tmp_name'], $uploadfile)) {
		echo "Upload OK : ";
	} else {
		echo "Upload failed<br>";
	}
		// var_dump ($_FILES['uploadHeadShot']);	
	var_dump($ImagName);
		// var_dump($_FILES['uploadHeadShot']['realname']);
		var_dump($_FILES['uploadHeadShot']['name']);	

	$getPathName = "/getImg/".$ImagName;
	
	function getUID($ImagName){
		if (false !== $pos = strripos($ImagName, '.')) //最後一次出現的位置
		{
	    	$ImagName = substr($ImagName, 0, $pos);    //擷取
		}
		return $ImagName;
	}
	$u_id = getUID($ImagName);
	require 'dbconnect.php';
	$sql_updata_image = "UPDATE `".$UserDB."`.`member` SET `head_img`='".$getPathName."' WHERE `u_id`='".$u_id."'";
	mysqli_query($conn,$sql_updata_image);
	mysqli_close($conn);


}




?>