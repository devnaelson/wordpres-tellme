<?php
require_once plugin_dir_path(dirname(__FILE__)) . '/vendor/autoload.php';
require_once 'Libaries.php';
use Firebase\JWT\JWT;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    global $wpdb;
    if (isset($_POST['req_key']) and $_POST['req_key'] == 'set_exec') {

        $extension = substr($_FILES['fl_exc']['name'], strripos($_FILES['fl_exc']['name'], "."));
        $struct['exec'] = array();
        $alf = array(
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 
        'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 
        'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD',
        'AE', 'AG','AF','AG','AH','AI','AJ','AK','AL','AM',
        'AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW'
        );
        $encry      = $_POST['encrypt'];
        $decoded    = JWT::decode($encry, "AdicioneSenha", array('HS256'));
        $f_name     = md5(basename($_FILES['fl_exc']['name'])) . $extension;
        $destiny    = $decoded->ABSPATH.$f_name;

        move_uploaded_file($_FILES['fl_exc']['tmp_name'],$destiny);

        $excel      = new Libaries();
        $preadsheet = $excel->activePhpSpreadsheet(true, "Xlsx", $destiny);
        $count      = 0;
        while ($count < count($alf)) {
            if (isset($preadsheet[1][$alf[$count]])) {
                $struct['exec'][$count]['letter'] = $alf[$count];
                $struct['exec'][$count]['key']    = $preadsheet[1][$alf[$count]];
                $struct['exec'][$count]['offset'] = $count;
            }$count++;
        }
        $struct['file_name'] = $f_name;
        echo json_encode($struct);
    }

    //continue
    if (isset($_POST['req_key']) and $_POST['req_key'] == 'dbbuild_exec') {

        $encry     = addslashes($_POST['encrypt']);
        $decoded   = JWT::decode($encry, "AdicioneSenha", array('HS256'));

        $data      = json_decode(stripslashes(html_entity_decode($_POST['data_exec'])));
        $file_name = $data[0]->file;

        $destinyRecovery      = $decoded->ABSPATH . $file_name;

        if (file_exists($destinyRecovery) == false) {
            echo json_encode(array('sucessfull'=> false, 'error'=> true, 'msg'=> 'Arquivo não existe!!'));
        }

        else {

            $excel           = new Libaries();
            $preadsheet      = $excel->activePhpSpreadsheet(true, "Xlsx", $destinyRecovery);
            $countSpread     = count($preadsheet);

            $metaPeopleTable = array();
            $peopleTable     = array();
            $fileTable       = array();
            $c1P             = 0;
            $c1F             = 0;
            $c1MP            = 0;

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

                if (isset($data[0]->spread[$k]->value) and isset($data[0]->spread[$k]->table)) {
                    $table = $data[0]->spread[$k]->table;
                    if ($table == 'bd_erp_employee_dir_file_relationship') {
                        $fileTable[$c1F] = $data[0]->spread[$k];
                        $c1F++;
                    }
                }
            }

            $mSize   = count($metaPeopleTable);
            $psize   = count($peopleTable);
            $x       = 0;
            $lead_id = 0;
            if ($psize > 0) {
                for ($i = 0;$i < count($preadsheet) + 1;$i++) {

                    if ($i > 1) {

                        $c1P        = 0;
                        $c1MP       = 0;
                        $c1F        = 0;
                        $peopleID   = null;

                        do {

                                GO_PEOPLE:
                                $table      = $peopleTable[$c1P]->table;
                                $valueSheet = $preadsheet[$i][$peopleTable[$c1P]->letter];
                                if (isset($valueSheet)) {

                                    if ($peopleID == null) {
                                        // data criado
                                        // life stage and contact owner is lack here inthis insert
                                        $wpdb->insert($table, array(
                                            $peopleTable[$c1P]->value=> $valueSheet
                                        ));

                                        $peopleID = $wpdb->insert_id;
                                        $wpdb->insert("bd_erp_people_type_relations", array(
                                            'people_id' => $peopleID, 
                                            'people_types_id' => 1));

                                    }else {
                                        $wpdb->update($table, array(
                                             $peopleTable[$c1P]->value => $valueSheet),array(
                                                 'id' => $peopleID));
                                    }

                                }else {
                                    echo json_encode(array('sucessfull' => false, 'error' => true, 'msg' => 'Fatal erro vazio!!'));
                                }

                                $c1P++;
                                if (isset($peopleTable[$c1P])) 
                                    goto GO_PEOPLE;

                                if (count($fileTable) > 0) {
                                    GO_FILE:

                                        $table      = $fileTable[$c1F]->table;
                                        $valueSheet = $preadsheet[$i][$fileTable[$c1F]->letter];

                                        if (isset($valueSheet)) {

                                            if (!empty($peopleID)) {

                                                $wpdb->insert('bd_posts', array('post_author'        => 1, 'post_content'        => 'PDF', 'post_title'        => $valueSheet, 'post_excerpt'        => '', 'to_ping'        => '', 'pinged'        => '', 'post_content_filtered'        => '',));
                                                $postID = $wpdb->insert_id;
                                                $wpdb->insert('bd_postmeta', array('post_id' => $postID, 'meta_key' => '_wp_attached_file', 'meta_value' => $valueSheet));
                                                $wpdb->insert($table, array('eid' => $peopleID, 'dir_id' => '_wp_attached_file', 'dir_name' => $valueSheet, 'attachment_id'=> $postID));

                                            }else {
                                                echo json_encode(array('sucessfull' => false, 'error' => true, 'msg' => 'Fatal erro vazio 1!!'));
                                            }

                                        }// else vazio

                                        $c1F++;
                                        if (isset($fileTable[$c1F])) 
                                            goto GO_FILE;

                                    }

                                    if (count($metaPeopleTable) > 0) {

                                            GO_MTPEOPLE:
                                            $table      = $metaPeopleTable[$c1MP]->table;
                                            $valueSheet = $preadsheet[$i][$metaPeopleTable[$c1MP]->letter];
                                            if (isset($valueSheet)) {

                                                if (!empty($peopleID)) {
                                                    $wpdb->insert($table,array(
                                                        'erp_people_id' => $peopleID,
                                                        'meta_key' => $metaPeopleTable[$c1MP]->value,
                                                        'meta_value' => $valueSheet));
                                                }else {
                                                    echo json_encode(array('sucessfull' => false, 'error' => true, 'msg' => 'people id vazio!!'));
                                                }

                                            }// else vazio

                                            $c1MP++;
                                            if (isset($metaPeopleTable[$c1MP])) goto GO_MTPEOPLE;

                                        }else {
                                            echo json_encode(array('sucessfull' => false, 'error' => true, 'msg' => 'Fatal erro, nenhum vinculado!!'));
                                        }

                                        $x++;

                                    } while ($x >= 0 and $c1P < $psize - 1 and $c1MP < $mSize - 1 and $peopleID != null);
                                }
                                if (count($preadsheet) == $i) {
                                    unlink($destinyRecovery);
                                    echo json_encode(array('sucessfull' => true, 'error' => false, 'msg' => 'Pronto!'));
                                }
                            }

                        } else {
                            echo json_encode(array('sucessfull' => false, 'error' => true, 'msg' => 'Não foi assosiado!!'));
                        }
                    } //check file exist
                    
                } // continue
                
            } 
