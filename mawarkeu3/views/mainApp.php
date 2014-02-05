<script>
    $.post(
            '?p=mainApp',
            function(result){
                $('#centerDIV1').html(result);
            }
    );

</script>
<input id="input_siswa">
<input id='input_jenispembayaran'>
<div id="centerDIV1"></div>
