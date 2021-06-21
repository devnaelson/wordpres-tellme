<?php 
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    if(isset($_POST['reqKey'])){

        print_r($_FILES['fl_exc']);
        $encry = $_POST['encrypt'];

        $decoded = JWT::decode($encry, "AdicioneSenha", array('HS256'));
        print_r($decoded);
        
        //move_uploaded_file($files['upload_files']['tmp_name'], $folder_extension . basename($fileName)) == true)
    }
}