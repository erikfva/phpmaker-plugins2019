<?php
include_once $plgConf["plugins_path"] . "phpfn.php";

//echo "plg_main: loading.php\n";

$PageName = pathinfo(CurrentPageName())['filename'];
$cmdxSession = !empty(@$_SESSION[$PageName . "_cmdx"]) ? $_SESSION[$PageName . "_cmdx"] : [];
global $cmdx;
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
