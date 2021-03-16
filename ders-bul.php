<?php include("connection.php");
if(isset($_POST['h_id'])) {
    $sql = "select * from `ders` where `hoca_id`=".mysqli_real_escape_string($con, $_POST['h_id']);
    $res = mysqli_query($con, $sql);
    if(mysqli_num_rows($res) > 0) {
        echo "<option value=''>------- Ders Seçin --------</option>";
        while($row = mysqli_fetch_object($res)) {
            echo "<option value='".$row->ders_kodu."|".$row->section_kod."'>".$row->ders_kodu. "(Section: " .$row->section_kod. ")</option>";
        }
    }
} else {
    header('location: ./');
}