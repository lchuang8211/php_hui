
<?php
    mb_internal_encoding('UTF-8');
    mb_http_input('UTF-8');
    mb_http_output('UTF-8');
echo '<h1>Smile Mi 的動物朋友</h1><hr>';

// // Lobby ;
// /**
//  *  table (myself data)
//  *  buttom (chang mydelf) (chang mydelf icon) 
//  *         (insert custom) (find custom) 
//  *         (insert pet) (find pet)
//  *         (upload icon) (upload picture)
//  *  
//  * <script src="https://code.jquery.com/jquery-3.2.1.js">
// function changeIcon($role) {
    //     console.log('changeIcon click '.$role);
    // }

    // function insertTalking($role) {
    //     console.log('insertTalking click '.$role);
    // }

    // var hui_1 = document.getElementById("change_hui_icon");
    // var hui_2 = document.getElementById("insert_hui_talking");
    // var pet_1 = document.getElementById("change_pet_icon");
    // var pet_2 = document.getElementById("insert_pet_talking");
    // var user_1 = document.getElementById("change_user_icon");
    // var user_2 = document.getElementById("insert_user_talking");
    
    // if (hui_1.addEventListener){
    //     hui_1.addEventListener("click", changeIcon(1), true);
    //     console.log('addEventListener hui_1');
        
    // }
    // else if (hui_1.attachEvent){
    //     hui_1.attachEvent('onclick', changeIcon(12));
    //     console.log('attachEvent hui_1');
    // }
 
    // if (hui_2.addEventListener){
    //     hui_2.addEventListener("click", insertTalking(1), false);
    //     console.log('addEventListener hui_2');
    // }
    // else if (hui_2.attachEvent)
    //     hui_2.attachEvent('onclick', insertTalking(12));
        
    // if (pet_1.addEventListener){
    //     pet_1.addEventListener("click", changeIcon(2), false);
    //     console.log('addEventListener pet_1');
    // }
    // else if (pet_1.attachEvent)
    //     pet_1.attachEvent('onclick', changeIcon(22));
        
    // if (pet_2.addEventListener){
    //     pet_2.addEventListener("click", insertTalking(2), false);
    //     console.log('addEventListener pet_2');
    // }
    // else if (pet_2.attachEvent)
    //     pet_2.attachEvent('onclick', insertTalking(22));
        
    // if (user_1.addEventListener){
    //     user_1.addEventListener("click", changeIcon(3), false);
    //     console.log('addEventListener user_1');
    // }
    // else if (user_1.attachEvent)
    //     user_1.attachEvent('onclick', changeIcon(32));
        
    // if (user_2.addEventListener){
    //     user_2.addEventListener("click", insertTalking(3), false);
    //     console.log('addEventListener user_2');
    // }
    // else if (user_2.attachEvent)
    //     user_2.attachEvent('onclick', insertTalking(32));

//  */

$hui_uid = "hui_308"; //人物編號
$hui_icon = "icon_308"; //人物icon
$hui_name = "SmileMi"; //人物名稱

function phpIcon($role){
    echo "<button>button $role </button>";
}

function showRecord($list){
    foreach ($list as $key => $value) {
        echo "key: $key value: ";
        foreach ($key as $value) {
            echo "$value ";
        }
        echo "<br>";
    }
}


?>

<html>
<header>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</header>

<table style="margin: 0px auto;">
    <tr>
        <td colspan="2" align="center"><img src="icon/people/woman.png" width="120"  alt="你自己"></td>
        <td colspan="2" align="center"><img src="icon/pet/cat/sandbox.png" width="120" alt="寵物"></td>
        <td colspan="2" align="center"><img src="icon/people/woman2.png" width="120" alt="對方"></td>
    </tr>
    <tr>
        <td><button id="change_hui_icon" type="button" >更換icon</button></td>
        <td><button id="insert_hui_talking" type="button" >新增對話</button></td>
        <td><button id="change_pet_icon" type="button" >更換icon</button></td>
        <td><button id="insert_pet_talking" type="button" >新增對話</button></td>
        <td><button id="change_user_icon" type="button" >更換icon</button></td>
        <td><button id="insert_user_talking" type="button" >新增對話</button></td>
        </tr>
