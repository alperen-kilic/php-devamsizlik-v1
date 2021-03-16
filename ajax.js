$(document).ready(function() {
    $("#hoca").change(function() {
        var hoca_id = $(this).val();
        if(hoca_id != "") {
            $.ajax({
                url:"ders-bul.php",
                data:{h_id:hoca_id},
                type:'POST',
                success:function(response) {
                    var resp = $.trim(response);
                    $("#ders").html(resp);
                }
            });
        } else {
            $("#ders").html("<option value=''>------- Ders Seçin --------</option>");
        }
    });


    //----------------------------------------------------------------------------------------------------------------//

});