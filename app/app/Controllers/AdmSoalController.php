<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdmModel;
use Config\Database;
use Config\Services;

class AdmSoalController extends BaseController
{
   
    private $db;
    private $admModel;

    public function __construct()
    {
        $this->db = Database::connect();

        $this->admModel = new AdmModel();
    }


    public function index()
    {
        //
        $a['sess_level'] = session('userData.admin_level');
        $a['sess_user'] =  session('userData.admin_user');
        $a['sess_konid'] = session('userData.admin_konid');
        $a['huruf_opsi'] = array("a", "b", "c", "d", "e");
        $a['jml_opsi'] = JML_OPSI;

        $a['p']    = "m_soal";

        return view('aaa', $a);
    }

    public function getListSoal()
    {
        $a['sess_level'] = session('userData.admin_level');
        $a['sess_user'] = session('userData.admin_user');
        $a['sess_konid'] = session('userData.admin_konid');

        $a['huruf_opsi'] = array("a", "b", "c", "d", "e");
        $a['jml_opsi'] = JML_OPSI;

        $start = $this->request->getVar('start');
        $length = $this->request->getVar('length');
        $draw = $this->request->getVar('draw');
        $search = $this->request->getVar('search');
        $order = $this->request->getVar('order');
        $urutan = $order[0]['dir'];
        $coloumn = $order[0]['column'];
        $wh = '';

       if ($coloumn == 2) {
            # code...
            $coloumns = 'a.soal';
        }
        elseif ($coloumn == 3) {
            # code...
            $coloumns = 'b.nama';
        }else{
            $coloumns = 'a.id';
        }

        if ($a['sess_level'] == "guru") {
            $wh = "a.id_guru = '" . $a['sess_konid'] . "' AND ";
        } else if ($a['sess_level'] == "admin") {
            $wh = "";
        }


        $d_total_row = $this->db->query("SELECT a.*
												FROM m_soal a
												INNER JOIN m_guru b ON a.id_guru = b.id
												INNER JOIN m_mapel c ON a.id_mapel = c.id
		                                        WHERE " . $wh . " (a.soal LIKE '%" . $search['value'] . "%' 
												OR b.nama LIKE '%" . $search['value'] . "%' 
												OR c.nama LIKE '%" . $search['value'] . "%')")->getNumRows();

        $q_datanya = $this->db->query("SELECT a.*, b.nama nmguru, c.nama nmmapel
												FROM m_soal a
												INNER JOIN m_guru b ON a.id_guru = b.id
												INNER JOIN m_mapel c ON a.id_mapel = c.id
		                                        WHERE " . $wh . " (a.soal LIKE '%" . $search['value'] . "%' 
												OR b.nama LIKE '%" . $search['value'] . "%' 
												OR c.nama LIKE '%" . $search['value'] . "%')
		                                        ORDER BY " . $coloumns . " " . $urutan . " LIMIT " . $start . ", " . $length . "")->getResultArray();
        //echo $this->db->last_query();

        $data = array();
        $no = ($start + 1);

        foreach ($q_datanya as $d) {
            $data_ok = array();
            $data_ok[0] = $no++;
            $data_ok[1] = $d['id'];
            $data_ok[2] = substr($d['soal'], 0, 300);
            $data_ok[3] = $d['nmmapel'] . '<br>' . $d['nmguru'];
            $data_ok[4] = "Jml dipakai : " . ($d['jml_benar'] + $d['jml_salah']) . "<br>Benar: " . $d['jml_benar'] . ", Salah: " . $d['jml_salah'];
            $data_ok[5] = '<div class="btn-group">
	                          <a href="' . base_url() . '/adm/m_soal/edit/' . $d['id'] . '" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-pencil" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Edit</a>
	                          <a href="' . base_url() . '/adm/m_soal/hapus/' . $d['id'] . '" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Hapus</a>
	                         ';

            $data[] = $data_ok;
        }

        $json_data = array(
            "draw" => $draw,
            "iTotalRecords" => $d_total_row,
            "iTotalDisplayRecords" => $d_total_row,
            "data" => $data
        );
        return j($json_data);
    }

    public function editSoal($uri4)
    {
        $a['sess_level'] = session('userData.admin_level');
        $a['sess_user'] = session('userData.admin_user');
        $a['sess_konid'] = session('userData.admin_konid');

        if ($a['sess_level'] == "guru") {
            $a['p_guru'] = obj_to_array($this->db->query("SELECT * FROM m_guru WHERE id = '" . $a['sess_konid'] . "'")->getResult(), "id,nama");
            $a['p_mapel'] = obj_to_array($this->db->query("SELECT 
											b.id, b.nama
											FROM tr_guru_mapel a
											INNER JOIN m_mapel b ON a.id_mapel = b.id
											WHERE a.id_guru = '" . $a['sess_konid'] . "'")->getResult(), "id,nama");
        } else {
            $a['p_guru'] = obj_to_array($this->db->query("SELECT * FROM m_guru")->getResult(), "id,nama");
            $a['p_mapel'] = obj_to_array($this->db->query("SELECT 
											b.id, b.nama
											FROM tr_guru_mapel a
											INNER JOIN m_mapel b ON a.id_mapel = b.id")->getResult(), "id,nama");
        }

        $a['huruf_opsi'] = array("a", "b", "c", "d", "e");
        $a['jml_opsi'] = JML_OPSI;
        $a['opsij'] = array("" => "Jawaban", "A" => "A", "B" => "B", "C" => "C", "D" => "D", "E" => "E");

        $id_guru = session('userData.admin_level') == "guru" ? "WHERE a.id_guru = '" . $a['sess_konid'] . "'" : "";

        obj_to_array($this->db->query("SELECT b.id, b.nama FROM tr_guru_mapel a INNER JOIN m_mapel b ON a.id_mapel = b.id $id_guru")->getResult(), "id,nama");

        if ($uri4 == 0) {
            $a['d'] = array("mode" => "add", "id" => "0", "id_guru" => $id_guru, "id_mapel" => "", "bobot" => "1", "file" => "", "soal" => "", "opsi_a" => "#####", "opsi_b" => "#####", "opsi_c" => "#####", "opsi_d" => "#####", "opsi_e" => "#####", "jawaban" => "", "tgl_input" => "");
        } else {
            $a['d'] = $this->db->query("SELECT m_soal.*, 'edit' AS mode FROM m_soal WHERE id = '$uri4'")->getRowArray();
        }

        $data = array();

        for ($e = 0; $e < $a['jml_opsi']; $e++) {
            $iidata = array();
            $idx = "opsi_" . $a['huruf_opsi'][$e];
            $idx2 = $a['huruf_opsi'][$e];

            $pc_opsi_edit = explode("#####", $a['d'][$idx]);
            $iidata['opsi'] = $pc_opsi_edit[1];
            $iidata['gambar'] = $pc_opsi_edit[0];
            $data[$idx2] = $iidata;
        }


        $a['data_pc'] = $data;
        $a['p'] = "f_soal";
        return view('aaa', $a);
    }

    public function simpanSoal()
    {
        $a['sess_level'] = session('userData.admin_level');
        $a['sess_user'] = session('userData.admin_user');
        $a['sess_konid'] = session('userData.admin_konid');

        $a['huruf_opsi'] = array("a", "b", "c", "d", "e");
        $a['jml_opsi'] = JML_OPSI;

        $p = $this->request->getVar();
        $pembuat_soal = ($a['sess_level'] == "admin") ? $p['id_guru'] : $a['sess_konid'];
        $pembuat_soal_u = ($a['sess_level'] == "admin") ? ", id_guru = '" . $p['id_guru'] . "'" : "";
        //etok2nya config
        $folder_gb_soal = "./upload/gambar_soal/";
        $folder_gb_opsi = "./upload/gambar_opsi/";

        $buat_folder_gb_soal = !is_dir($folder_gb_soal) ? @mkdir("./upload/gambar_soal/") : false;
        $buat_folder_gb_opsi = !is_dir($folder_gb_opsi) ? @mkdir("./upload/gambar_opsi/") : false;

        $allowed_type     = array(
            "image/jpeg", "image/png", "image/gif",
            "audio/mpeg", "audio/mpg", "audio/mpeg3", "audio/mp3", "audio/x-wav", "audio/wave", "audio/wav",
            "video/mp4", "application/octet-stream"
        );

        $gagal         = array();
        $nama_file     = array();
        $tipe_file     = array();

        //get mode
        $__mode = $p['mode'];
        $__id_soal = 0;
        //ambil data post sementara
        $pdata = array(
            "id_guru" => $p['id_guru'],
            "id_mapel" => $p['id_mapel'],
            "bobot" => $p['bobot'],
            "soal" => $p['soal'],
            "jawaban" => $p['jawaban'],
        );

        if ($__mode == "edit") {
            $this->admModel->updateSoal($pdata, $p['id']);
            // $this->db->where("id", $p['id']);
            // $this->db->update("m_soal", $pdata);
            $__id_soal = $p['id'];
        } else {
            $this->admModel->insertSoal($pdata);
            // $this->db->insert("m_soal", $pdata);
            $get_id_akhir = $this->db->query("SELECT MAX(id) maks FROM m_soal LIMIT 1")->getRowArray();
            $__id_soal = $get_id_akhir['maks'];
        }

        //mulai dari sini id soal diambil dari variabel $__id

        //lakukan perulangan sejumlah file upload yang terdeteksi
        foreach ($_FILES as $k => $v) {
            //var file upload
            //$k = nama field di form
            $file_name         = $_FILES[$k]['name'];
            $file_type        = $_FILES[$k]['type'];
            $file_tmp        = $_FILES[$k]['tmp_name'];
            $file_error        = $_FILES[$k]['error'];
            $file_size        = $_FILES[$k]['size'];
            //kode ref file upload jika error
            $kode_file_error = array("File berhasil diupload", "Ukuran file terlalu besar", "Ukuran file terlalu besar", "File upload error", "Tidak ada file yang diupload", "File upload error");

            //jika file error = 0 / tidak ada, tipe file ada di file yang diperbolehkan, dan nama file != kosong
            //echo $file_error."<br>".$file_type;
            //exit;
            //echo var_dump($file_error == 0 || in_array($file_type, $allowed_type) || $file_name != "");
            //exit;
            if ($file_error != 0) {
                $gagal[$k] = $kode_file_error[$file_error];
                $nama_file[$k]    = "";
                $tipe_file[$k]    = "";
            } else if (!in_array($file_type, $allowed_type)) {
                $gagal[$k] = "Tipe file ini tidak diperbolehkan..";
                $nama_file[$k]    = "";
                $tipe_file[$k]    = "";
            } else if ($file_name == "") {
                $gagal[$k] = "Tidak ada file yang diupload";
                $nama_file[$k]    = "";
                $tipe_file[$k]    = "";
            } else {
                $ekstensi = explode(".", $file_name);

                $file_name = $k . "_" . $__id_soal . "." . $ekstensi[1];

                if ($k == "gambar_soal") {
                    @move_uploaded_file($file_tmp, $folder_gb_soal . $file_name);
                } else {
                    @move_uploaded_file($file_tmp, $folder_gb_opsi . $file_name);
                }

                $gagal[$k]         = $kode_file_error[$file_error]; //kode kegagalan upload file
                $nama_file[$k]    = $file_name; //ambil nama file
                $tipe_file[$k]    = $file_type; //ambil tipe file
            }
        }


        //ambil data awal
        $get_opsi_awal = $this->db->query("SELECT opsi_a, opsi_b, opsi_c, opsi_d, opsi_e FROM m_soal WHERE id = '" . $__id_soal . "'")->getRowArray();

        $data_simpan = array();

        if (!empty($nama_file['gambar_soal'])) {
            $data_simpan = array(
                "file" => $nama_file['gambar_soal'],
                "tipe_file" => $tipe_file['gambar_soal'],
            );
        }

        for ($t = 0; $t < $a['jml_opsi']; $t++) {
            $idx     = "opsi_" . $a['huruf_opsi'][$t];
            $idx2     = "gj" . $a['huruf_opsi'][$t];


            //jika file kosong
            $pc_opsi_awal = explode("#####", $get_opsi_awal[$idx]);
            $nama_file_opsi = empty($nama_file[$idx2]) ? $pc_opsi_awal[0] : $nama_file[$idx2];

            $data_simpan[$idx] = $nama_file_opsi . "#####" . $p[$idx];
        }

        // $this->db->where("id", $__id_soal);
        // $this->db->update("m_soal", $data_simpan);
        $this->admModel->updateSoals($data_simpan, $__id_soal);

        $teks_gagal = "";
        foreach ($gagal as $k => $v) {
            $arr_nama_file_upload = array("gambar_soal" => "File Soal ", "gja" => "File opsi A ", "gjb" => "File opsi B ", "gjc" => "File opsi C ", "gjd" => "File opsi D ", "gje" => "File opsi E ");
            $teks_gagal .= $arr_nama_file_upload[$k] . ': ' . $v . '<br>';
        }
        session()->getFlashdata('k', '<div class="alert alert-info">' . $teks_gagal . '</div>');

        return redirect()->to('adm/m_soal/pilih_mapel/' . $p['id_mapel']);
    }

    public function pilihMapel($uri4)
    {

        $a['sess_level'] = session('userData.admin_level');
        $a['sess_user'] = session('userData.admin_user');
        $a['sess_konid'] = session('userData.admin_konid');

        if ($a['sess_level'] == "guru") {
            $a['data'] = $this->db->query("SELECT m_soal.*, m_guru.nama AS nama_guru FROM m_soal INNER JOIN m_guru ON m_soal.id_guru = m_guru.id WHERE m_soal.id_guru = '" . $a['sess_konid'] . "' AND m_soal.id_mapel = '$uri4' ORDER BY id DESC")->getResult();
        } else {
            $a['data'] = $this->db->query("SELECT m_soal.*, m_guru.nama AS nama_guru FROM m_soal INNER JOIN m_guru ON m_soal.id_guru = m_guru.id WHERE m_soal.id_mapel = '$uri4' ORDER BY id DESC")->getResult();
        }
        //echo $this->db->last_query();
        $a['p']    = "m_soal";
        return view('aaa', $a);
    }

    public function importSoal()
    {
        $a['sess_level'] = session('userData.admin_level');
        $a['sess_user'] = session('userData.admin_user');
        $a['sess_konid'] = session('userData.admin_konid');
        $a['p_guru'] = obj_to_array($this->db->query("SELECT * FROM m_guru WHERE id = '" . $a['sess_konid'] . "'")->getResult(), "id,nama");
        $a['p_mapel'] = obj_to_array($this->db->query("SELECT 
											b.id, b.nama
											FROM tr_guru_mapel a
											INNER JOIN m_mapel b ON a.id_mapel = b.id
											WHERE a.id_guru = '" . $a['sess_konid'] . "'")->getResult(), "id,nama");
        $a['p']    = "f_soal_import";

        return view('aaa', $a);
    }
}
