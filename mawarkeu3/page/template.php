<?php
$themes = isset($_REQUEST['themes']) ? $_REQUEST['themes'] : 'default';
$username = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Aplikasi Keuangan Yayasan Pendidikan Matholi'ul Anwar Simo Sungelebak Karanggeneng Lamongan</title>
        <link rel="shortcut icon" href="favicon.ico">
        <link rel="icon" href="favicon.ico" type="image/x-icon" >
        <link rel="stylesheet" type="text/css" href="include/easyui/themes/default/easyui.css">
        <link rel="stylesheet" type="text/css" href="include/easyui/themes/icon.css">
        <script type="text/javascript" src="include/easyui/jquery.min.js"></script>
        <script type="text/javascript" src="include/easyui/jquery.easyui.min.js"></script>
        <script type="text/javascript" src="include/easyui/datagrid-detailview.js"></script>
        <?php
        /* ---------------- Definisi situs ---------------- */
        define("HEADERKETERANGANSEKOLAH01", "Sistem Keuangan");
        define("HEADERKETERANGANSEKOLAH02", "Yayasan Pendidikan Matholi'ul Anwar");
        define("HEADERKETERANGANSEKOLAH03", "Simo Sungelebak Karanggeneng Lamongan");
        define("FOOTER_TEXT", "Copyright &copy; " . date('Y') . " by Yayasan Pendidikan Matholi'ul Anwar, Simo, Sungelebak, Karanggeneng, Lamongan");
        ?> 
        
        <script>

            $.extend($.fn.datebox.defaults, {
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return  y + '-' + (m < 10 ? ('0' + m) : m) + '-' + (d < 10 ? ('0' + d) : d);
                },
                parser: function(s) {
                    if (!s)
                        return new Date();
                    var ss = s.split('-');
                    var d = parseInt(ss[2], 10);
                    var m = parseInt(ss[1], 10);
                    var y = parseInt(ss[0], 10);
                    if (!isNaN(y) && !isNaN(m) && !isNaN(d)) {
                        return new Date(y, m - 1, d);
                    } else {
                        return new Date();
                    }
                }
            });

            var index = 0;
            function openMenu(nmMenu, url) {
                index++;
                if ($('#mawarkeu3_tt').tabs('exists', nmMenu)) {
                    $('#mawarkeu3_tt').tabs('select', nmMenu);
                } else {
                    $('#mawarkeu3_tt').tabs('add', {
                        title: nmMenu,
                        href: 'ajaxroute.php?p=' + url,
                        closable: true
                    });
                }
            }

            function logout() {
                window.location.replace("?logout");
            }
        </script>
        
    </head>
    <body class="easyui-layout">
        <div data-options="region:'north', split:false, href:'ajaxroute.php?p=menuHeader'" style="height: 45px;padding:5px;border:1px solid #ddd;">
        </div>
        <div data-options="region:'center'" >
            <div id='mawarkeu3_tt' class="easyui-tabs" data-options="fit: true">
                <div title="Home" style="padding: 10px;background: url('css/login/images/bg.jpg');color:white;">
                    <?= HEADERKETERANGANSEKOLAH01 ?><br>
                    <?= HEADERKETERANGANSEKOLAH02 ?><br>
                    <?= HEADERKETERANGANSEKOLAH03 ?><br>
                </div>
            </div>
        </div>
        <div data-options="region:'south'" style="height:25px;padding:2px;text-align: center;font-style: italic;font-size: 10px;">
            <span><?= FOOTER_TEXT ?></span>
        </div>

    </body>
</html>
