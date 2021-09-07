<?php 

$data = json_decode(file_get_contents('php://input'), true);

$userInfoTableName = "userInfo";


$uid = $data['uid'];
$u_name = $data['u_name'];
$age = $data['age'];
$reference = $data['reference'];
$sex = $data['sex'];
$user_describe = $data['user_describe'];
$picture = $data['picture'];
$trend = $data['trend'];
$date = $data['date'];
$f_date = $data['f_date'];
$total_pay = $data['total_pay'];
$contact_type = $data['contact_type'];
$contact_id = $data['contact_id'];

setDBConfig("localhost","lchuang","asabee1566","hui_pet");
getDBConnect();

// $sql_checkUserExist = "SELECT * fron $userInfoTableName";
// $result = selectDataTable($sql_getUserInfo);
// if (mysqli_num_rows($result)>0) { 
// }

$sql_insertUserInfo = "INSERT INTO `$userInfoTableName` (`uid`, `u_name`, `age`, `reference`, `sex`, `user_describe`, `picture`, `trend`, `date`, `f_date`, `total_pay`, `contact_type`, `contact_id`) VALUES ('".$uid."', '".$u_name."', '".$age."', '".$reference."', '".$sex."', '".$user_describe."', '".$picture."', '".$trend."', '".$date."', '".$f_date."', '".$total_pay."', '".$contact_type."', '".$contact_id."')";


insertDataTable($sql_getUserInfo);
closeDBConnect();

?>
