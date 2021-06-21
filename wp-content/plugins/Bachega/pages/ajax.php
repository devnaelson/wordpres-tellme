<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['reqKey'])){
        print_r($_FILES['fl_exc']);
        //move_uploaded_file($files['upload_files']['tmp_name'], $folder_extension . basename($fileName)) == true)
    }
}