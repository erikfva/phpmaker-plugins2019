<?php

use PHPMaker2019\gvadmin as phpfn;

define("EW_PROJECT_NAME", phpfn\PROJECT_NAME, TRUE); // Project name
define("EW_TABLE_MASTER_TABLE", phpfn\TABLE_MASTER_TABLE, TRUE); // Master table

function CurrentPage(){
    return phpfn\CurrentPage();
}
function isLoggedIn(){
    return phpfn\isLoggedIn();
}
function AddClientScript($src, $attrs = NULL) {
    return phpfn\AddClientScript($src, $attrs);
}
function AddStylesheet($href, $attrs = NULL) {
    return phpfn\AddStylesheet($href, $attrs);
}
function CurrentPageName() {
    return phpfn\CurrentPageName();
}
// Get breadcrumb object
function &Breadcrumb() {
	return phpfn\Breadcrumb();
}
// Check if mobile device
function IsMobile() {
    return phpfn\IsMobile(); 
}
function CurrentPageID(){
    return phpfn\CurrentPageID();
}
?>