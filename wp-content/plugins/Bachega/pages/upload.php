<div class="row">

    <div class="col-12 bg-danger">
        s
    </div>
    <div class="col-12 bg-warning">
        sd
    </div>
    <div class="col-12">
        <?php
        $resultFiels = get_option('myfields');
        for ($bI = 0; $bI < count($resultFiels); $bI++) {
            echo '<div draggable="true" class="box-fields">'.$resultFiels[$bI]['label'].'</div>';
        }
        ?>
    </div>

</div>