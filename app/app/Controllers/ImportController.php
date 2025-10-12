<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Config\Database;
use Excel;
use PHPExcel_IOFactory;
use PHPExcel_Settings;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class ImportController extends BaseController
{
    private $kolom_xl;
    private $db;

    public function __construct() {
        // require_once( APPPATH . 'ThirdParty/PHPExcel/PHPExcel.php');
        $this->db = Database::connect();
        $this->kolom_xl = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
    }
    public function index()
    {
        //
    }

    public function siswa() {
        $idx_baris_mulai = 3;
        $idx_baris_selesai = 100;

        $target_file = './upload/temp/';
        $buat_folder_temp = !is_dir($target_file) ? @mkdir("./upload/temp/") : false;
        
        move_uploaded_file($_FILES["import_excel"]["tmp_name"], $target_file.$_FILES['import_excel']['name']);

        $file   = explode('.',$_FILES['import_excel']['name']);
        $length = count($file);

        if($file[$length -1] == 'xlsx' || $file[$length -1] == 'xls') {

            $tmp    = '/upload/temp/'.$_FILES['import_excel']['name'];
            //Baca dari tmp folder jadi file ga perlu jadi sampah di server :-p
            
           //Load library excelnya
            $read   = PHPExcel_IOFactory::createReaderForFile($tmp);
            $read->setReadDataOnly(true);
            $excel  = $read->load($tmp);
    
            $_sheet = $excel->setActiveSheetIndexByName('data');
            
            $data = array();
            for ($j = $idx_baris_mulai; $j <= $idx_baris_selesai; $j++) {
                $nim = $_sheet->getCell("A".$j)->getCalculatedValue();
                $nama = $_sheet->getCell("B".$j)->getCalculatedValue();
                $kelas = $_sheet->getCell("C".$j)->getCalculatedValue();

                if ($nim != "" || $nama != "") {
                    $data[] = "('".$nim."', '".$nama."', '".$kelas."')"; 
                }
            }

            $strq = "INSERT INTO m_siswa (nim, nama, jurusan) VALUES ";
           
            $strq .= implode(",", $data).";";
            
            $this->db->query($strq);
        } else {
            exit('Bukan File Excel...');//pesan error tipe file tidak tepat
        }
        return redirect('adm/m_siswa');
	}

    public function soal() {
        $p = $this->request->getVar();

        $idx_baris_mulai = 3;
        $idx_baris_selesai = 106;

        // $target_file = './upload/temp/';
        // $buat_folder_temp = !is_dir($target_file) ? @mkdir("./upload/temp/") : false;
        
        // move_uploaded_file($_FILES["import_excel"]["tmp_name"], $target_file.$_FILES['import_excel']['name']);

        // $file   = explode('.',$_FILES['import_excel']['name']);
        // $length = count($file);

        $path 			= './upload/temp/';
		$json 			= [];
		$file_name 		= $this->request->getFile('import_excel');
		$file_name 		= $this->uploadFile($path, $file_name);
		$arr_file 		= explode('.', $file_name);
		$extension 		= end($arr_file);
		if('csv' == $extension) {
			$reader 	= new \PhpOffice\PhpSpreadsheet\Reader\Csv();
		} elseif('xls' == $extension){
            $reader 	= new Xls();
        }
        else {
			$reader 	= new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		}

        if($extension == 'xlsx' || $extension == 'xls') {

            $tmp    = './upload/temp/'.$_FILES['import_excel']['name'];
            //Baca dari tmp folder jadi file ga perlu jadi sampah di server :-p
            
            //Load library excelnya
            // PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
            // $read   = PHPExcel_IOFactory::load($tmp);
            // $read->setReadDataOnly(true);
            // $excel  = $read->load($tmp);

            $reader = new Xlsx();
            $spreadsheet 	= $reader->load($tmp);

            $_sheet = $spreadsheet->getActiveSheet('data');
            // var_dump($_sheet);
            // die;
            $data = array();
            for ($j = $idx_baris_mulai; $j <= $idx_baris_selesai; $j++) {
                $bobot = $_sheet->getCell("A".$j)->getCalculatedValue();
                $soal = $_sheet->getCell("B".$j)->getCalculatedValue();
                $opsi_a = $_sheet->getCell("C".$j)->getCalculatedValue();
                $opsi_b = $_sheet->getCell("D".$j)->getCalculatedValue();
                $opsi_c = $_sheet->getCell("E".$j)->getCalculatedValue();
                $opsi_d = $_sheet->getCell("F".$j)->getCalculatedValue();
                $opsi_e = $_sheet->getCell("G".$j)->getCalculatedValue();
                $kunci = $_sheet->getCell("H".$j)->getCalculatedValue();

                if ($soal != "") {
                    $data[] = "('".$p['id_guru']."', '".$p['id_mapel']."', '".$bobot."', '".$soal."', '#####".$opsi_a."', '#####".$opsi_b."', '#####".$opsi_c."', '#####".$opsi_d."', '#####".$opsi_e."', '".$kunci."', NOW(), 0, 0)"; 
                }
            }

            $strq = "INSERT INTO m_soal (id_guru, id_mapel, bobot, soal, opsi_a, opsi_b, opsi_c, opsi_d, opsi_e, jawaban, tgl_input, jml_benar, jml_salah) VALUES ";
           
            $strq .= implode(",", $data).";";
            //echo $strq;
            //exit;

            $this->db->query($strq);
        } else {
            exit('Bukan File Excel...');//pesan error tipe file tidak tepat
        }
        return redirect()->to('adm/m_soal');
    }

    public function uploadFile($path, $image)
	{
		if (!is_dir($path))
			mkdir($path, 0777, TRUE);
		if ($image->isValid() && !$image->hasMoved()) {
			$newName = $image->getRandomName();
			$image->move('./' . $path, $newName);
			return $path . $image->getName();
		}
		return "";
	}
}
