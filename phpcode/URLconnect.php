<?php
	$input = file_get_contents("php://input",'r');
	
	echo "connect ok ";
	if($input!=null && $input!=""){ 
        // str_replace("\n", "", $input1)
        if(substr($input,0,3) == pack("CCC",0xEF,0xBB,0xBF)) 
            $input = substr($input,3);
        $input = json_decode( trim($input,chr(239).chr(187).chr(191)),  true);
        // echo var_dump($input)."+ ";
        $input = $input["phoneDataJson"];
        echo $input;
        // echo "json_last_error : ".json_last_error()." - ";
    } 
    
    
?>