<?php
include_once $plgConf["plugins_path"] . "phpfn.php";

global $plgConf, $cmdx;

//Para mostrar solo la tabla de datos sin encabezado y pie de pagina
global $SkipHeaderFooter;
if (!empty($cmdx) && in_array("SkipHeaderFooter", $cmdx)) {
    $SkipHeaderFooter = true;
}

global $Breadcrumb, $Language;
if (isset($Breadcrumb->Links)) {

    //Quitando el link de "inicio" del path
    if ($Breadcrumb->Links[0][0] == "home") {array_splice($Breadcrumb->Links, 0, 1);}

    //Agregando los botones de accion en la misma fila del path
    if (isset(CurrentPage()->PageID) && (CurrentPage()->PageID == "edit" || CurrentPage()->PageID == "add")) {
        global $customstyle;
        //$PageCaption = $Language->Phrase("EditBtn");
        array_splice($Breadcrumb->Links, count($Breadcrumb->Links) - 1, 0, array(array("editbtn", "SaveBtn", "javascript:$('#btnAction').trigger('click');\" class=\"btn btn-sm btn-primary", "", CurrentPage()->TableVar, false)));
        css(".breadcrumb .active{display:none !important}");
    }
    if (isset(CurrentPage()->PageID) && CurrentPage()->PageID == "search") {
        global $customstyle;
        //$PageCaption = $Language->Phrase("SearchBtn");
        array_splice($Breadcrumb->Links, count($Breadcrumb->Links) - 1, 0, array(array("searchbtn", "SearchBtn", "javascript:$('#btnAction').trigger('click');\" class=\"btn btn-sm btn-primary", "", CurrentPage()->TableVar, false)));
        css(".breadcrumb .active{display:none !important}");
    }
    //En algunos casos es necesario adicionar el link para volver atras
    if ((empty($opciones) || !strpos($opciones, "hidebkmainpage")) && isset(CurrentPage()->TableVar) && !empty($_SESSION[EW_PROJECT_NAME . "_" . CurrentPage()->TableVar . "_" . Config("TABLE_MASTER_TABLE")]) && count($Breadcrumb->Links) == 1) {
        $masterTbl = $_SESSION[EW_PROJECT_NAME . "_" . CurrentPage()->TableVar . "_" . Config("TABLE_MASTER_TABLE")];
        $PageLnk = @$_SESSION[EW_PROJECT_NAME . "_" . $_SESSION[EW_PROJECT_NAME . "_" . CurrentPage()->TableVar . "_" . Config("TABLE_MASTER_TABLE")] . "_exportreturn"];
        array_splice($Breadcrumb->Links, count($Breadcrumb->Links) - 1, 0, array(array(
            $masterTbl,
            $masterTbl,
            ew_DomainUrl() . $PageLnk,
            "",
            $masterTbl,
            false)));
    }

}
?>

<?php AddStylesheet($plgConf["plugins_path"] . "plg_main/loading/css/loading.css");?>
<?php AddClientScript($plgConf["plugins_path"] . "plg_main/userfn.js");?>

<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1, user-scalable=no' name='viewport' />
<!-- Loading spinner... -->
<?php
//echo "plg_webservice: header.php";exit();
?>
<style>
#ew-page-spinner {
    display: none !important;
}
</style>
<?php
if (IsLoggedIn() && (empty(CurrentPage()->Export) && !IsMobile())) {
    ?>
			<div class="pageload-overlay">
				<!-- the component -->
				<ul class="bokeh">
					<li></li>
					<li></li>
					<li></li>
					<li></li>
				</ul>
			</div>
<?php }?>