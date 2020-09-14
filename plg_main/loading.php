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

//Remove webservice from cmdx when not request.
$url_key = IsPost() ? Post("cmdx") : Get("cmdx");
$isWebservice = !empty($url_key) && strpos($url_key, "webservice") >= 0;
if (!$isWebservice) {
    $cmdx = array_diff($cmdx, ["webservice"]);
}

if (IsPost() && !empty(Post("returnUrl"))) {
    CurrentPage()->setReturnUrl(Post("returnUrl"));
}

if (!empty($_SESSION[CurrentPage()->TableName . "_hidemasterheader"])) {
    //var_dump("hidemasterheader");exit;
    $_SESSION[CurrentPage()->TableName . "_hidemasterheader"] = null;
    $_GET["hidemasterheader"] = true;
    $_POST["hidemasterheader"] = true;
}

if (Get("hidemasterheader") || Post("hidemasterheader")) { //change page links
    if (!empty(CurrentPage()->GridAddUrl)) {
        CurrentPage()->GridAddUrl = CurrentPage()->GridAddUrl . "&hidemasterheader=true";
    }
    if (CurrentPage()->PageID == "delete") {
        $_SESSION[CurrentPage()->TableName . "_hidemasterheader"] = true;
    }
}
