<?php
$connect = mysqli_connect("localhost", "root", "", "devamsizlik");
$query = "
 UPDATE islenen_dersler SET ".$_POST["name"]." = '".$_POST["value"]."' 
 WHERE id = '".$_POST["pk"]."'";
$query2 = 'INSERT INTO islenen_dersler (
    ders_kodu, section_kod, hafta, saat)
    SELECT ders_kodu, section_kod, ' .$_POST["hafta"]. ','. $_POST["value"] .'
    FROM islenen_dersler
    WHERE id = '. $_POST["pk"] .'
    ON DUPLICATE KEY UPDATE
    saat= '. $_POST["value"] .';';
mysqli_query($connect, $query2);
?>