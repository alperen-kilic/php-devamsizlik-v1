<?php include("connection.php"); ?>
<?php
if(isset($_POST['d_id']) && isset($_POST['h_id'])) {
    $sql = "select * from `ders` where `hoca_id`=".mysqli_real_escape_string($con, $_POST['h_id']);
    $res = mysqli_query($con, $sql);
    if(mysqli_num_rows($res) > 0) {
        echo "<tr></tr>";
        while($row = mysqli_fetch_object($res)) {
//            echo "<option value='".$row->id."'>".$row->ders_kodu."</option>";
            echo "<td>".$row->ogrenci_no."</td>";
        }
    }
} else {
    header('location: ./');
}
?>