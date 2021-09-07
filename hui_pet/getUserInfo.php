<?php 

$data = json_decode(file_get_contents('php://input'), true);

$userInfoTableName = "userInfo";

// if ($data != null) {

    // $uid = $data['uid'];
    $uid = "user00001";
    // $u_name = $data['u_name'];
    // $sex = $data['sex'];

    require("huiPsetDbConnect.php");
    $sql_getUserInfo = "SELECT * from $userInfoTableName where uid ='".$uid."';" ;

    setDBConfig("localhost","lchuang","asabee1566","hui_pet");
    getDBConnect();
    $result = selectDataTable($sql_getUserInfo);
    closeDBConnect();
    $jsonArray=array();
    if (mysqli_num_rows($result)>0) {   
        while($row = $result->fetch_assoc()){
            array_push($jsonArray,$row);
        }
        $json = array("data"=>$jsonArray);
        echo json_encode($json,JSON_UNESCAPED_UNICODE|true);
    }else {
        echo "空";
    }
      
// } else{
//     echo "get user info connect not ok";
// }



/*
CREATE TABLE `hui_pet`.`userinfo` ( `uid` TEXT NOT NULL , `u_name` TEXT NOT NULL , `age` INT NOT NULL , `reference` TEXT NOT NULL , `sex` BOOLEAN NOT NULL , `user_describe` TEXT NOT NULL , `picture` TEXT NOT NULL , `trend` INT NOT NULL , `date` DATETIME NOT NULL , `f_date` DATETIME NOT NULL , `total_pay` INT NOT NULL , `contact_type` INT NOT NULL , `contact_id` TEXT NOT NULL ) ENGINE = InnoDB;

INSERT INTO `userinfo` (`uid`, `u_name`, `age`, `reference`, `sex`, `user_describe`, `picture`, `trend`, `date`, `f_date`, `total_pay`, `contact_type`, `contact_id`) VALUES ('user00001', '米血豬', '18', '經由朋友推薦來看IG', '0', '來自台南的阿豬豬', '', '1', '2021-06-17', '2021-07-02', '1500', '2', 'IG:mi_zhu_zhu')
*/




?>
