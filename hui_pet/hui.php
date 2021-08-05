
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
//  *   **/


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
            <th width="5%">No.</th>
            <th width="80%" colspan="1"></th>
            <th width="15%">編輯</th>
        </tr>
    </Thead>
    <tbody id="record_tbody">
 

    </tbody>


</table>

<table>
    <tr id="tr_stored">
        <td><button id="store_record_list" type="button" >上傳記錄</button></td>
        <td><button id="remove_all" type="button" >清空對話</button></td>
    </tr>
</table>

</div>
</html>


    
<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<script type="text/javascript">

var huiInfo = new Array(); // 本人資料
var petInfo = new Array(); // 寵物資料
var userInfo = new Array();// 客戶資料

var tbRow = 0;
var countDelete = 0;
var objChatRecord = new Object();

var tempRecordList = [];
var recordList = [];

if (window.sessionStorage.getItem('haveRecord')) {
    console.log("+++ : " + window.sessionStorage.getItem('haveRecord')+ " - " + window.sessionStorage.getItem('temp_chatRecord'));
    objChatRecord = JSON.parse(window.sessionStorage.getItem('temp_chatRecord'));
    backupChatRecord(objChatRecord);
}

$("#insert_hui_talking").click(function() {
    addOneRawTable(tbRow,"hui");
    // tbRow++;
});
$("#insert_pet_talking").click(function() {
    addOneRawTable(tbRow,"pet");
    // tbRow++;
});
$("#insert_user_talking").click(function() {
    addOneRawTable(tbRow,"user");
    // tbRow++;
});

$("#store_record_list").click(function() {
    uploadToDB();
});

$("#remove_all").click(function() {
    removeAll();
});


// 新增 row
function addOneRawTable(i,role) {
    console.log("addOneRawTable: " + i);
    let roleInfo = new Array();
    roleInfo = Array.from(getRoleInfo(role));
    
    $('#tb_test').append('<tr id="tr_newAdd' + i + '"></tr><tr id="tr_newEditor' + i + '"></tr>');
    $('#tr_newAdd' + i).html('<td width="5%" rowspan="2">' + (i+1) + '</td><td width="80%"><label name="roleInfo" style="display:none">'+roleInfo[1]+'</label><label name="direction" style="display:none">'+true+'</label><img src="icon/'+roleInfo[0]+'" width="30"> : <input style="width:90%; heigth:100" name="speak_data" value=""/></td><td width=15%><button id="btn_insert_'+ i +'" onClick="deleteRow('+i+')">刪除</button></td>');
    $('#tr_newEditor' + i).html('<td width="95%" colspan="2" align="right"><button id="btn_left_'+i+'" onClick="letTextLeft(\''+i+'\',\''+role+'\')">靠左</button><button id="btn_right_'+i+'" onClick="letTextRight(\''+i+'\',\''+role+'\')">靠右</button></td>');
    // document.getElementById('tb_test').insertRow(-1).innerHTML = '<td>' + (i+1) + '</td><td>' + role + '</td><td><input style="width:100%; heigth:100" name="speak_data" value=""/></td><td><button id="btn_insert_'+ i +'" onClick="deleteRow('+i+')">刪除</button></td>';
    // document.getElementById('tb_test').insertRow(-1).innerHTML = '<td>' + (i+1) + '</td><td colspan="3" align="right"><button id="btn_left_'+i+' onClick="setTextLeft()">靠左</button><button id="btn_right_'+i+' onClick="setTextRight()">靠右</button></td>';
    // document.getElementById('tb_test').insertRow(-1).innerHTML="tr_newEditor";
    // $('#tb_test').append('<tr id="tr_newAdd' + (i+1) + '"></tr><tr id="tr_newEditor' + (i+1) + '"></tr>');
    // $('#tb_test').append('<tr id="tr_newAdd' + (i+1) + '"></tr>');
    
    tempRecordList.push("tr_newAdd"+i);
    tbRow++;
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
    countDelete+=1;
}

