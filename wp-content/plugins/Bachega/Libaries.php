<?php 
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
class Libaries {

    protected $acitve = false;
    protected $setting = array();
    function __construct() {}
    /**
     * @package types extension
     * @version .0.1.0
     * Csv
     * Xlsx
     * Xls
     */
    public function activePhpSpreadsheet($active,$type,$path) { 
        $this->acitve = $active;
        $this->getActive();
        return $this->LoadPhpSpreadsheet($type,$path);
    }

    public function getActive() { return $this->acitve; }
    function LoadPhpSpreadsheet($type,$path) {
        if($this->getActive() == true) {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($type);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($path);
            return $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        }
    }
}