<form enctype="multipart/form-data" method="post">
<div class="p-3">
<div class="input-group">
  <input type="file" class="form-control" name="target_upload" id="excelUp">
  <input type="hidden" id="execkeys" value="<?=EXEC_ENCRYPT;?>">
  <input type="hidden" id="url_site" value="<?php echo plugins_url();?>">
  <button class="btn btn-outline-secondary" type="button" id="exec_send">Enviar</button>
</div>
</div>
</form>

<div class="row text-center">
    <div class="col-4" id="sheet_excel">
    </div>
    <div class="col-4 bgbox" id="sheet_detect">
    </div>
    <div class="col-4 bgbox">
        <?php
        global $wpdb;
        $dPFields = array();
        $dPFields = $wpdb->get_results("SHOW COLUMNS FROM bd_erp_peoples");
        $resultFiels = get_option('myfields');
        for ($bI = 0; $bI < count($resultFiels); $bI++) echo '<div draggable="true" class="box-fields lessthan" data-side="left" data-column="' . $resultFiels[$bI]['name'] . '">' . $resultFiels[$bI]['name'] . '</div>';
        for ($bI = 0; $bI < count($dPFields); $bI++) echo '<div draggable="true" class="box-fields" data-people-field="' . $dPFields[$bI]->Field . '">' . $dPFields[$bI]->Field . '</div>';
        ?>
    </div>
</div>