function getRoleInfo(role) {
    // role = 客戶編號/寵物編號/Hui編號
    console.log("array from r: "+ role);
    /** use role to select mysql and get the all role data */
    let info = new Array;
    switch (role) {
        case "hui":
            huiInfo.splice(0, 0, "people/woman.png");
            huiInfo.splice(1, 0, "hui");
            info = Array.from(huiInfo);
            break;
        case "pet":
            petInfo.splice(0, 0, "pet/cat/sandbox.png");
            petInfo.splice(1, 0, "pet");
            info = Array.from(petInfo);
            break;
        case "user":
            userInfo.splice(0, 0, "people/woman2.png");
            userInfo.splice(1, 0, "user");
            info = Array.from(userInfo);
            break;
        default:
            break;
    }
    console.log("array from : "+ info);
    return info
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
    let time = new Date();
    
    objChatRecord.data = new Array();
    // objChatRecord.time = time.getFullYear()+'-'+(time.getMonth()+1)+'-'+time.getDate()+' '+time.getHours()+':'+time.getMinutes()+':'+time.getSeconds()+'T'+time.getTimezoneOffset();
    // objChatRecord.time = new Date().toLocaleString( {timeZone: 'Asia/Taipei' , hour12: false , year: 'numeric',  hour: '2-digit', minute: '2-digit' ,day: 'numeric'} );
    objChatRecord.time = new Date().toISOString();
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
                    // data1.role = cells[j].textContent;
                    // console.log("role: "+cells[j].textContent);
                    // console.log("list: "+ jData);
                    // console.log("i: "+ i + " cell[j]1: "+cells[j].textContent);
                } else if (j == 2) {
                    // let jData = JSON.stringify({
                    //     "speak_data" : document.getElementsByName("speak_data")[(i-1)/2].value
                    // })
                    // recordList[i].push( jData );
                    data1.role = document.getElementsByName("roleInfo")[(i-1)/2].textContent;
                    data1.speak_data = document.getElementsByName("speak_data")[(i-1)/2].value;
                    data1.direction = document.getElementsByName("direction")[(i-1)/2].textContent;
                    // console.log("input value: "+document.getElementsByName("speak_data")[i].value);        
                    // console.log("list: "+jData);
                    // console.log("i: "+ i + " cell[j]2: " + document.getElementsByName("speak_data")[(i-1)/2].value);
                } 

                
            }
            objChatRecord['data'].push(data1);
        }
        // var jsonString= JSON.stringify(data);
        // console.log("JS ARRAY: "+i+" "+jsonString);
        // var objString = JSON.parse(obj);
        // obj['data'].push(data1);
        // obj = JSON.stringify(objString);
    }
    // obj.data = JSON.stringify(recordList)
    // showRecord(recordList);
    
    console.log("list: " + JSON.stringify(objChatRecord));
    
}

function letTextLeft(row,role) {
    console.log("setTextLeft: " + row + role + countDelete);
    let roleInfo = new Array();
    roleInfo = Array.from(getRoleInfo(role));
    let tempText = document.getElementsByName("speak_data")[row-countDelete].value;
    let row1 = '<tr id="tr_newAdd' + row + '"><td width="5%" rowspan="2">' + (parseInt(row, 10)+1) + '</td><td width="80%"><label name="roleInfo" style="display:none">'+roleInfo[1]+'</label><label name="direction" style="display:none">'+true+'</label><img src="icon/'+roleInfo[0]+'" width="30"> : <input style="width:90%; heigth:100" name="speak_data" value="'+tempText+'"/></td><td width="15%"><button id="btn_insert_'+ row +'" onClick="deleteRow('+row+')">刪除</button></td></tr>';
    // $('#btn_insert_'+ i).parent().replaceWith(row1);
    $('#tr_newAdd'+row).replaceWith(row1);
}

function letTextRight(row,role) {
    console.log("setTextRight: " + row + role + countDelete);
    let roleInfo = new Array();
    roleInfo = Array.from(getRoleInfo(role));
    let tempText = document.getElementsByName("speak_data")[row-countDelete].value;
    let row1 = '<tr id="tr_newAdd' + row + '"><td width="5%" rowspan="2">' + (parseInt(row, 10)+1) + '</td><td width="80%"><label name="roleInfo" style="display:none">'+roleInfo[1]+'</label><label name="direction" style="display:none">'+false+'</label><input style="width:90%; heigth:100" name="speak_data" value="'+tempText+'"/> : <img src="icon/'+roleInfo[0]+'" width="30"></td><td width="15%"><button id="btn_insert_'+ row +'" onClick="deleteRow('+row+')">刪除</button></td></tr>';
    // let row2 = '<td>e'+(i+1)+'</td><td colspan="2" align="right"><button id="btn_left_'+i+' onClick="setTextLeft('+i+')">靠左</button><button id="btn_right_'+i+' onClick="setTextRight('+i+')">靠右</button></td>'
    
    
	// $("td#someid").parent().replaceWith(newtr);
    $('#tr_newAdd'+row).replaceWith(row1);
    // $('#btn_insert_'+ i).parent().replaceWith(row1);
    // $('#tr_newEditor'+i).replaceWith(newtr);
}

// 清空對話
function removeAll(){
    tbRow = 0;
    countDelete = 0;
    $("#record_tbody").children().remove()
}

var loadcount = 0;
loadcount = window.sessionStorage.getItem('loadcount');
// console.log('temp_chatRecord: '+ loadcount+ " " + window.sessionStorage.getItem('temp_chatRecord'));
// 重整時提醒(IE,Chrome 皆可用)
window.onbeforeunload = function() {
    let haveRecord = false;
    if(document.getElementById("tb_test").rows.length>1)
        haveRecord = true;
    else{
        haveRecord = false;
    }
    storeRecordList();
    loadcount++;
    window.sessionStorage.setItem('haveRecord', haveRecord);
    window.sessionStorage.setItem('loadcount', loadcount);
    window.sessionStorage.setItem('temp_chatRecord', JSON.stringify(objChatRecord));



  return "on";
};
// 關閉時提醒(chrome不適用，僅IE可用)
// window.onunload = function(){
//    return "leave?"
// }


