<?php
include ("connection.php");
?>
<?php
$data[] = null;
$last_seen_pk = -1;
$ders_data[] = null;
$sayac = 4; // Devamsızlık column takibi için
$satir_takip = 1; // Satır IDlerini vermek için
$islenenDersSayac = 0; // İşlenen ders saati takibi için
$toplam_ders_saati = 0;
$devamsizlik_saati = 0;
$islenen_ders_saati = 0; // Uyarı verip vermeyeceğini hesaplamak için
$final_saat = 0; // Devamsızlıkla geçti-kaldı seçimini hesaplamak için
if(!empty($_GET['ders']))
{
    $ders_sec_value = $_GET['ders'];
    $ders_sec_value_exploded = explode('|', $ders_sec_value);  // 0-> Ders Kodu 1-> Section Kodu
    $ders_kodu = $ders_sec_value_exploded[0];
    $section_kodu = $ders_sec_value_exploded[1];
    $sql = 'SELECT devamsizliklar.id, devamsizliklar.ogrenci_no, ogrenciler.ad, ogrenciler.soyad, devamsizliklar.ders_kodu, devamsizliklar.section_kod, devamsizliklar.hafta,
            devamsizliklar.saat, ders.hoca_id
            FROM `devamsizliklar` INNER JOIN `ders` ON devamsizliklar.ders_kodu=ders.ders_kodu
            INNER JOIN `ogrenciler` ON devamsizliklar.ogrenci_no = ogrenciler.ogrenci_no
            WHERE devamsizliklar.ders_kodu = "'. $ders_kodu .'" AND ders.hoca_id = '. $_GET['hoca']. ' AND devamsizliklar.section_kod = '. $section_kodu .'
            ORDER BY ogrenci_no, hafta';
    $sql2 = 'SELECT islenen_dersler.id, islenen_dersler.ders_kodu, islenen_dersler.section_kod, islenen_dersler.hafta, islenen_dersler.saat, dersler.donem_saati FROM `islenen_dersler`
             INNER JOIN `dersler` ON islenen_dersler.ders_kodu = dersler.ders_kodu WHERE islenen_dersler.ders_kodu = "'. $ders_kodu . '" AND islenen_dersler.section_kod = ' . $section_kodu .'
             ORDER BY hafta';
    $res = mysqli_query($con, $sql2);
    if(mysqli_num_rows($res) > 0) {
        while($row = mysqli_fetch_assoc($res)) {
            $ders_data[] = $row;
        }
    }
    $res->close();
    if(!empty($ders_data[1]))
    {
        $toplam_ders_saati = $ders_data[1]['donem_saati']; // Dönem saati -> (Teorik saat + Pratik saat) * 14
    }
    $res = mysqli_query($con, $sql);
    if(mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $data[] = $row;
        }
    }
}
?>
<!DOCTYPE HTML>
<html lang="en-us">
<head>
    <script src="js/jquery-2.0.3.min.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
    <link href="css/bootstrap-editable.css" rel="stylesheet">
    <script src="js/bootstrap-editable.js"></script>
    <script src="js/bootstrap-editable.min.js"></script>
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<div class="container" id="tum_sayfa">
    <div id="dersler">
        <?php
        if(!empty($data[1]))
        {
            echo '<h1>' . $data[1]['ders_kodu'] . '<span class="badge badge-secondary"> Section:' . $data[1]['section_kod'] . '</span></h1>';
        }
        ?>
        <br />
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>İşlenen Ders Saatleri</th>
                <th>Hafta 1</th>
                <th>Hafta 2</th>
                <th>Hafta 3</th>
                <th>Hafta 4</th>
                <th>Hafta 5</th>
                <th>Hafta 6</th>
                <th>Hafta 7</th>
                <th>Hafta 8</th>
                <th>Hafta 9</th>
                <th>Hafta 10</th>
                <th>Hafta 11</th>
                <th>Hafta 12</th>
                <th>Hafta 13</th>
                <th>Hafta 14</th>
                <th>Hafta 15</th>
                <th>Hafta 16</th>
                <th>Hafta 17</th>
                <th>Toplam Ders Saati</th>
            </tr>
            </thead>
            <tbody id="ders_data">
            <?php
            if ( !empty($ders_data)) {
                echo "<td style='background-color: lightgrey'></td>";
                for($i = 1; $i < count($ders_data); $i++)
                {
                    echo "<td data-name='saat' id='hafta". $i ."' data-hafta='" . $i ."' data-type='text' class='saat' data-pk='". $ders_data[$i]['id'] ."'>" . $ders_data[$i]['saat'] . "</td>";
                    $islenenDersSayac++;
                    $islenen_ders_saati += $ders_data[$i]['saat'];
                }
                $last_seen_pk = $ders_data[$i-1]['id'];
                for($i = $islenenDersSayac; $i < 17; $i++)
                {
                    echo "<td data-name='saat' id='hafta". ($i + 1) ."' data-hafta='" . ($i + 1) ."' data-type='text' class='saat' data-pk='". $last_seen_pk ."'></td>";
                }
                echo "<td id='donem-saat-toplami'>";
                echo ($toplam_ders_saati < $islenen_ders_saati) ? $islenen_ders_saati : $toplam_ders_saati;
                echo " saat</td>";
                echo "</tr>";
                if($toplam_ders_saati < $islenen_ders_saati)
                {
                    echo '<div class="alert alert-warning" role="alert">İşlenen ders saati, standart dönem saatini aşıyor. Hesaplamalar işlenen saat üzerinden yapılacak.</div>';
                }
            }
            ?>
            </tbody>
        </table>
    </div>
    <div id="devamsizliklar">
        <br />
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Öğrenci NO</th>
                <th>Ad</th>
                <th>Soyad</th>
                <th>Hafta 1</th>
                <th>Hafta 2</th>
                <th>Hafta 3</th>
                <th>Hafta 4</th>
                <th>Hafta 5</th>
                <th>Hafta 6</th>
                <th>Hafta 7</th>
                <th>Hafta 8</th>
                <th>Hafta 9</th>
                <th>Hafta 10</th>
                <th>Hafta 11</th>
                <th>Hafta 12</th>
                <th>Hafta 13</th>
                <th>Hafta 14</th>
                <th>Hafta 15</th>
                <th>Hafta 16</th>
                <th>Hafta 17</th>
                <th>Devamsızlık</th>
            </tr>
            </thead>
            <tbody id="devamsizlik_data">
            <?php
            if(!empty($data[1])) {
                for($i = 1; $i < count($data); $i++) {
                    $next = isset($data[$i+1]) ? $data[$i+1] : null; // next
                    $prev = isset($data[$i-1]) ? $data[$i-1] : null; // previous
                    $curr = isset($data[$i])   ? $data[$i]   : null; //current
                    // Öğrenci numarası aynı devam ediyorsa aynı satırda devam et
                    if($prev['ogrenci_no'] == $curr['ogrenci_no'])
                    {
                        echo "<td data-no='". ($sayac - 2) ."' data-name='saat' data-type='text' class='saat' data-pk='". $curr['id'] ."'>" . $curr['saat'] . "</td>";
                        $devamsizlik_saati += $curr['saat'];
                        $sayac ++;
                        //Sonraki data boşsa (Son satır) kalan sütunları boş olarak yaz
                        if(is_null($next))
                        {
                            if($toplam_ders_saati || $islenen_ders_saati)
                            {
                                $final_saat = ($toplam_ders_saati < $islenen_ders_saati) ? $islenen_ders_saati : $toplam_ders_saati;
                                $devamsizlik_saati = intval(($devamsizlik_saati / $final_saat) * 100);
                                echo ($devamsizlik_saati > 30) ? "<td class='kaldi' " : "<td ";
                                echo "id='ogrenci-satir-toplam-". $satir_takip ."'>%";
                                echo $devamsizlik_saati . "</td>";
                            }
                            echo "</tr>";
                            $satir_takip++;
                        }
                    }
                    elseif(!is_null($prev)) {
                        if($toplam_ders_saati || $islenen_ders_saati)
                        {
                            $final_saat = ($toplam_ders_saati < $islenen_ders_saati) ? $islenen_ders_saati : $toplam_ders_saati;
                            $devamsizlik_saati = intval(($devamsizlik_saati / $final_saat) * 100);
                            echo ($devamsizlik_saati > 30) ? "<td class='kaldi' " : "<td ";
                            echo "id='ogrenci-satir-toplam-". $satir_takip ."'>%";
                            echo $devamsizlik_saati . "</td>";
                        }
                        $devamsizlik_saati = 0;
                        $sayac = 4;
                        echo "</tr>";
                        $satir_takip++;
                    }
                    if(is_null($prev) || $curr['ogrenci_no'] != $prev['ogrenci_no'])
                    {
                        echo "<tr id='ogrenci-devamsizlik-satir-". $satir_takip ."'>";
                        echo "<td><b>" . $curr['ogrenci_no'] . "</b></td>";
                        echo "<td>" . strtoupper($curr['ad']) . "</td>";
                        echo "<td>" . strtoupper($curr['soyad']) . "</td>";
                        echo "<td data-no='". ($sayac - 3) ."' data-name='saat' data-type='text' class='saat' data-pk='". $curr['id'] ."'>" . $curr['saat'] . "</td>";
                        $devamsizlik_saati += $curr['saat'];
                    }
                }
            }
            else {
                echo "<tr><td colspan='21'>Kayıt Bulunamadı</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
<script type="text/javascript" language="javascript" >
    $('#ders_data').editable({
        ajaxOptions: {
            cache: false,
        },
        container: 'body',
        selector: 'td.saat',
        params: function (params) {
            params.hafta = $(this).editable().data('hafta');
            return params;
        },
        url: "update.php",
        title: 'Saat',
        type: "POST",
        emptytext: '',
        dataType: 'json',
        validate: function(value){
            if($.trim(value) == '')
            {
                return 'Bu alan boş bırakılamaz';
            }
            if(value < 0)
            {
                return "İşlenen saat 0'dan küçük olamaz!";
            }
            var regex = /^[0-9]+$/;
            if(! regex.test(value))
            {
                return 'Sadece rakam girin!';
            }

        },
        success: function(response){
            var ajax_load = "<img src='http://automobiles.honda.com/images/current-offers/small-loading.gif' alt='loading...' />";
            var loadUrl = "http://localhost/testing/devamsizlik-liste-test.php?ders=<?php echo $_GET['ders'] ?>&hoca=<?php echo $_GET['hoca'] ?>";
            loadUrl += '&_=' + (new Date()).getTime();
            $("body").html(ajax_load).load(loadUrl);
        }
    });
    $('#devamsizlik_data').editable({
        params: function (params) {
            params.param1 = $(this).editable().data('no');
            return params;
        },
        container: 'body',
        selector: 'td.saat',
        url: "update-devamsizlik.php",
        title: 'Saat',
        defaultValue: '0',
        type: "POST",
        dataType: 'json',
        validate: function(value){
            var haftaNo = $(this).editable().data('no');
            var islenenDersSaati = $("#hafta" + haftaNo).text();
            islenenDersSaati = parseInt(islenenDersSaati);
            if($.trim(value) == '')
            {
                return 'Bu alan boş bırakılamaz';
            }
            if(value < 0)
            {
                return "İşlenen saat 0'dan küçük olamaz!";
            }
            if(value < $('#'))
                var regex = /^[0-9]+$/;
            if(! regex.test(value))
            {
                return 'Sadece rakam girin!';
            }
            if(value > islenenDersSaati)
            {
                return 'Devamsızlık saati, işlenen ders saatini geçemez!';
            }
        },
        success: function(response){
            var ajax_load = "<img src='http://automobiles.honda.com/images/current-offers/small-loading.gif' alt='loading...' />";
            var loadUrl = "http://localhost/testing/devamsizlik-liste-test.php?ders=<?php echo $_GET['ders'] ?>&hoca=<?php echo $_GET['hoca'] ?>";
            loadUrl += '&_=' + (new Date()).getTime();
            $("body").html(ajax_load).load(loadUrl);
        }
    });
</script>