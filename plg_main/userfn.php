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
    while (list($key, $value) = each($_POST)) {
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
$PageName = CurrentPageName();

$cmdx = array_filter(preg_split("[,]", isset($_GET["cmdx"]) ? $_GET["cmdx"] : @$_POST["cmdx"]));
if (empty($cmdx)) {
    $cmdx = [];
}

$cmdxSession = !empty(@$_SESSION[$PageName . "_cmdx"]) ? $_SESSION[$PageName . "_cmdx"] : [];
foreach ($cmdx as $cmd) {
    if (strpos($cmd, "reset") === false) {
        if (!in_array($cmd, $cmdxSession)) {
            $cmdxSession[] = $cmd;
        }

    } else {
        $key = str_replace("reset", "", $cmd);
        $cmdxSession = array_filter($cmdxSession, function ($k) use ($key) {
            return $k != $key;
        });
    }
}

if (!empty($cmdx) && in_array("reset", $cmdx)) {
    $cmdxSession = [];
}
$_SESSION[$PageName . "_cmdx"] = $cmdxSession;
$cmdx = $_SESSION[$PageName . "_cmdx"];

//echo "plg_main: userfn.php\n";
