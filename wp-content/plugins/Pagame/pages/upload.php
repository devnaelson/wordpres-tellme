<div class="row">
    <div class="col-6"></div>
    <div class="col-2 bg-primary text-center">
        People
    </div>
    <div class="col-2 bg-warning text-center">
        Meta people
    </div>
    <div class="col-2 bg-dark text-center text-white">
        File
    </div>
</div>
<div class="row p-1">
    <div class="col-6"></div>
    <div class="col-6 text-center">
        <div class="row">
            <div class="col-auto">
                <input type="text" value="ordin_ria" id="exeField_check">
            </div>
            <div class="col-auto" style="line-height: 35px;">
                Campo validação
            </div>
        </div>
        <div class="row">
            <div class="col-auto">
                <input type="text" value="bd_erp_peoples" id="exeTable_check">
            </div>
            <div class="col-auto" style="line-height: 35px;">
                Tabela principal criação da lead
            </div>
        </div>
        <div class="row">
            <div class="col-auto">
                <input type="text" value="40" id="exeLife_stage_check">
            </div>
            <div class="col-auto" style="line-height: 35px;">
                Life Stage
            </div>
        </div>
        <div class="row">
            <div class="col-auto">
                <input type="text" value="customer" id="execontectOwner_check">
            </div>
            <div class="col-auto" style="line-height: 35px;">
                Contact Owner
            </div>
        </div>
    </div>
</div>
<form enctype="multipart/form-data" method="post">
    <div class="p-3">
        <div class="input-group">
            <input type="file" class="form-control" name="target_upload" id="excelUp">
            <input type="hidden" id="execkeys" value="<?=EXEC_ENCRYPT;?>">
            <button class="btn btn-outline-secondary" type="button" id="exec_send"
                onclick="sendStructure();">Enviar</button>
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
          $people = array();
          $people = $wpdb->get_results("SHOW COLUMNS FROM bd_erp_peoples");
          $files_ = array();
          $files_ = $wpdb->get_results("SHOW COLUMNS FROM bd_erp_employee_dir_file_relationship");
          $meta_people = get_option('erp-contact-fields');
          for ($bI = 0; $bI < count($meta_people); $bI++) echo '<div draggable="true" class="box-fields lessthan bg-warning text-dark" data-side="left" data-column="' . $meta_people[$bI]['name'] . '" data-table="bd_erp_peoplemeta" >' . $meta_people[$bI]['name'] . '</div>';
          for ($bI = 0; $bI < count($people); $bI++) echo '<div draggable="true" class="box-fields bg-primary text-white" data-people-field="' . $people[$bI]->Field . '" data-table="bd_erp_peoples">' . $people[$bI]->Field . '</div>';
          for ($bI = 0; $bI < count($files_); $bI++) {
          if($files_[$bI]->Field == 'dir_name')
            echo '<div draggable="true" class="box-fields bg-dark text-white" data-people-field="' . $files_[$bI]->Field . '" data-table="bd_erp_employee_dir_file_relationship">' . $files_[$bI]->Field . '</div>';
          }
          ?>
        </div>
    </div>
</div>