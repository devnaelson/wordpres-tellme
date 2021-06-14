<?php 
if ( !defined( 'ABSPATH' ) ) exit;
require RDIR_BACHEGA.'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
class Libaries {

    protected $acitve = false;
    protected $setting = array();

    function __construct() {}

    public function activePhpSpreadsheet($active,$type) { 
        $this->acitve = $active;
        $this->getActive();
        $this->LoadPhpSpreadsheet($type);
    }

    public function getActive() { return $this->acitve; }

    /**
     * @package types extension
     * @version .0.1.0
     * Csv
     * Xlsx
     * Xls
     */
    function LoadPhpSpreadsheet($type) {
        if($this->getActive() == true) {

            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($type);
            $reader->setReadDataOnly(true);

            // $spreadsheet = $reader->load(dirname(__FILE__) . "/" . $target_file);
            // $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        }
    }

    // $callObject = new Libaries();
    // $callObject->activePhpSpreadsheet(true,"Xlsx");

}