</table>

<div style="margin:0 100 auto;border:1px #cccccc solid;">
<table style="margin:0 auto" width="100%" id="tb_test">
    <Thead>    
        <tr id="tr_tittle" bgcolor="#DDDDDD">
            <th width="10%">No.</th>
            <th width="80%" colspan="2"></th>
            <th width="10%">編輯</th>
        </tr>
    </Thead>
    <tbody>
 

    </tbody>


</table>

<table>
    <tr id="tr_stored">
        <td><button id="store_record_list" type="button" >儲存對話</button></td>
    </tr>
</table>

</div>
</html>


    
<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<script type="text/javascript">
var i = 0;


var tempRecordList = [];
var recordList = [];
$("#insert_hui_talking").click(function() {
    addOneRawTable(i,"hui");
    i++;
});
$("#insert_pet_talking").click(function() {
    addOneRawTable(i,"pet");
    i++;
});
$("#insert_user_talking").click(function() {
    addOneRawTable(i,"user");
    i++;
});

$("#store_record_list").click(function() {
    storeRecordList();
});

// 新增 row
function addOneRawTable(i,role) {
    // console.log("addOneRawTable: " + i);
    getRoleInfo(role);
    
    $('#tb_test').append('<tr id="tr_newAdd' + i + '"></tr><tr id="tr_newEditor' + i + '"></tr>');
    $('#tr_newAdd' + i).html('<td width="10%" rowspan="2">' + (i+1) + '</td><td width="10%">' + role + '</td><td width="70%"><input style="width:100%; heigth:100" name="speak_data" value=""/></td><td width="10%"><button id="btn_insert_'+ i +'" onClick="deleteRow('+i+')">刪除</button></td>');
    $('#tr_newEditor' + i).html('<td width="90%" colspan="3" align="right"><button id="btn_left_'+i+'" onClick="letTextLeft(\''+i+'\',\''+role+'\')">靠左</button><button id="btn_right_'+i+'" onClick="letTextRight(\''+i+'\',\''+role+'\')">靠右</button></td>');
    // document.getElementById('tb_test').insertRow(-1).innerHTML = '<td>' + (i+1) + '</td><td>' + role + '</td><td><input style="width:100%; heigth:100" name="speak_data" value=""/></td><td><button id="btn_insert_'+ i +'" onClick="deleteRow('+i+')">刪除</button></td>';
    // document.getElementById('tb_test').insertRow(-1).innerHTML = '<td>' + (i+1) + '</td><td colspan="3" align="right"><button id="btn_left_'+i+' onClick="setTextLeft()">靠左</button><button id="btn_right_'+i+' onClick="setTextRight()">靠右</button></td>';
    // document.getElementById('tb_test').insertRow(-1).innerHTML="tr_newEditor";
    // $('#tb_test').append('<tr id="tr_newAdd' + (i+1) + '"></tr><tr id="tr_newEditor' + (i+1) + '"></tr>');
    // $('#tb_test').append('<tr id="tr_newAdd' + (i+1) + '"></tr>');
    
    tempRecordList.push("tr_newAdd"+i);
    // console.log("tempRecordList: " + tempRecordList);
}

// 刪除 row
function deleteRow(i) {
    console.log("deleteRow: "+i+" tr_newAdd"+i+" "+ event.srcElement.id);
    // document.getElementById("tb_test").removeChild(0); //document.getElementById("tr_newAdd"+i)

	var row = document.getElementById('tr_newAdd'+i);
	row.parentElement.removeChild(row);
    var row = document.getElementById('tr_newEditor'+i);
	row.parentElement.removeChild(row);
    console.log("length: " + tempRecordList.length);
    if (tempRecordList.length > 0) {
        
        let position = tempRecordList.indexOf('tr_newAdd'+i);
        tempRecordList.splice(position, 1);
        console.log("tempRecordList: " + tempRecordList);
    }
    // var child=document.getElementById("tr_newAdd"+i);
    // child.parentNode.removeChild(child);
}

function getRoleInfo(role) {
    // console.log("getRoleInfo: " + role);
}

