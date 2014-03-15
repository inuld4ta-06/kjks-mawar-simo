<?php
$themes = isset($_REQUEST['themes']) ? $_REQUEST['themes'] : 'default';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Aplikasi Keuangan Yayasan Pendidikan Matholi'ul Anwar Simo Sungelebak Karanggeneng Lamongan</title>
        <link rel="stylesheet" type="text/css" href="include/easyui/themes/<?= $themes ?>/easyui.css">
        <link rel="stylesheet" type="text/css" href="include/easyui/themes/icon.css">
        <script type="text/javascript" src="include/easyui/jquery.min.js"></script>
        <script type="text/javascript" src="include/easyui/jquery.easyui.min.js"></script>
        <?php
        /* ---------------- Definisi situs ---------------- */
        define("HEADERKETERANGANSEKOLAH01", "Sistem Keuangan");
        define("HEADERKETERANGANSEKOLAH02", "Yayasan Pendidikan Matholi'ul Anwar");
        define("HEADERKETERANGANSEKOLAH03", "Simo Sungelebak Karanggeneng Lamongan");
        define("FOOTER_TEXT", "Copyright &copy; " . date('Y') . " by Yayasan Pendidikan Matholi'ul Anwar, Simo, Sungelebak, Karanggeneng, Lamongan");
        ?> 
    </head>
    <body class="easyui-layout">
        <div data-options="region:'center'" >
            <script>
                var index = 0;
                function openMenu(nmMenu, url) {
                    index++;
                    if ($('#mawarkeu3_tt').tabs('exists', nmMenu)) {
                        $('#mawarkeu3_tt').tabs('select', nmMenu);
                    } else {
                        $('#mawarkeu3_tt').tabs('add', {
                            title: nmMenu,
                            href: 'controller/'+url+'.php',
                            closable: true
                        });
                    }
                }
            </script>
            <div style="padding:5px;border:1px solid #ddd">
                <?php
                        include_once 'menuHeader.php';
                ?>
                <a href="?logout" class="easyui-linkbutton" data-options="plain:true" style='float: right'>Logout</a>
            </div>
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
            <span style="float: right;margin-top: -3px;">
                <form id="kjksmawarsimo_themes_form" name="kjksmawarsimo_themes_form" method="post" action="" style="text-align: right;">
                    Theme 
                    <select id="themes" name="themes" onchange="document.forms['kjksmawarsimo_themes_form'].submit();">
                        <option value="black" <?= ($themes == 'black') ? ' selected' : ''; ?>>Black</option>
                        <option value="bootstrap" <?= ($themes == 'bootstrap') ? ' selected' : ''; ?>>Bootstrap</option>
                        <option value="default" <?= ($themes == 'default') ? ' selected' : ''; ?>>Default</option>
                        <option value="gray" <?= ($themes == 'gray') ? ' selected' : ''; ?>>Gray</option>
                        <option value="metro" <?= ($themes == 'metro') ? ' selected' : ''; ?>>Metro</option>
                    </select>
                </form>
            </span>
        </div>

    </body>
</html>
