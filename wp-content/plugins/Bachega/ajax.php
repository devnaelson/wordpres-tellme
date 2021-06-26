<?php
require 'vendor/autoload.php';
require 'Libaries.php';
use Firebase\JWT\JWT;
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{

    if (isset($_POST['reqKey']) and $_POST['reqKey'] == 'getExec')
    {
        $extension = substr($_FILES['fl_exc']['name'], strripos($_FILES['fl_exc']['name'], "."));
        $struct['exec'] = array();
        $alf = array(
            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
            'G',
            'H',
            'I',
            'J',
            'K',
            'L',
            'M',
            'N',
            'O',
            'P',
            'Q',
            'R',
            'S',
            'T',
            'U',
            'V',
            'W',
            'X',
            'Y',
            'Z',
            'AA',
            'AB',
            'AC',
            'AD',
            'AE',
            'AG'
        );
        $encry = $_POST['encrypt'];
        $decoded = JWT::decode($encry, "AdicioneSenha", array(
            'HS256'
        ));
        $f_name = md5(basename($_FILES['fl_exc']['name'])) . $extension;
        $dest = $decoded->ABSPATH . "/assets/upload/" . $f_name;
        move_uploaded_file($_FILES['fl_exc']['tmp_name'], $dest);
        $excel = new Libaries();
        $preadsheet = $excel->activePhpSpreadsheet(true, "Xlsx", $dest);
        $count = 0;
        while ($count < count($alf))
        {
            if (isset($preadsheet[1][$alf[$count]]))
            {
                $struct['exec'][$count]['letter'] = $alf[$count];
                $struct['exec'][$count]['key'] = $preadsheet[1][$alf[$count]];
                $struct['exec'][$count]['offset'] = $count;
            }
            $count++;
        }
        $struct['file_name'] = $f_name;
        echo json_encode($struct);
    }

    //continue
    if (isset($_POST['reqKey']) and $_POST['reqKey'] == 'constructExec')
    {
        $encry = addslashes($_POST['encrypt']);
        $decoded = JWT::decode($encry, "AdicioneSenha", array(
            'HS256'
        ));
        $data = json_decode($_POST['dataStructExec']);
        $ile_name = $data[0]->file;
        $dest = $decoded->ABSPATH . "/assets/upload/" . $ile_name;
        $excel = new Libaries();
        $preadsheet = $excel->activePhpSpreadsheet(true, "Xlsx", $dest);
        $countSpread = count($preadsheet);
        $metaPeopleTable = array();
        $peopleTable = array();
        $c1P = 0;
        $c1MP = 0;

        for ($k = 0;$k < count($data[0]->spread);$k++)
        {
            if (isset($data[0]->spread[$k]->value) and isset($data[0]->spread[$k]->table))
            {
                $table = $data[0]->spread[$k]->table;
                if ($table == 'bd_erp_peoples')
                {
                    $peopleTable[$c1P] = $data[0]->spread[$k];
                    $c1P++;
                }
            }

            if (isset($data[0]->spread[$k]->value) and isset($data[0]->spread[$k]->table))
            {
                $table = $data[0]->spread[$k]->table;
                if ($table == 'bd_erp_peoplemeta')
                {
                    $metaPeopleTable[$c1MP] = $data[0]->spread[$k];
                    $c1MP++;
                }
            }
        }

        //continue
        

    }
}

