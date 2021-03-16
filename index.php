<?php
include ("connection.php");
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Devamsızlık Hesaplama</title>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/ajax.js"></script>

</head>
<body>
<br>
<form style="margin-top:20px; margin-right:+50px" name="devamsizlik" method="get" action="devamsizlik-liste-test.php">
<div class="">
    <label>Hocalar :</label>
    <select class="form-control picker" name="hoca" id="hoca">
        <option value=''>------- Hoca Seçin --------</option>
        <?php
        $sql = "select * from `hocalar`";
        $res = mysqli_query($con, $sql);
        if(mysqli_num_rows($res) > 0) {
            while($row = mysqli_fetch_object($res)) {
                echo "<option value='".$row->id."'>".$row->ad. " " . $row->soyad ."</option>";
            }
        }
        ?>
    </select>

    <label>Dersler :</label>
    <select class="form-control picker" name="ders" id="ders"><option>------- Ders Seçin --------</option></select>
    <br/>
    <input class="btn btn-primary btn-lg" type="submit" id="button" disabled value="Devamsızlıkları Göster" />

</body>
</html>
<script>
    $(function() {
        $('.picker').on('change', function() {
            var $sels = $('.picker option:selected[value=""]');
            $("#button").attr("disabled", $sels.length > 0);
        }).change();
    });


</script>