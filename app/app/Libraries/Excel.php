<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 *  ======================================= 
 *  Author     : Muhammad Surya Ikhsanudin 
 *  License    : Protected 
 *  Email      : mutofiyah@gmail.com 
 *   
 *  Dilarang merubah, mengganti dan mendistribusikan 
 *  ulang tanpa sepengetahuan Author 
 *  ======================================= 
 */ 
// require_once APPPATH."third_party/PHP_Excel/PHPExcel".EXT; 
// require_once( APPPATH . 'third_party/PHP_Excel/Classes/PHPExcel.php');
require_once( APPPATH . 'ThirdParty/PHP_Excel/PHPExcel.php');
  
class Excel extends PHPExcel { 
    public function __construct() { 
        parent::__construct(); 
    } 
}