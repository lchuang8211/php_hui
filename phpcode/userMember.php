<?php 
	//設定header 
    header("Content-type:text/html; charset=utf-8");  //Content-type:text/json
    mb_internal_encoding('UTF-8');
    mb_http_input('UTF-8');
    mb_http_output('UTF-8');

	$getUserInput = file_get_contents("php://input",'rb');
	$getUserInput = urldecode($getUserInput);
	if(substr($getUserInput,0,3) == pack("CCC",0xEF,0xBB,0xBF))   //去BOM檔頭
            $getUserInput = substr($getUserInput,3);

    $getUserInput = json_decode( $getUserInput,  true);
	
	$USER_SIGNIN_OR_SIGNUP = $getUserInput["USER_SIGNIN_OR_SIGNUP"];
	
	// echo $USER_ACCOUNT ."-".$USER_PASSWORD;
	
    require("dbconnect.php");
    $returnJson = array();
    if($USER_SIGNIN_OR_SIGNUP==1){
    	$USER_ACCOUNT = trim($getUserInput["USER_ACCOUNT"]);
		$USER_PASSWORD = trim($getUserInput["USER_PASSWORD"]);
	    // $userAddAccount_sql="insert into ".$dbname.".member (account, password ) VALUES (".$USER_ACCOUNT.",".$USER_PASSWORD.");";
	    $userSignin_sql="select `u_id`,`user_name`,head_img from ".$UserDB.".member where account ='".$USER_ACCOUNT."' && password ='".$USER_PASSWORD."';";
	    $result = mysqli_query($conn,$userSignin_sql);
	    // var_dump(mysqli_num_rows($result));
		if (mysqli_num_rows($result)>=1) {
			$row = $result->fetch_assoc();							
			$returnJson =array("u_id"=>$row["u_id"],"USER_NICK_NAME"=>$row["user_name"],"ENTRANCE_NUM"=>1,"head_img"=>$row["head_img"]);
			echo json_encode($returnJson,JSON_UNESCAPED_UNICODE|true);
			// array_push($returnJson, 1);
			// var_dump($returnJson);
			// $json=json_encode($returnJson);
			// echo $json;
		}else
			echo 0;
	}else if($USER_SIGNIN_OR_SIGNUP==2){
		$USER_SIGNUP_ACCOUNT = trim($getUserInput["USER_SIGNUP_ACCOUNT"]);
		$USER_SIGNUP_PASSWORD = trim($getUserInput["USER_SIGNUP_PASSWORD"]);
		$getUserName =  trim($getUserInput["USER_NICK_NAME"]);
		$userCheck_SQL = "select * from ".$UserDB.".member where `account` = '".$USER_SIGNUP_ACCOUNT."';";
		// echo $userCheck_SQL;
		$result = mysqli_query($conn,$userCheck_SQL);

		// while ($row = mysql_fetch_array($result))
		if(mysqli_num_rows($result)>0) {
			// echo "帳號重複";
			echo 0;
		}else{
			$userSignup_SQL = "insert into ".$UserDB.".member (`account`,`password`,`user_name`) VALUES ('".$USER_SIGNUP_ACCOUNT."','".$USER_SIGNUP_PASSWORD."','".$getUserName."' );";
			$set_uid = "UPDATE ".$UserDB.".member SET `u_id` = CONCAT('u', `id`) WHERE `id` = LAST_INSERT_ID();";
			$set_schedule = "UPDATE ".$UserDB.".member SET `schedule` = CONCAT('plan', `id`) WHERE `id` = LAST_INSERT_ID();";
			mysqli_query($conn,$userSignup_SQL); // 新增成功加入會員的帳號資訊
			mysqli_query($conn,$set_uid); // 更新 u_id
			mysqli_query($conn,$set_schedule);// 更新 schedule 名稱
			$select_schedule = "SELECT u_id, schedule FROM ".$UserDB.".member WHERE `id` = LAST_INSERT_ID();";
			$result = mysqli_query($conn,$select_schedule);
			$getSchedule = $result->fetch_assoc();
			$create_schedule = "CREATE TABLE IF NOT EXISTS ".$UserDB.".".$getSchedule["schedule"]." (u_id VARCHAR(10) NOT NULL, plan_name VARCHAR(10) NOT NULL, start_date VARCHAR(10) NOT NULL, end_date VARCHAR(10) NOT NULL) ENGINE = InnoDB;";
			mysqli_query($conn,$create_schedule);// 建立 user schedule Table (存放個人所有行程表)
			$return_u_id = "select user_name,`u_id`,`head_img` from ".$UserDB.".member WHERE `id` = LAST_INSERT_ID();";			
			$result = mysqli_query($conn,$return_u_id);
			$row = $result->fetch_assoc();
			$returnJson =array("u_id"=>$row["u_id"],"USER_NICK_NAME"=>$row["user_name"],"ENTRANCE_NUM"=>1,"head_img"=>$row["head_img"]);			
			echo json_encode($returnJson,JSON_UNESCAPED_UNICODE|true);			
			// echo "this is sign up";
		}
	}
	mysqli_close($conn);
?>