// let: 區域變數 var: 全域變數
function storeRecordList() {
    // let length = tempRecordList.length

    // for (let index = 0; index < length; index++) {
    //     
    //     recordList[index] = new Array();
    //     recordList[index].push( document.getElementById(tempRecordList[index]).cells[1] );
    // }
    // console.log(recordList);
   
    var tbl = document.getElementById("tb_test");
    var numRows = tbl.rows.length-1;
    // console.log("size = "+ numRows +" "+(numRows-1)/2);
    var obj = new Object();
    obj.data = new Array();
    // 取得 table 內所有資料
    for (let i = 1; i < (numRows); i=i+1) {
        let ID = tbl.rows[i].id;
        
        
        if (i%2==1) {
            recordList[i] = new Array();
            var tr = document.querySelectorAll("#"+ID);
            // console.log("ID = "+ ID);
            let cells = tbl.rows[i].getElementsByTagName('td');
            var data1 = new Object();
        
            for (let j=0,it=cells.length;j<it;j++) {
                if (j == 1) {
                    // let jData = JSON.stringify({
                    //     "role" : cells[j].textContent
                    // })
                    // recordList[i].push( jData ); 
                    data1.role = cells[j].textContent;
                    // console.log("role: "+cells[j].textContent);
                    // console.log("list: "+ jData);
                    // console.log("i: "+ i + " cell[j]1: "+cells[j].textContent);
                } else if (j == 2) {
                    // let jData = JSON.stringify({
                    //     "speak_data" : document.getElementsByName("speak_data")[(i-1)/2].value
                    // })
                    // recordList[i].push( jData );
                    data1.speak_data =  document.getElementsByName("speak_data")[(i-1)/2].value;
                    // console.log("input value: "+document.getElementsByName("speak_data")[i].value);        
                    // console.log("list: "+jData);
                    // console.log("i: "+ i + " cell[j]2: " + document.getElementsByName("speak_data")[(i-1)/2].value);
                } 

                
            }
            obj['data'].push(data1);
        }
        // var jsonString= JSON.stringify(data);
        // console.log("JS ARRAY: "+i+" "+jsonString);
        // var objString = JSON.parse(obj);
        // obj['data'].push(data1);
        // obj = JSON.stringify(objString);
    }
    // obj.data = JSON.stringify(recordList)
    // showRecord(recordList);
    console.log("list: " + JSON.stringify(obj));
    
}

function letTextLeft(row,role) {
    console.log("setTextLeft: " + row + role);
    // event.srcElement.id
    
    let row1 = '<tr id="tr_newAdd' + row + '"><td width="10%" rowspan="2">' + (parseInt(row, 10)+1) + '</td><td width="10%">' + role + '</td><td width="70%"><input style="width:100%; heigth:100" name="speak_data" value=""/></td><td width="10%"><button id="btn_insert_'+ row +'" onClick="deleteRow('+row+')">刪除</button></td></tr>';
    // $('#btn_insert_'+ i).parent().replaceWith(row1);
    $('#tr_newAdd'+row).replaceWith(row1);
}

function letTextRight(row,role) {
    console.log("setTextRight: " + row + role);
    let row1 = '<tr id="tr_newAdd' + row + '"><td width="10%" rowspan="2">' + (parseInt(row, 10)+1) + '</td><td width="70%"><input style="width:100%; heigth:100" name="speak_data" value=""/></td><td width="10%">' + role + '</td><td width="10%"><button id="btn_insert_'+ row +'" onClick="deleteRow('+row+')">刪除</button></td></tr>';
    // let row2 = '<td>e'+(i+1)+'</td><td colspan="2" align="right"><button id="btn_left_'+i+' onClick="setTextLeft('+i+')">靠左</button><button id="btn_right_'+i+' onClick="setTextRight('+i+')">靠右</button></td>'
    
    
	// $("td#someid").parent().replaceWith(newtr);
    $('#tr_newAdd'+row).replaceWith(row1);
    // $('#btn_insert_'+ i).parent().replaceWith(row1);
    // $('#tr_newEditor'+i).replaceWith(newtr);
}

</script>


