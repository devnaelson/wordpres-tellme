<?php
use Firebase\JWT\JWT;
require_once plugin_dir_path( dirname( __FILE__ ) ).'/vendor/autoload.php';
require_once 'Libaries.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['reqKey']) and $_POST['reqKey'] == 'getExec') {
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
        while ($count < count($alf)) {
            if (isset($preadsheet[1][$alf[$count]])) {
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
    if (isset($_POST['reqKey']) and $_POST['reqKey'] == 'constructExec') {
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

        for ($k = 0;$k < count($data[0]->spread);$k++) {
            if (isset($data[0]->spread[$k]->value) and isset($data[0]->spread[$k]->table)) {
                $table = $data[0]->spread[$k]->table;
                if ($table == 'bd_erp_peoples') {
                    $peopleTable[$c1P] = $data[0]->spread[$k];
                    $c1P++;
                }
            }

            if (isset($data[0]->spread[$k]->value) and isset($data[0]->spread[$k]->table)) {
                $table = $data[0]->spread[$k]->table;
                if ($table == 'bd_erp_peoplemeta') {
                    $metaPeopleTable[$c1MP] = $data[0]->spread[$k];
                    $c1MP++;
                }
            }
        }

        $mSize = count($metaPeopleTable);
        $psize = count($peopleTable);
        $x = 0;
        $lead_id = 0;
        if ($psize > 0) {
            for ($i = 0;$i < count($preadsheet) + 1;$i++) {

                if ($i > 1) {
                    $c1P = 0;
                    $c1MP = 0;
                    $peopleID = null;
                    do {

                        GO_PEOPLE:

                            $table = $peopleTable[$c1P]->table;
                            $valueSheet = $preadsheet[$i][$peopleTable[$c1P]->letter];
                            if (isset($preadsheet[$i][$peopleTable[$c1P]->letter]) ) {
                               
                                if($peopleID == null){
                                    global $wpdb;
                                    print_r($wpdb);
                            
                                    // $table_name = $wpdb->prefix . $table;     
                                    // $wpdb->insert($table_name,array($peopleTable[$c1P]->value => $valueSheet));
                                    // $wpdb->insert_id;
                                } else {

                                }

                            } else {
                                echo "throw error";
                            }

                            $c1P++;
                            if (isset($peopleTable[$c1P])) goto GO_PEOPLE;

                            if (count($metaPeopleTable) > 0) {
                                GO_MTPEOPLE:
                                    
                                    if (isset($preadsheet[$i][$metaPeopleTable[$c1MP]])) {
                                        
                                    } else {
                                        echo "throw error";
                                    }

                                    $c1MP++;
                                    if (isset($metaPeopleTable[$c1MP])) goto GO_MTPEOPLE;
                                }
                                $x++;
                            }
                            while ($x >= 0 and $c1P < $psize - 1 and $c1MP < $mSize - 1 and $peopleID != null);
                        }
                    }
                }
                else {
                    echo "não foi assosiado!";
                }

                //continue
                
            }
        } 
