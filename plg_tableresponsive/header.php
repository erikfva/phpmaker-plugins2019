<?php
if (CurrentPageID() == "list") { //Generate field labels class for vertical tables.
    global $plgConf;
    $plugins_path = $plgConf["plugins_path"];
    $FieldList = array();
    $orderField = "";
    $orderBy = CurrentPage()->getSessionOrderBy();
    $orderType = strpos($orderBy, "ASC") !== false ? "ASC" : "DESC";
    $SortImage = $orderType == 'ASC' ? 'asc_sort.svg' : 'des_sort.svg';

    echo "<style>\n";
    echo <<<END
    @media screen (max-width: 576px) and (min-width: 300em) {
        .ew-grid:not(.ew-master-div) table > thead,
        .vertical-table > thead {
            display: table-header-group; }
        .ew-grid:not(.ew-master-div) table > thead > th:not(:last-child),
        .vertical-table > thead > th:not(:last-child) {
            border-right: 1px solid #999; }
        .ew-grid:not(.ew-master-div) table > thead > tr,
        .ew-grid:not(.ew-master-div) table > tbody tr,
        .vertical-table > thead > tr,
        .vertical-table > tbody > tr {
            display: table-row; padding: 0; }
        .ew-grid:not(.ew-master-div) table > thead > tr > td,
        .ew-grid:not(.ew-master-div) table > tbody > tr > td ,
        .vertical-table > thead > tr > td,
        .vertical-table > tbody > tr > td  {
            display: table-cell; }
        .ew-grid:not(.ew-master-div) table > tbody > td:not(:last-child),
        .vertical-table > tbody > td:not(:last-child) {
            border-bottom: none;
            border-right: 1px solid #DDD; }
    }
    .sort-control{
        display:none;
    }
    @media (max-width: 576px) {
        .sort-control{
            display:inline-flex;
        }
        /*transform vertical tables */
        .ew-grid:not(.ew-master-div){
            min-width: inherit;
        }
        .ew-grid:not(.ew-master-div) table,
        table.vertical-table {
        border-collapse: collapse; min-width: 280px; margin-bottom: 15px !important
        }
        .ew-grid:not(.ew-master-div) table > thead,
        .vertical-table > thead {
        visibility: hidden;
        position: absolute;
        overflow: hidden;
        }
        .ew-grid:not(.ew-master-div) table > thead > tr,
        .ew-grid:not(.ew-master-div) table > tbody > tr,
        .vertical-table > thead > tr,
        .vertical-table > tbody > tr{
        display: block; padding: 0.5em;
        clear: both;
        }
        .ew-grid:not(.ew-master-div) table > thead > tr > td,
        .ew-grid:not(.ew-master-div) table > tbody > tr > td,
        .vertical-table > thead > tr > td,
        .vertical-table > tbody > tr > td{
        display: block;
        padding: .5rem .5rem
        }
        .ew-grid:not(.ew-master-div) table > tbody > td:not(:last-child),
        .vertical-table > tbody > td:not(:last-child) {
        border-bottom: 1px solid #DDD; }
        .ew-grid:not(.ew-master-div) table .ew-list-option-body,
        .vertical-table  .ew-list-option-body{float:right}

END;
    foreach (CurrentPage()->fields as $FldVar => $field) {
        $FieldList[] = array('id' => $field->FieldVar, 'name' => $FldVar,
            'caption' => $field->caption(),
            'sortable' => (CurrentPage()->SortUrl($field) == "" ? false : true),
            'visible' => $field->Visible);

        $FldCaption = $field->caption();
        $dataName = $FldVar;
        echo <<<END
		.ew-grid:not(.ew-master-div) table td[data-name="$dataName"]::before,
		.vertical-table td[data-name="$dataName"]::before{
			content:"$FldCaption:    ";
			white-space: pre;
			color: navy;
			font-weight: bold;
		}\n
END;
        if (strpos($orderBy, '`' . $FldVar . '`') !== false) {
            $orderField = $FldVar;
            echo <<<END
			.ew-grid:not(.ew-master-div) table td[data-name="$dataName"]::before,
			.vertical-table td[data-name="$dataName"]::before{
				content:"$FldCaption     ";
				background: url("$plugins_path/plg_tableresponsive/img/svg/$SortImage") no-repeat;
				background-position-x: right;
            }
            .sort-symbol{
				background: url("$plugins_path/plg_tableresponsive/img/svg/$SortImage") no-repeat;
            }
            \n
END;
        }
    }
    echo "}\n";
    echo "</style>\n";
    //var_dump($FieldList);
    ?>
<!-- Right Navbar -->
<script type="text/html" id="sort-field-control" class="ew-js-template"  data-name="sort-control" data-seq="10" data-method="appendTo" data-target="#gmp_<?php echo CurrentPage()->TableName; ?>">
<div class="sort-control position-absolute-top-right" style="top: -45px">
    <div class="dropdown">
    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Orden
    </a>

    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
        <?php foreach ($FieldList as $field) {
        if ($field["sortable"] && $field["visible"]) {?>

            <a class="dropdown-item" href="#" onclick="ew.sort(event, '<?php echo CurrentPageName() . '?order=' . $field['name'] . '&amp;ordertype=' . ($field['name'] == $orderField ? ($orderType == 'ASC' ? 'DESC' : 'ASC') : 'ASC'); ?>', 1); return false;">
                <?php echo $field["caption"] ?><?php if (strpos($orderBy, '`' . $field["name"] . '`') !== false) {echo ' <span class="sort-symbol">&nbsp;&nbsp;&nbsp;</span>';}?>
            </a>
        <?php }}?>

    </div>
    </div>
</div>
</script>
<?php
}
