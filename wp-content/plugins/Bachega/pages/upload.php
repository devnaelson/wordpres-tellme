<form enctype="multipart/form-data" method="post">
<div class="p-3">
<div class="input-group">
  <input type="file" class="form-control" name="target_upload" id="excelUp">
  <input type="hidden" id="execkeys" value="<?=EXEC_ENCRYPT;?>">
  <input type="hidden" id="url_site" value="<?php echo get_page_by_title("sheet-request")->guid; ?>">
  <button class="btn btn-outline-secondary" type="button" id="exec_send" onclick="sendStructure();">Enviar</button>
  
</div>
</div>
</form>

<div class="row text-center">
    <div class="col-4" id="sheet_excel">
    </div>
    <div class="col-4 bgbox" id="sheet_detect">
    </div>
      <div class="col-4 bgbox">
      <div class="scrollExec">
          <?php
          global $wpdb;
          $dPFields = array();
          $dPFields = $wpdb->get_results("SHOW COLUMNS FROM bd_erp_peoples");
          $resultFiels = get_option('myfields');
          for ($bI = 0; $bI < count($resultFiels); $bI++) echo '<div draggable="true" class="box-fields lessthan" data-side="left" data-column="' . $resultFiels[$bI]['name'] . '" data-table="bd_erp_peoplemeta" >' . $resultFiels[$bI]['name'] . '</div>';
          for ($bI = 0; $bI < count($dPFields); $bI++) echo '<div draggable="true" class="box-fields" data-people-field="' . $dPFields[$bI]->Field . '" data-table="bd_erp_peoples">' . $dPFields[$bI]->Field . '</div>';
          ?>
      </div>
    </div>
</div>