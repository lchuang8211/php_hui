<meta charset="UTF-8">
<?php 
// https://charleslin74.pixnet.net/blog/post/436317631

//https://jokes168.pixnet.net/blog/post/378967367
$json1d = '[
    {
        "key_A":"symbols/2/2.png",
        "key_B":133,
        "key_C":"string array[2]"
    },
    {
        "key_A":"symbols/2/2.png",
        "key_B":175,
        "key_C":"string array[1]"
        
    }
    
]';

$result = json_decode($json1d);

foreach($result as $key => $value) {   
        echo "$value->key_A"."<br>";  // JSON裡 要取的值的KEY
        echo "$value->key_B"."<br>";
        echo "$value->key_C"."<br>";
    // }
 }
$json2d = '[
    {
        "key_A":"array1 AA",
        "key_B":133,
        "key_C":"8888"        
    },
    {
        "key_A":"array2",
        "key_B":175,
        "key_C":"3333"
    }
    
]';

/// 原民會旅遊便利GO_行程景點 農村地方美食小吃特色料理
// $myFile="農村地方美食小吃特色料理";
// $myDir="jsonfile";

/*
ID(ID)、Name(名稱)、Address(地址)、Tel(電話)、HostWords(主人的話)、
Price(費用簡介)、OpenHours(營業時間)、CreditCard(刷卡True_False)、
TravelCard(國民旅遊卡True_False)、TrafficGuidelines(交通指引)、
ParkingLot(停車場)、Url(網站連結)、Email(業者email)、BlogUrl(部落格網址)、
PetNotice(寵物須知)、Reminder(貼心叮嚀)、FoodMonths(最佳時令)、
FoodCapacity(容納人數)、FoodFeature(美食特色)、City(景點縣市)、Town(鄉鎮)、
Coordinate(座標)、PicURL(圖片)
*/
/* 景點名稱、行政區、地址、聯絡人、電話、手機、經度、緯度 */
$myFile="農村地方美食小吃特色料理";
$myDir="jsonfile";
echo '<br><hr>';
$handle = fopen($myDir."/".$myFile.".json","rb");
$content = "";
while (!feof($handle)) {	
	$content .= fread($handle, 10000);
}
fclose($handle);
$content = json_decode($content);
echo count($content);
foreach ($content as $key => $value) {
	if($value!="" && $value!=null){	
	echo "ID : "."$value->ID<br>";
	echo "名稱 : "."$value->Name<br>";
    echo "地址 : "."$value->Address<br>";
    echo "電話 : "."$value->Tel<br>";
    echo "主人的話 : "."$value->HostWords<br>";
    echo "費用簡介 : "."$value->Price<br>";
    echo "營業時間 : "."$value->OpenHours<br>";
    echo "刷卡 : "."$value->CreditCard<br>";
    echo "國民旅遊卡 : "."$value->TravelCard<br>";
    echo "交通指引 : "."$value->TrafficGuidelines<br>";
    echo "停車場 : "."$value->ParkingLot<br>";
    echo "網站連結 : "."$value->Url<br>";
    echo "業者email : "."$value->Email<br>";
    echo "部落格網址 : "."$value->BlogUrl<br>";
	echo "寵物須知 : "."$value->PetNotice<br>";
	echo "貼心叮嚀 : "."$value->Reminder<br>";
	echo "最佳時令 : "."$value->FoodMonths<br>";
    echo "容納人數 : "."$value->FoodCapacity<br>";
    echo "美食特色 : "."$value->FoodFeature<br>";
    echo "景點縣市 : "."$value->City<br>";
    echo "鄉鎮 : "."$value->Town<br>";
    echo "座標 : "."$value->Coordinate<br>";
    echo "圖片 : "."$value->PicURL<br>";
    echo "<hr>";
}



}
	// echo "$value->景點名稱<br>";
		// echo "$value->行政區<br>";
		// echo "$value->地址<br>";
		// echo "$value->聯絡人<br>";
		// echo "$value->電話<br>";
		// echo "$value->手機<br>";
		// echo "$value->經度<br>";
		// echo "$value->緯度<br>";
	


// class Helper_Tool{
// 	static function unicodeDecode($data){  
// 		function replace_unicode_escape_sequence($match) {
// 			return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
// 		}  
// 		$rs = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $data);
// 		return $rs;
// 	}  
// }
//呼叫
// $name = '\u884c\u7a0b\u540d\u7a31';
// $data = Helper_Tool::unicodeDecode($name); //輸出新浪微博



// echo '<br><br>';
// $obj=json_decode($jsontest);
// echo $obj;
// foreach ($rows as $row) {
//     echo $row['a'];
//     echo $row['b'];
// }

// foreach($obj as $key=>$val) {
// 	echo $obj." first ";
// 	foreach($obj as $key=>$val) {
//   		echo "Scond ".$key."=".$val."<br/>";
// 	}
// // }
// echo '<br><br>';
// // echo $obj;
// echo '<br><br>';
// echo $jsontest;

// $jsonSQL = $obj["\u884c\u7a0b\u540d\u7a31"];

// MySQL table's name
// $tableName = 'my_table';
// Get JSON file and decode contents into PHP arrays/values
// $jsonFile = './jsonfile/Aborigines.json';
// $jsonData = json_decode(file_get_contents($jsonFile), true);

// Iterate through JSON and build INSERT statements
// foreach ($jsonData as $id=>$row) {
//     $insertPairs = array();
//     foreach ($row as $key=>$val) {
//         $insertPairs[addslashes($key)] = addslashes($val);
//     }
//     $insertKeys = '`' . implode('`,`', array_keys($insertPairs)) . '`';
//     $insertVals = '"' . implode('","', array_values($insertPairs)) . '"';

//     echo "INSERT INTO `{$tableName}` ({$insertKeys}) VALUES ({$insertVals});" . "\n";
// }

?>
