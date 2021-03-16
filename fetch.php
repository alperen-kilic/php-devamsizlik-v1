<?php
$ders_kodu = $_GET['ders_kodu'];
$section_kodu = $_GET['section_kodu'];
$connect = mysqli_connect("localhost", "root", "", "devamsizlik");
$query = 'SELECT islenen_dersler.id, islenen_dersler.ders_kodu, islenen_dersler.section_kod, islenen_dersler.hafta, islenen_dersler.saat, dersler.donem_saati FROM `islenen_dersler`
             INNER JOIN `dersler` ON islenen_dersler.ders_kodu = dersler.ders_kodu WHERE islenen_dersler.ders_kodu = "'. $ders_kodu . '" AND islenen_dersler.section_kod = ' . $section_kodu .'
             ORDER BY hafta';
$result = mysqli_query($connect, $query);
$output = array();
while($row = mysqli_fetch_assoc($result))
{
    $output[] = $row;
}
echo json_encode($output);
?>