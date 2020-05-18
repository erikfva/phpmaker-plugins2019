<?php
//include_once "plugins/phpfn.php";
if (file_exists($plgConf["plugins_path"] . "phpfn.php")) {
    include_once $plgConf["plugins_path"] . "phpfn.php";
} else {
    include_once "../" . $plgConf["plugins_path"] . "phpfn.php";
}

if (@$GLOBALS["_SERVER"]["REQUEST_METHOD"] == "POST" && empty($_POST)) {
    $_POST = json_decode(trim(file_get_contents('php://input')), true);
}

//-----------------------------------//
//***FIX: limpiando 'amp;' en los indices de $_POST
// cuando se llama el script desde file_get_content
//-----------------------------------//
if (!empty($_POST)) {

    foreach ($_POST as $key => $value) {
        //var_dump($key, $value);
        $newkey = str_replace('amp;', '', $key);
        if ($newkey != $key) {
            global $_POST;
            $_POST[$newkey] = $_POST[$key];
            unset($_POST[$key]);
        }
    }
}

//--------------------------------------------------//
//***Extend commans processing
//--------------------------------------------------//
global $cmdx, $_GET, $_POST;

$cmdx = array_filter(preg_split("[,]", isset($_GET["cmdx"]) ? $_GET["cmdx"] : @$_POST["cmdx"]));
if (empty($cmdx)) {
    $cmdx = [];
}

//echo "plg_main: userfn.php\n";
