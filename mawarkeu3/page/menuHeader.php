<?php
session_start();
$username = $_SESSION['user_name'];

// si user dapat role apa aja
$q1 = <<<q
select * from users_role where user_name='$username'
q;
$role = array();
foreach (pdoMysql_queryAll($q1) as $row1){
    array_push($role, $row1['user_role']);
}
$roles = "'" . implode("','", $role) . "'";

// role itu tadi dapat menu apa aja
$q2 = <<<q
select * from m_menu_role where role_id in ($roles)
q;
$menu = array();
foreach (pdoMysql_queryAll($q2) as $row2){
    array_push($menu, $row2['menu_id']);
}
$menus = implode(",", $menu);

// menu2 itu tadi ada di header mana aja
$q3 = <<<q
select * from m_menu where menu_id in (select parent_id from m_menu where menu_id in ($menus) and enabled=1) and type='MENU_HEADER'
q;

foreach (pdoMysql_queryAll($q3) as $row3){
    $menuheaderName = $row3['name'];
    $menuheaderId = $row3['menu_id'];
        $html[] = <<<h
<a href="javascript:void(0)" class="easyui-menubutton" data-options="menu:'#menu$menuheaderName'">$menuheaderName</a>
<div id="menu$menuheaderName" style="width:200px">
h;
        $q4 = "select * from m_menu where menu_id in ($menus) and parent_id=$menuheaderId";
        foreach (pdoMysql_queryAll($q4) as $row4){
            $menuName = $row4['name'];
            $menuPath = $row4['path'];
            $html[] = <<<h
<div onclick="openMenu('$menuName', '$menuPath');">$menuName</div>
h;
        }
        $html[] = <<<h
        </div>
h;
}

if(count($html) > 0){
    $menus_ready = implode('', $html);
} else {
    $menus_ready = "";
}

echo <<<h
<table width="100%">
    <tr>
        <td>$menus_ready</td>
        <td style="vertical-align: middle;text-align: right;">
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true" iconCls="icon-no" onclick="logout();" title="Logout"><b>$username</b></a>
        </td>
    </tr>
</table>

h;


function pdoMysql_queryAll($sql) {
    $server = DB_HOST;
    $username = DB_USER;
    $password = DB_PASS;
    $dbase = DB_NAME;
    try {
        $db = new PDO("mysql:host=$server;dbname=$dbase", $username, $password);

        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $data = array();
        $result = $db->query($sql);
        foreach ($result as $row) {
            array_push($data, $row);
        }

        $db = null; // close the database connection
        return $data;
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
