<div class="p-3">
<div class="input-group">
  <input type="file" class="form-control" id="inputGroupFile04" aria-describedby="ssup" aria-label="Upload">
  <button class="btn btn-primary" type="button" id="ssup">Button</button>
  <input type="hidden" name="target_upload" value="true">
</div>
</div>
<div class="row bgbox text-center p-5 w-100">
    <div class="col-auto bg-danger w-100">s</div>
    <div class="col-auto bg-warning w-100">sd</div>
    <div class="col-auto w-100">
        <?php
        $resultFiels = get_option('myfields');
        for ($bI = 0; $bI < count($resultFiels); $bI++) {
            echo '<div draggable="true" class="box-fields">' . $resultFiels[$bI]['label'] . '</div>';
        }
        ?>
    </div>
</div>