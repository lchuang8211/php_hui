<?php 
 echo "Upload failed<br>";
$uploaddir = 'D:\xampp\htdocs\picture\userPicture';

if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {

    if ($_FILES['userfile']['error'] > 0) {
        echo "Return Code:" . $_FILES["userfile"]["error"] . "<br>";
    }

    $uploadfile = $uploaddir . "\\" . basename($_FILES['userfile']['name']);
    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
        echo "Upload OK : ";
    } else {
        echo "Upload failed<br>";
    }

}
echo "Upload failed 2<br>";
?>
