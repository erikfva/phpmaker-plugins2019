<?php
global $plgConf;
$plugins_path = $RELATIVE_PATH . "phpmaker-plugins/";
$plgConf = array(
    "plugins_path" => $plugins_path,
    "include" => array(),
    "plg_main" => array(
        "userfn" => "plg_main/userfn.php",
        "loading" => "plg_main/loading.php",
        "header" => "plg_main/header.php",
        "footer" => "plg_main/footer.php",
        "client_script" => "plg_main/client_script.php",
    ),
    "plg_selectcol" => array("loading" => "plg_selectcol/loading.php",
        "client_script" => "plg_selectcol/client_script.php"),
    "plg_metro" => array("header" => "plg_main/header_metro.php"),
/*
"plg_coolui" => array("userfn" => "plg_coolui/userfn.php",
"header" => "plg_coolui/header.php",
"footer" => "plg_coolui/footer.php"),
 */
    "plg_tableresponsive" => array(
        "header" => "plg_tableresponsive/header.php",
        "footer" => "plg_tableresponsive/footer.php",
    ),
    "plg_uidatetime" => array("header" => "plg_uidatetime/header.php",
        "footer" => "plg_uidatetime/footer.php"),
    "plg_autosizetextarea" => array("header" => "plg_autosizetextarea/header.php",
        "footer" => "plg_autosizetextarea/footer.php"),
    "plg_utm" => array("header" => "plg_utm/header.php",
        "footer" => "plg_utm/footer.php"),
    "plg_webservice" => array(
        "header" => "plg_webservice/header.php",
        "userfn" => "plg_webservice/userfn.php",
        "loading" => "plg_webservice/loading.php",
        "rendering" => "plg_webservice/rendering.php",
        "unloaded" => "plg_webservice/unloaded.php",
    ),
);

function addPlg($plgNames, $page = "")
{
    //echo "** addPlg **";
    global $plgConf;
    if (empty($page)) {
        $page = CurrentPage()->PageObjName;
    }

    $plgList = explode(",", $plgNames);
    foreach ($plgList as &$plg) {
        $plg = trim($plg);
    }
    $plgNames = implode(",", $plgList);

    if (empty($plgConf["include"][$page]) && $page != "all") {

        foreach ($plgList as $plg) {
            if (!empty($plgConf[$plg]["loading"])) {
                //echo "incluyendo:".$plgConf[$plg]["loading"]." position:loading page:".$page;
                try {
                    include_once $plgConf["plugins_path"] . $plgConf[$plg]["loading"];
                } catch (Exception $e) {
                    include_once "../" . $plgConf["plugins_path"] . $plgConf[$plg]["loading"];
                }

            }
        }
    }

    $plgConf["include"][$page] = (!empty($plgConf["include"][$page]) ? $plgConf["include"][CurrentPage()->PageObjName] . "," : "") . $plgNames;
    //var_dump($plgConf);
}

function includePlg($position = "header", $page = "")
{
    //echo "** includePlg **";
    global $plgConf;
    $plgNames = !empty($plgConf["include"]["all"]) ? $plgConf["include"]["all"] : ""; //plugins para todas las paginas "all"
    if (empty($page) && !empty(CurrentPage()->PageObjName)) {
        $page = CurrentPage()->PageObjName;
    }

    $plgNames .= (!empty($plgNames) ? "," : "") . (!empty($plgConf["include"][$page]) ? $plgConf["include"][$page] : "");
    $plgList = explode(",", $plgNames);
// var_dump($plgList,$position);
    foreach ($plgList as $plg) {
        if (!empty($plgConf[$plg]) && !empty($plgConf[$plg][$position])) {
            //    echo "incluyendo:".$plgConf[$plg][$position]." position:".$position." page:".$page;
            include_once $plgConf["plugins_path"] . $plgConf[$plg][$position];
        }
    }
}

//incluyendo las funciones de usuario de cada plugin (para todas las paginas)
foreach ($plgConf as $plg) {
    if (!empty($plg["userfn"])) {
        if (file_exists($plgConf["plugins_path"] . $plg["userfn"])) {
            include_once $plgConf["plugins_path"] . $plg["userfn"];
        } else {
            include_once "../" . $plgConf["plugins_path"] . $plg["userfn"];
        }
    }
}

//Agregando los plugins para todas las páginas
//addPlg("plg_main,plg_coolui,plg_webservice", "all");
addPlg("plg_main, plg_webservice, plg_tableresponsive", "all");