// init載入重整前資訊
function backupChatRecord(obj){
    console.log("backupChatRecord : "+ JSON.stringify(obj));
    console.log("objChatRecord time: " + obj.time );
    console.log("objChatRecord lengh: " + obj.data.length );
    console.log("objChatRecord lengh: " + Object.keys(obj.data).length );
    let index = Object.keys(obj.data).length;
    tbRow = index===0? 0 : index - 1;
    for (let i = 0; i < index; i++) {
        let role = obj.data[i].role;
        addOneRawTable(i,role);
        
        document.getElementsByName("speak_data")[i].value = [obj.data[i].speak_data];

        console.log("direction: "+obj.data[i].direction);
        if(obj.data[i].direction == "true")
            letTextLeft(i,role);
        else
            letTextRight(i,role);

    }

}

function uploadToDB(){
    storeRecordList();
    
}




function formatDate(date, format, utc) {
    var MMMM = ["\x00", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    var MMM = ["\x01", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var dddd = ["\x02", "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    var ddd = ["\x03", "Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

    function ii(i, len) {
        var s = i + "";
        len = len || 2;
        while (s.length < len) s = "0" + s;
        return s;
    }

    var y = utc ? date.getUTCFullYear() : date.getFullYear();
    format = format.replace(/(^|[^\\])yyyy+/g, "$1" + y);
    format = format.replace(/(^|[^\\])yy/g, "$1" + y.toString().substr(2, 2));
    format = format.replace(/(^|[^\\])y/g, "$1" + y);

    var M = (utc ? date.getUTCMonth() : date.getMonth()) + 1;
    format = format.replace(/(^|[^\\])MMMM+/g, "$1" + MMMM[0]);
    format = format.replace(/(^|[^\\])MMM/g, "$1" + MMM[0]);
    format = format.replace(/(^|[^\\])MM/g, "$1" + ii(M));
    format = format.replace(/(^|[^\\])M/g, "$1" + M);

    var d = utc ? date.getUTCDate() : date.getDate();
    format = format.replace(/(^|[^\\])dddd+/g, "$1" + dddd[0]);
    format = format.replace(/(^|[^\\])ddd/g, "$1" + ddd[0]);
    format = format.replace(/(^|[^\\])dd/g, "$1" + ii(d));
    format = format.replace(/(^|[^\\])d/g, "$1" + d);

    var H = utc ? date.getUTCHours() : date.getHours();
    format = format.replace(/(^|[^\\])HH+/g, "$1" + ii(H));
    format = format.replace(/(^|[^\\])H/g, "$1" + H);

    var h = H > 12 ? H - 12 : H == 0 ? 12 : H;
    format = format.replace(/(^|[^\\])hh+/g, "$1" + ii(h));
    format = format.replace(/(^|[^\\])h/g, "$1" + h);

    var m = utc ? date.getUTCMinutes() : date.getMinutes();
    format = format.replace(/(^|[^\\])mm+/g, "$1" + ii(m));
    format = format.replace(/(^|[^\\])m/g, "$1" + m);

    var s = utc ? date.getUTCSeconds() : date.getSeconds();
    format = format.replace(/(^|[^\\])ss+/g, "$1" + ii(s));
    format = format.replace(/(^|[^\\])s/g, "$1" + s);

    var f = utc ? date.getUTCMilliseconds() : date.getMilliseconds();
    format = format.replace(/(^|[^\\])fff+/g, "$1" + ii(f, 3));
    f = Math.round(f / 10);
    format = format.replace(/(^|[^\\])ff/g, "$1" + ii(f));
    f = Math.round(f / 10);
    format = format.replace(/(^|[^\\])f/g, "$1" + f);

    var T = H < 12 ? "AM" : "PM";
    format = format.replace(/(^|[^\\])TT+/g, "$1" + T);
    format = format.replace(/(^|[^\\])T/g, "$1" + T.charAt(0));

    var t = T.toLowerCase();
    format = format.replace(/(^|[^\\])tt+/g, "$1" + t);
    format = format.replace(/(^|[^\\])t/g, "$1" + t.charAt(0));

    var tz = -date.getTimezoneOffset();
    var K = utc || !tz ? "Z" : tz > 0 ? "+" : "-";
    if (!utc) {
        tz = Math.abs(tz);
        var tzHrs = Math.floor(tz / 60);
        var tzMin = tz % 60;
        K += ii(tzHrs) + ":" + ii(tzMin);
    }
    format = format.replace(/(^|[^\\])K/g, "$1" + K);

    var day = (utc ? date.getUTCDay() : date.getDay()) + 1;
    format = format.replace(new RegExp(dddd[0], "g"), dddd[day]);
    format = format.replace(new RegExp(ddd[0], "g"), ddd[day]);

    format = format.replace(new RegExp(MMMM[0], "g"), MMMM[M]);
    format = format.replace(new RegExp(MMM[0], "g"), MMM[M]);

    format = format.replace(/\\(.)/g, "$1");

    return format;
};
</script>


