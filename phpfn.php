<?php

use PHPMaker2020\Medical as phpfn;

define("EW_PROJECT_NAME", phpfn\PROJECT_NAME); // Project name
//define("EW_TABLE_MASTER_TABLE", Config("TABLE_MASTER_TABLE"), true); // Master table

class Language extends phpfn\Language
{}
class UserProfile extends phpfn\UserProfile
{}
class AdvancedSecurity extends phpfn\AdvancedSecurity
{}
class PrevNextPager extends phpfn\PrevNextPager
{}
class ExportJson extends phpfn\ExportJson
{
    public function &GetItems()
    {
        return $this->Items;
    }
}

function &GetExportDocumentJson(&$tbl, $style)
{
    $inst = new ExportJson($tbl, $style);
    return $inst;
}
function Config()
{
    $arg_list = func_get_args();
    return phpfn\Config(...$arg_list);
}
function CurrentProjectID()
{
    return phpfn\CurrentProjectID();
}
// Permission denied message
function DeniedMessage()
{
    return phpfn\DeniedMessage();
}

function CurrentPage()
{
    return phpfn\CurrentPage();
}
function isLoggedIn()
{
    return phpfn\isLoggedIn();
}
function AddClientScript($src, $attrs = null)
{
    return phpfn\AddClientScript($src, $attrs);
}
function AddStylesheet($href, $attrs = null)
{
    return phpfn\AddStylesheet($href, $attrs);
}
function CurrentPageName()
{
    return phpfn\CurrentPageName();
}
// Get breadcrumb object
function &Breadcrumb()
{
    return phpfn\Breadcrumb();
}
// Check if mobile device
function IsMobile()
{
    return phpfn\IsMobile();
}
function CurrentPageID()
{
    return phpfn\CurrentPageID();
}
function IsPost()
{
    return phpfn\IsPost();
}
function IsGet()
{
    return phpfn\IsGet();
}

function Get($name, $default = null)
{
    return phpfn\Get($name, $default);
}
function Post($name, $default = null)
{
    return phpfn\Post($name, $default);
}
function DomainUrl()
{
    return phpfn\DomainUrl();
}
//get protected method, reference -> https: //stackoverflow.com/questions/20334355/how-to-get-protected-property-of-object-in-php
function getModel($page)
{

    $p = $page;
    if ($page->PageID != "add" && file_exists(getcwd() . "//classes//" . $page->TableName . "_add.php")) {
        $class = phpfn\PROJECT_NAMESPACE . $page->TableName . "_add";
        $p = new $class();
    }

    //get protected method, reference -> https: //stackoverflow.com/questions/20334355/how-to-get-protected-property-of-object-in-php
    $getNewRecord = function () {
        return $this->newRow();
    };

    return $getNewRecord->call($p);
};
function errorFn($err)
{
    CurrentPage()->setFailureMessage(Conn()->errorMsg());
}
function Conn()
{
    return phpfn\Conn();

}
