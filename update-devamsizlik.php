<?php
//update.php
$connect = mysqli_connect("localhost", "root", "", "devamsizlik");
$query = "
 UPDATE devamsizliklar SET ".$_POST["name"]." = '".$_POST["value"]."' 
 WHERE id = '".$_POST["pk"]."'";


//$query2 = "INSERT INTO devamsizliklar (
//    ogrenci_no, ders_kodu, section_kod, hafta, saat)
//    SELECT ogrenci_no, ders_kodu, section_kod, , 1
//    FROM devamsizliklar
//    WHERE id = 1
//    ON DUPLICATE KEY UPDATE
//    saat=1;"
mysqli_query($connect, $query);
?>