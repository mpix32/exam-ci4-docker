<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Config\Database;
use Config\Services;
use DateTime;
use stdClass;

class AdmUjianController extends BaseController
{
    private $db;
    private $opsi;

    public function __construct()
    {

        $this->db = Database::connect();
        $this->opsi = array("a", "b", "c", "d", "e");
    }

    public function index()
    {
        //
        $a['sess_level'] = session('userData.admin_level');
        $a['sess_user'] =  session('userData.admin_user');
        $a['sess_konid'] = session('userData.admin_konid');

        $a['pola_tes'] = array("" => "Pengacakan Soal", "acak" => "Soal Diacak", "set" => "Soal Diurutkan");

        $a['p_mapel'] = obj_to_array($this->db->query("SELECT * FROM m_mapel WHERE id IN (SELECT id_mapel FROM tr_guru_mapel WHERE id_guru = '" . $a['sess_konid'] . "')")->getResult(), "id,nama");
        $a['p']    = "m_guru_tes";

        return view('aaa', $a);
    }

    public function getListUjian()
    {
        $a['sess_level'] = session('userData.admin_level');
        $a['sess_user'] =  session('userData.admin_user');
        $a['sess_konid'] = session('userData.admin_konid');

        $start = $this->request->getPost('start');
        $length = $this->request->getPost('length');
        $draw = $this->request->getPost('draw');
        $search = $this->request->getPost('search');

        $d_total_row = $this->db->query("SELECT a.id
		        	FROM tr_guru_tes a
		        	INNER JOIN m_mapel b ON a.id_mapel = b.id 
		        	WHERE a.id_guru = '" . $a['sess_konid'] . "' 
                    AND (a.nama_ujian LIKE '%" . $search['value'] . "%' 
					OR b.nama LIKE '%" . $search['value'] . "%')")->getNumRows();

        //echo $this->db->last_query();

        $q_datanya = $this->db->query("SELECT a.*, b.nama AS mapel
												FROM tr_guru_tes a
									        	INNER JOIN m_mapel b ON a.id_mapel = b.id 
									        	WHERE a.id_guru = '" . $a['sess_konid'] . "'
							                    AND (a.nama_ujian LIKE '%" . $search['value'] . "%'
												OR b.nama LIKE '%" . $search['value'] . "%') 
		                                        ORDER BY a.id DESC LIMIT " . $start . ", " . $length . "")->getResultArray();
        $data = array();
        $no = ($start + 1);

        foreach ($q_datanya as $d) {
            $jenis_soal = $d['jenis'] == "acak" ? "Soal diacak" : "Soal urut";

            $data_ok = array();
            $data_ok[0] = $no++;
            $data_ok[1] = $d['nama_ujian'] . "<br>Token : <b>" . $d['token'] . "</b> &nbsp;&nbsp; <a href='#' onclick='return refresh_token(" . $d['id'] . ")' title='Perbarui Token'><i class='fa fa-refresh'></i></a>";
            $data_ok[2] = $d['mapel'];
            $data_ok[3] = $d['jumlah_soal'];
            $data_ok[4] = tjs($d['tgl_mulai'], "s") . "<br>(" . $d['waktu'] . " menit)";
            $data_ok[5] = $jenis_soal;
            $data_ok[6] = '
		            	<div class="btn-group">
                          <a href="#" onclick="return m_ujian_e(' . $d['id'] . ');" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-pencil" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Edit</a>
                          <a href="#" onclick="return m_ujian_h(' . $d['id'] . ');" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Hapus</a>
                        </div>
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

    public function detUjian($uri4)
    {

        $a['sess_level'] = session('userData.admin_level');
        $a['sess_user'] =  session('userData.admin_user');
        $a['sess_konid'] = session('userData.admin_konid');

        $a['pola_tes'] = array("" => "Pengacakan Soal", "acak" => "Soal Diacak", "set" => "Soal Diurutkan");

        $are = array();

        $are['p_mapel'] = obj_to_array($this->db->query("SELECT * FROM m_mapel WHERE id IN (SELECT id_mapel FROM tr_guru_mapel WHERE id_guru = '" . $a['sess_konid'] . "')")->getResult(), "id,nama");

        $a = $this->db->query("SELECT * FROM tr_guru_tes WHERE id = '$uri4'")->getRow();


        if (!empty($a)) {
            $pc_waktu = explode(" ", $a->tgl_mulai);
            $pc_tgl = explode("-", $pc_waktu[0]);

            $are['id'] = $a->id;
            $are['id_guru'] = $a->id_guru;
            $are['id_mapel'] = $a->id_mapel;
            $are['nama_ujian'] = $a->nama_ujian;
            $are['jumlah_soal'] = $a->jumlah_soal;
            $are['waktu'] = $a->waktu;
            $are['terlambat'] = $a->terlambat;
            $are['jenis'] = $a->jenis;
            $are['detil_jenis'] = $a->detil_jenis;
            $are['tgl_mulai'] = $pc_waktu[0];
            $are['wkt_mulai'] = substr($pc_waktu[1], 0, 5);
            $are['token'] = $a->token;
        } else {
            $are['id'] = "";
            $are['id_guru'] = "";
            $are['id_mapel'] = "";
            $are['nama_ujian'] = "";
            $are['jumlah_soal'] = "";
            $are['waktu'] = "";
            $are['terlambat'] = "";
            $are['jenis'] = "";
            $are['detil_jenis'] = "";
            $are['tgl_mulai'] = "";
            $are['wkt_mulai'] = "";
            $are['token'] = "";
        }

        return $this->response->setJSON($are);
    }

    public function jmlSoal($uri4 = '')
    {
        $a['sess_level'] = session('userData.admin_level');
        $a['sess_user'] =  session('userData.admin_user');
        $a['sess_konid'] = session('userData.admin_konid');

        $ambil_data = $this->db->query("SELECT id FROM m_soal WHERE id_mapel = '$uri4' AND id_guru = '" . $a['sess_konid'] . "'")->getNumRows();
        $ret_arr['jumlah'] = $ambil_data;
        return $this->response->setJSON($ret_arr);
    }

    public function simpanUjian()
    {
        $p = json_decode(file_get_contents('php://input'));

        $a['sess_level'] = session('userData.admin_level');
        $a['sess_user'] =  session('userData.admin_user');
        $a['sess_konid'] = session('userData.admin_konid');

        $ket     = "";

        if ($p->id != 0 && $p->id != "") {
            $this->db->query("UPDATE tr_guru_tes SET id_mapel = '" . bersih($p, "mapel") . "', 
								nama_ujian = '" . bersih($p, "nama_ujian") . "', jumlah_soal = '" . bersih($p, "jumlah_soal") . "', 
								waktu = '" . bersih($p, "waktu") . "', terlambat = '" . bersih($p, "terlambat") . "', 
								tgl_mulai = '" . bersih($p, "tgl_mulai") . " " . bersih($p, "wkt_mulai") . "', jenis = '" . bersih($p, "acak") . "'
								WHERE id = '" . bersih($p, "id") . "'");
            $ket = "edit";
        } else {
            $ket = "tambah";
            $token = strtoupper(random_string('alpha', 5));
           
            $this->db->query("INSERT INTO tr_guru_tes ( id_guru, id_mapel, nama_ujian, jumlah_soal, waktu, jenis, detil_jenis, tgl_mulai, terlambat, token) 
                                VALUES ( '" . $a['sess_konid'] . "', '" . bersih($p, "mapel") . "',
								'" . bersih($p, "nama_ujian") . "', '" . bersih($p, "jumlah_soal") . "', 
                                '" . bersih($p, "waktu") . "', '" . bersih($p, "acak") . "', 
								'', '" . bersih($p, "tgl_mulai") . " " . bersih($p, "wkt_mulai") . "', '" .
                                bersih($p, "terlambat") . "', '$token')");
        }

        $ret_arr['status']     = "ok";
        $ret_arr['caption']    = $ket . " sukses";
        return $this->response->setJSON($ret_arr);
    }

    public function refreshToken($uri4)
    {
        $token = strtoupper(random_string('alpha', 5));

        $this->db->query("UPDATE tr_guru_tes SET token = '$token' WHERE id = '$uri4'");

        $ret_arr['status'] = "ok";
        return $this->response->setJSON($ret_arr);
    }

    public function hapusUjian($uri4)
    {
        $this->db->query("DELETE FROM tr_guru_tes WHERE id = '" . $uri4 . "'");
        $ret_arr['status']     = "ok";
        $ret_arr['caption']    = "hapus sukses";
        return $this->response->setJSON($ret_arr);
    }

    // Hasil Ujian

    public function hasilUjianIndex()
    {
        $a['sess_level'] = session('userData.admin_level');
        $a['sess_user'] =  session('userData.admin_user');
        $a['sess_konid'] = session('userData.admin_konid');

        $a['p']    = "m_guru_tes_hasil";
        return view('aaa', $a);
    }

    public function hasilUjian()
    {
        $a['sess_level'] = session('userData.admin_level');
        $a['sess_user'] =  session('userData.admin_user');
        $a['sess_konid'] = session('userData.admin_konid');

        $start = $this->request->getPost('start');
        $length = $this->request->getPost('length');
        $draw = $this->request->getPost('draw');
        $search = $this->request->getPost('search');
        $order = $this->request->getVar('order');
        $urutan = $order[0]['dir'];
        $coloumn = $order[0]['column'];

        if($coloumn == 1){
            $coloumns = 'a.nama_ujian';
        }elseif ($coloumn == 2) {
            # code...
            $coloumns = 'a.tgl_mulai';
        }
        elseif ($coloumn == 3) {
            # code...
            $coloumns = 'c.nama';
        }elseif ($coloumn == 3) {
            # code...
            $coloumns = 'b.nama';
        }
        else {
            # code...
            $coloumns = 'a.id';
        }


        $wh_1 = $a['sess_level'] == "admin" ? "" : " AND a.id_guru = '" . $a['sess_konid'] . "'";

        $d_total_row = $this->db->query("SELECT a.id FROM tr_guru_tes a
	        	INNER JOIN m_mapel b ON a.id_mapel = b.id 
	        	INNER JOIN m_guru c ON a.id_guru = c.id
	            WHERE (a.nama_ujian LIKE '%" . $search['value'] . "%' OR b.nama LIKE '%" . $search['value'] . "%' OR c.nama LIKE '%" . $search['value'] . "%') " . $wh_1 . "")->getNumRows();
        //echo $this->db->last_query();

        $q_datanya = $this->db->query("SELECT a.*, b.nama AS mapel, c.nama AS nama_guru 
                FROM tr_guru_tes a
	        	INNER JOIN m_mapel b ON a.id_mapel = b.id 
	        	INNER JOIN m_guru c ON a.id_guru = c.id
	            WHERE (a.nama_ujian LIKE '%" . $search['value'] . "%' OR b.nama LIKE '%" . $search['value'] . "%' OR c.nama LIKE '%" . $search['value'] . "%') " . $wh_1 . " 
                        ORDER BY " . $coloumns . " " . $urutan . " LIMIT " . $start . ", " . $length . "")->getResultArray();

        $data = array();
        $no = ($start + 1);


        foreach ($q_datanya as $d) {
            $data_ok = array();
            $data_ok[0] = $no++;
            $data_ok[1] = $d['nama_ujian'];
            $data_ok[2] = $d['tgl_mulai'];
            $data_ok[3] = $d['nama_guru'];
            $data_ok[4] = $d['mapel'];
            $data_ok[5] = $d['jumlah_soal'];
            $data_ok[6] = $d['waktu'] . " menit";
            $data_ok[7] = '<a href="' . base_url() . 'adm/h_ujian/det/' . $d['id'] . '" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-search" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Lihat Hasil</a>
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

    public function detHasilUjian($uri4)
    {
        $a['sess_level'] = session('userData.admin_level');
        $a['sess_user'] =  session('userData.admin_user');
        $a['sess_konid'] = session('userData.admin_konid');

        $a['detil_tes'] = $this->db->query("SELECT m_mapel.nama AS namaMapel, m_guru.nama AS nama_guru, 
												tr_guru_tes.* 
												FROM tr_guru_tes 
												INNER JOIN m_mapel ON tr_guru_tes.id_mapel = m_mapel.id
												INNER JOIN m_guru ON tr_guru_tes.id_guru = m_guru.id
												WHERE tr_guru_tes.id = '$uri4'")->getRow();
        $a['statistik'] = $this->db->query("SELECT MAX(nilai) AS max_, MIN(nilai) AS min_, AVG(nilai) AS avg_ 
											FROM tr_ikut_ujian
											WHERE tr_ikut_ujian.id_tes = '$uri4'")->getRow();

        //$a['hasil'] = $this->db->query("")->result();

        $a['url'] = $uri4;
        $a['p'] = "m_guru_tes_hasil_detil";
        return view('aaa', $a);
    }

    public function dataDetailUjian($uri4)
    {
        // $uri3 = handlingUri(3);
        // $uri4 = handlingUri(4);
        // $uri5 = handlingUri(5);

        $start = $this->request->getPost('start');
        $length = $this->request->getPost('length');
        $draw = $this->request->getPost('draw');
        $search = $this->request->getPost('search');
        $order = $this->request->getVar('order');
        $urutan = $order[0]['dir'];
        $coloumn = $order[0]['column'];

        if($coloumn == 1){
            $coloumns = 'b.nim';
        }elseif ($coloumn == 2) {
            # code...
            $coloumns = 'b.nama';
        }
        else {
            # code...
            $coloumns = 'a.nilai';
        }

        $d_total_row = $this->db->query("
	        	SELECT a.id
				FROM tr_ikut_ujian a
				INNER JOIN m_siswa b ON a.id_user = b.id
				WHERE a.id_tes = '$uri4' 
				AND b.nama LIKE '%" . $search['value'] . "%'")->getNumRows();

        $q_datanya = $this->db->query("
	        	SELECT a.id, b.nim, b.nama, a.nilai, a.jml_benar, a.nilai_bobot, a.list_jawaban,c.jumlah_soal
				FROM tr_ikut_ujian a
				INNER JOIN m_siswa b ON a.id_user = b.id
				INNER JOIN tr_guru_tes c ON a.id_tes = c.id
				WHERE a.id_tes = '$uri4' 
				AND b.nama LIKE '%" . $search['value'] . "%' ORDER BY 
                " . $coloumns . "  " . $urutan . " LIMIT " . $start . ", " . $length . "")->getResultArray();

        $data = array();
        $no = ($start + 1);


        foreach ($q_datanya as $d) {
            $data_ok = array();
            $data_ok[0] = $no++;
            $data_ok[1] = $d['nim'];
            $data_ok[2] = $d['nama'];
            $data_ok[3] = $d['jml_benar'];
            $data_ok[4] = $d['jumlah_soal'];
            $data_ok[5] = $d['nilai'];
            // $data_ok[5] = $d['list_jawaban'];
            $data_ok[6] = '<a href="' . base_url() . 'adm/h_ujian/batalkan_ujian/' . $d['id'] . '/' . $uri4 . '" class="btn btn-danger btn-xs" onclick="return confirm(\'Anda yakin...?\');"><i class="glyphicon glyphicon-remove" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Batalkan Ujian</a>';
            // $data_ok[6] = '<a href="'.base_url().'adm/h_ujian/print_jawaban/'.$d['id'].'/'.$this->uri->segment(4).'" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-print" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Hasil Jawaban</a>';
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

    public function batalUjian($uri5,$uri4){

        $this->db->query("DELETE FROM tr_ikut_ujian WHERE id = '$uri5'");
		return	redirect()->to('adm/h_ujian/det/' . $uri4);
    }

    public function hasilUjianCetak($uri3)
    {

        //var def uri segment
        $a['detil_tes'] = $this->db->query("SELECT m_mapel.nama AS namaMapel, m_guru.nama AS nama_guru, 
												tr_guru_tes.* 
												FROM tr_guru_tes 
												INNER JOIN m_mapel ON tr_guru_tes.id_mapel = m_mapel.id
												INNER JOIN m_guru ON tr_guru_tes.id_guru = m_guru.id
												WHERE tr_guru_tes.id = '$uri3'")->getRow();

        $a['statistik'] = $this->db->query("SELECT MAX(nilai) AS max_, MIN(nilai) AS min_, AVG(nilai) AS avg_ 
										FROM tr_ikut_ujian
										WHERE tr_ikut_ujian.id_tes = '$uri3'")->getRow();
        $a['hasil'] = $this->db->query("SELECT m_siswa.nama, tr_ikut_ujian.nilai, tr_ikut_ujian.jml_benar, tr_ikut_ujian.nilai_bobot,tr_guru_tes.jumlah_soal
										FROM tr_ikut_ujian
										INNER JOIN m_siswa ON tr_ikut_ujian.id_user = m_siswa.id
										INNER JOIN tr_guru_tes ON tr_ikut_ujian.id_tes = tr_guru_tes.id
										WHERE tr_ikut_ujian.id_tes = '$uri3'")->getResult();

        return view("m_guru_tes_hasil_detil_cetak", $a);
    }

    public function hasilUjianPeserta()
    {
        $a['sess_level'] = session('userData.admin_level');
        $a['sess_user'] =  session('userData.admin_user');
        $a['sess_konid'] = session('userData.admin_konid');

        $a['p']    = "m_guru_tes_hasil_peserta";

        return view('aaa', $a);
    }

    public function listUjianPeserta()
    {
        $a['sess_level'] = session('userData.admin_level');
        $a['sess_user'] =  session('userData.admin_user');
        $a['sess_konid'] = session('userData.admin_konid');

        $start = $this->request->getPost('start');
        $length = $this->request->getPost('length');
        $draw = $this->request->getPost('draw');
        $search = $this->request->getPost('search');
        $order = $this->request->getVar('order');
        $urutan = $order[0]['dir'];
        $coloumn = $order[0]['column'];

        if($coloumn == 1){
            $coloumns = 'a.nim';
        }elseif ($coloumn == 2 ) {
            # code...
            $coloumns = 'a.nama';
        }elseif ($coloumn == 3) {
            # code...
            $coloumns = 'c.nama_ujian';
        }elseif ($coloumn == 4) {
            # code...
            $coloumns = 'c.tgl_mulai';
        }
        else {
            # code...
            $coloumns = 'a.id';
        }

        $wh_1 = $a['sess_level'] == "admin" ? "" : " AND c.id_guru = '" . $a['sess_konid'] . "'";

        /*$d_total_row = $this->db->query("SELECT a.*, b.nama  FROM tr_ikut_ujian a
	        	INNER JOIN m_siswa b ON a.id_user = b.id 
	            WHERE (b.nama LIKE '%".$search['value']."%') ".$wh_1."")->num_rows();*/
        //echo $this->db->last_query();

        /*$q_datanya = $this->db->query("SELECT a.*, b.nama  FROM tr_ikut_ujian a
	        	INNER JOIN m_siswa b ON a.id_user = b.id 
				WHERE (b.nama LIKE '%".$search['value']."%' ) ".$wh_1." group by a.id_user ORDER BY a.id DESC  LIMIT ".$start.", ".$length." ")->result_array();
	        */
        $d_total_row = $this->db->query("SELECT * from m_siswa a 
				INNER JOIN tr_ikut_ujian b ON a.id=b.id_user 
				INNER JOIN tr_guru_tes c ON b.id_tes=c.id
	            WHERE (c.nama_ujian LIKE '%" . $search['value'] . "%' OR a.nama LIKE '%" . $search['value'] . "%' ) " . $wh_1 . " group by a.id ORDER BY a.id DESC LIMIT " . $start . ", " . $length . "")->getNumRows();

        $q_datanya = $this->db->query("SELECT * from m_siswa a 
				INNER JOIN tr_ikut_ujian b ON a.id=b.id_user 
				INNER JOIN tr_guru_tes c ON b.id_tes=c.id
	            WHERE (c.nama_ujian LIKE '%" . $search['value'] . "%' OR a.nama LIKE '%" . $search['value'] . "%' OR a.nim LIKE '%" . $search['value'] . "%' ) " . $wh_1 . " 
                group by a.id ORDER BY " . $coloumns . "  " . $urutan . " LIMIT " . $start . ", " . $length . "")->getResultArray();

        $data = array();
        $no = ($start + 1);


        foreach ($q_datanya as $d) {
            $data_ok = array();
            $data_ok[0] = $no++;
            $data_ok[1] = $d['nim'];
            $data_ok[2] = $d['nama'];
            $data_ok[3] = $d['nama_ujian'];
            $data_ok[4] = $d['tgl_mulai'];
            $data_ok[5] = $d['nilai'];
            $data_ok[6] = '<a href="' . base_url() . 'adm/h_ujian_peserta_cetak/' . $d['id_user'] . '" target="_blank" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-print" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Cetak</a>
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

    public function ujianPesertaCetak($uri3)
    {
        $a['detil_tes'] = $this->db->query("SELECT a.nama , a.jurusan, b.tgl_selesai,a.tgl_lahir 
												FROM m_siswa a
												INNER JOIN tr_ikut_ujian b ON a.id = b.id_user
												WHERE b.id_user = '$uri3'")->getRow();

        $a['statistik'] = $this->db->query("SELECT MAX(nilai) AS max_, MIN(nilai) AS min_, AVG(nilai) AS avg_ 
										FROM tr_ikut_ujian
										WHERE tr_ikut_ujian.id_user = '$uri3'")->getRow();
        $a['hasil'] = $this->db->query("SELECT (select count(id) from tr_ikut_ujian WHERE id_user = '$uri3') as jum_soal,c.nama, b.nama_ujian, b.tgl_mulai,b.jumlah_soal ,
												b.jumlah_soal, a.jml_benar, a.nilai, a.nilai_bobot, a.tgl_selesai FROM tr_ikut_ujian a 
												INNER JOIN tr_guru_tes b ON a.id_tes = b.id 
												INNER JOIN m_mapel c ON b.id_mapel = c.id WHERE a.id_user = '$uri3'")->getResult();

        return view("m_guru_tes_hasil_peserta_cetak", $a);
    }

    // Ujian

    public function ikutiUjian()
    {
        $a['sess_level'] = session('userData.admin_level');
        $a['sess_user'] =  session('userData.admin_user');
        $a['sess_konid'] = session('userData.admin_konid');

        $a['data'] = $this->db->query("SELECT 
        a.id, a.nama_ujian, a.jumlah_soal, a.waktu,a.tgl_mulai,
        b.nama nmmapel,
        c.nama nmguru,
        d.nilai_bobot,
        IF((d.status='Y' AND NOW() BETWEEN d.tgl_mulai AND d.tgl_selesai),'Sedang Tes',
        IF(d.status='Y' AND NOW() NOT BETWEEN d.tgl_mulai AND d.tgl_selesai,'Waktu Habis',
        IF(d.status='N','Selesai','Belum Ikut'))) status 
        FROM tr_guru_tes a
        INNER JOIN m_mapel b ON a.id_mapel = b.id
        INNER JOIN m_guru c ON a.id_guru = c.id
        LEFT JOIN tr_ikut_ujian d ON CONCAT('" . $a['sess_konid'] . "',a.id) = CONCAT(d.id_user,d.id_tes)
        ORDER BY a.id ASC")->getResult();
        //echo $this->db->last_query();
        $a['p']    = "m_list_ujian_siswa";
        return view('aaa', $a);
    }

    public function ikutUjianToken($uri4)
    {
        $a['sess_level'] = session('userData.admin_level');
        $a['sess_user'] =  session('userData.admin_user');
        $a['sess_konid'] = session('userData.admin_konid');

        $a['detil_user'] = $this->db->query("SELECT * FROM m_siswa WHERE id = '" . $a['sess_konid'] . "'")->getRow();
        $a['du'] = $this->db->query("SELECT a.id, a.tgl_mulai, a.terlambat, 
										a.token, a.nama_ujian, a.jumlah_soal, a.waktu,
										b.nama nmguru, c.nama nmmapel FROM tr_guru_tes a 
										INNER JOIN m_guru b ON a.id_guru = b.id
										INNER JOIN m_mapel c ON a.id_mapel = c.id 
										WHERE a.id = '$uri4'")->getRowArray();
        $a['dp'] = $this->db->query("SELEcT * FROM m_siswa WHERE id = '" . $a['sess_konid'] . "'")->getRowArray();

        if (!empty($a['du']) || !empty($a['dp'])) {
            $tgl_selesai = $a['du']['tgl_mulai'];
            $tgl_selesai = strtotime($tgl_selesai);
            $tgl_baru = date('F j, Y H:i:s', $tgl_selesai);

            $tgl_terlambat = strtotime("+" . $a['du']['terlambat'] . " minutes", $tgl_selesai);
            $tgl_terlambat_baru = date('F j, Y H:i:s', $tgl_terlambat);

            $a['tgl_mulai'] = $tgl_baru;
            $a['terlambat'] = $tgl_terlambat_baru;

            $a['p']    = "m_token";
            return view('aaa', $a);
        } else {
            return redirect()->to('adm/ikuti_ujian');
        }
    }

    public function prosesIkutUjian($uri4)
    {
        $a['sess_level'] = session('userData.admin_level');
        $a['sess_user'] =  session('userData.admin_user');
        $a['sess_konid'] = session('userData.admin_konid');

        $a['detil_user'] = $this->db->query("SELECT * FROM m_siswa WHERE id = '" . $a['sess_konid'] . "'")->getRow();


        $cek_sdh_selesai = $this->db->query("SELECT id FROM tr_ikut_ujian WHERE id_tes = '$uri4' AND id_user = '" . $a['sess_konid'] . "' AND status = 'N'")->getNumRows();

        //sekalian validasi waktu sudah berlalu...
        if ($cek_sdh_selesai < 1) {
            //ini jika ujian belum tercatat, belum ikut
            //ambil detil soal
            $cek_detil_tes = $this->db->query("SELECT * FROM tr_guru_tes WHERE id = '$uri4'")->getRow();
            $q_cek_sdh_ujian = $this->db->query("SELECT id FROM tr_ikut_ujian WHERE id_tes = '$uri4' AND id_user = '" . $a['sess_konid'] . "'");
            $d_cek_sdh_ujian = $q_cek_sdh_ujian->getRow();
            $cek_sdh_ujian    = $q_cek_sdh_ujian->getNumRows();
            $acakan = $cek_detil_tes->jenis == "acak" ? "ORDER BY RAND()" : "ORDER BY id ASC";

            if ($cek_sdh_ujian < 1) {
                $soal_urut_ok = array();
                $q_soal            = $this->db->query("SELECT id, file, tipe_file, soal, opsi_a, opsi_b, opsi_c, opsi_d, opsi_e, '' AS jawaban FROM m_soal WHERE id_mapel = '" . $cek_detil_tes->id_mapel . "' AND id_guru = '" . $cek_detil_tes->id_guru . "' " . $acakan . " LIMIT " . $cek_detil_tes->jumlah_soal)->getResult();
                $i = 0;
                foreach ($q_soal as $s) {
                    $soal_per = new stdClass();
                    $soal_per->id = $s->id;
                    $soal_per->soal = $s->soal;
                    $soal_per->file = $s->file;
                    $soal_per->tipe_file = $s->tipe_file;
                    $soal_per->opsi_a = $s->opsi_a;
                    $soal_per->opsi_b = $s->opsi_b;
                    $soal_per->opsi_c = $s->opsi_c;
                    $soal_per->opsi_d = $s->opsi_d;
                    $soal_per->opsi_e = $s->opsi_e;
                    $soal_per->jawaban = $s->jawaban;
                    $soal_urut_ok[$i] = $soal_per;
                    $i++;
                }
                $soal_urut_ok = $soal_urut_ok;
                $list_id_soal    = "";
                $list_jw_soal     = "";
                if (!empty($q_soal)) {
                    foreach ($q_soal as $d) {
                        $list_id_soal .= $d->id . ",";
                        $list_jw_soal .= $d->id . ":,";
                    }
                }
                $list_id_soal = substr($list_id_soal, 0, -1);
                $list_jw_soal = substr($list_jw_soal, 0, -1);
                $waktu_selesai = tambah_jam_sql($cek_detil_tes->waktu);
                $time_mulai        = date('Y-m-d H:i:s');
                $this->db->query("INSERT INTO tr_ikut_ujian VALUES (null, '$uri4', '" . $a['sess_konid'] . "', '$list_id_soal', '$list_jw_soal', 0, 0, 0, '$time_mulai', ADDTIME('$time_mulai', '$waktu_selesai'), 'Y')");

                $detil_tes = $this->db->query("SELECT * FROM tr_ikut_ujian WHERE id_tes = '$uri4' AND id_user = '" . $a['sess_konid'] . "'")->getRow();

                $soal_urut_ok = $soal_urut_ok;
            } else {
                $q_ambil_soal     = $this->db->query("SELECT * FROM tr_ikut_ujian WHERE id_tes = '$uri4' AND id_user = '" . $a['sess_konid'] . "'")->getRow();

                $urut_soal         = explode(",", $q_ambil_soal->list_jawaban);
                $soal_urut_ok    = array();
                for ($i = 0; $i < sizeof($urut_soal); $i++) {
                    $pc_urut_soal = explode(":", $urut_soal[$i]);
                    $pc_urut_soal1 = empty($pc_urut_soal[1]) ? "''" : "'" . $pc_urut_soal[1] . "'";
                    $ambil_soal = $this->db->query("SELECT *, $pc_urut_soal1 AS jawaban FROM m_soal WHERE id = '" . $pc_urut_soal[0] . "'")->getRow();
                    $soal_urut_ok[] = $ambil_soal;
                }

                $detil_tes = $q_ambil_soal;

                $soal_urut_ok = $soal_urut_ok;
            }


            $pc_list_jawaban = explode(",", $detil_tes->list_jawaban);

            $arr_jawab = array();
            foreach ($pc_list_jawaban as $v) {
                $pc_v = explode(":", $v);
                $idx = $pc_v[0];
                $val = $pc_v[1];

                $arr_jawab[$idx] = $val;
            }

            $html = '';
            $no = 1;
            if (!empty($soal_urut_ok)) {
                foreach ($soal_urut_ok as $d) {
                    $tampil_media = tampil_media("/upload/gambar_soal/" . $d->file, '700px', '350px');

                    $html .= '<input type="hidden" name="id_soal_' . $no . '" value="' . $d->id . '">';
                    $html .= '<div class="step" id="widget_' . $no . '">';

                    $html .= '<p>' . $d->soal . '</p><p>' . $tampil_media . '</p><div class="funkyradio">';

                    for ($j = 0; $j < JML_OPSI; $j++) {
                        $opsi = "opsi_" . $this->opsi[$j];

                        $checked = $arr_jawab[$d->id] == strtoupper($this->opsi[$j]) ? "checked" : "";

                        $pc_pilihan_opsi = explode("#####", $d->$opsi);

                        $tampil_media_opsi = (is_file('/upload/gambar_soal/' . $pc_pilihan_opsi[0]) || $pc_pilihan_opsi[0] != "") ? tampil_media('/upload/gambar_opsi/' . $pc_pilihan_opsi[0], '300px', '200px') : '';

                        $html .= '<div class="funkyradio-success">
				                <input type="radio" id="opsi_' . strtoupper($this->opsi[$j]) . '_' . $d->id . '" name="opsi_' . $no . '" value="' . strtoupper($this->opsi[$j]) . '" ' . $checked . '> <label for="opsi_' . strtoupper($this->opsi[$j]) . '_' . $d->id . '"><div class="huruf_opsi">' . $this->opsi[$j] . '</div> <p>' . $pc_pilihan_opsi[1] . '</p><p>' . $tampil_media_opsi . '</p></label></div>';
                    }
                    $html .= '</div></div>';
                    $no++;
                }
            }

            $a['jam_mulai'] = $detil_tes->tgl_mulai;
            $a['jam_selesai'] = $detil_tes->tgl_selesai;
            $a['id_tes'] = $cek_detil_tes->id;
            $a['nama_ujian'] = $cek_detil_tes->nama_ujian;
            $a['no'] = $no;
            $a['html'] = $html;

            return view('v_ujian', $a);
        } else {
            redirect('adm/sudah_selesai_ujian/' . $uri4);
        }
    }

    public function selesaiUjian($uri3)
    {


        //var def session
        $a['sess_level'] = session('userData.admin_level');
        $a['sess_user'] =  session('userData.admin_user');
        $a['sess_konid'] = session('userData.admin_konid');
        //var def uri segment

        $q_nilai = $this->db->query("SELECT nilai, tgl_selesai FROM tr_ikut_ujian WHERE id_tes = $uri3 AND id_user = '" . $a['sess_konid'] . "' AND status = 'N'")->getRow();
        if (empty($q_nilai)) {
            return redirect()->to(site_url('adm/ikut_ujian/_/') . $uri3);
        } else {
            $a['p'] = "v_selesai_ujian";
            //$a['data'] = "<div class='alert alert-danger'>Anda telah selesai mengikuti ujian ini pada : <strong style='font-size: 16px'>".tjs($q_nilai->tgl_selesai, "l")."</strong>, dan mendapatkan nilai : <strong style='font-size: 16px'>".$q_nilai->nilai."</strong></div>";
            $a['data'] = "<div class='alert alert-danger'>Anda telah selesai mengikuti ujian ini pada : <strong style='font-size: 16px'>" . tjs($q_nilai->tgl_selesai, "l");
        }
        return view('aaa', $a);
    }

    public function get_servertime()
    {
        $now = new DateTime();
        $dt = $now->format("M j, Y H:i:s O");

        return $this->response->setJSON($dt);
    }

    public function simpanSatu($uri4)
    {

        $a['sess_level'] = session('userData.admin_level');
        $a['sess_user'] =  session('userData.admin_user');
        $a['sess_konid'] = session('userData.admin_konid');

        $p            = json_decode(file_get_contents('php://input'));

        $update_     = "";
        for ($i = 1; $i < $p->jml_soal; $i++) {
            $_tjawab     = "opsi_" . $i;
            $_tidsoal     = "id_soal_" . $i;
            $jawaban_     = empty($p->$_tjawab) ? "" : $p->$_tjawab;
            $update_    .= "" . $p->$_tidsoal . ":" . $jawaban_ . ",";
        }
        $update_        = substr($update_, 0, -1);
        $this->db->query("UPDATE tr_ikut_ujian SET list_jawaban = '" . $update_ . "' WHERE id_tes = '$uri4' AND id_user = '" . $a['sess_konid'] . "'");
        //echo $this->db->last_query();

        $q_ret_urn     = $this->db->query("SELECT list_jawaban FROM tr_ikut_ujian WHERE id_tes = '$uri4' AND id_user = '" . $a['sess_konid'] . "'");

        $d_ret_urn     = $q_ret_urn->getRowArray();
        $ret_urn     = explode(",", $d_ret_urn['list_jawaban']);
        $hasil         = array();
        foreach ($ret_urn as $key => $value) {
            $pc_ret_urn = explode(":", $value);
            $idx         = $pc_ret_urn['0'];
            $val         = $pc_ret_urn['1'];
            $hasil[] = $val;
        }

        $d['data'] = $hasil;
        $d['status'] = "ok";

        return j($d);
    }

    public function simpanAkhir($uri4)
    {
        $a['sess_level'] = session('userData.admin_level');
        $a['sess_user'] =  session('userData.admin_user');
        $a['sess_konid'] = session('userData.admin_konid');

        $p = json_decode(file_get_contents('php://input'));

        $jumlah_soal = $p->jml_soal;
        $jumlah_benar = 0;
        //$jumlah_bobot = 0;
        $update_ = "";
        //nilai bobot 
        $array_bobot     = array();
        $array_nilai    = array();
        for ($i = 1; $i < $p->jml_soal; $i++) {
            $_tjawab     = "opsi_" . $i;
            $_tidsoal     = "id_soal_" . $i;
            $jawaban_     = empty($p->$_tjawab) ? "" : $p->$_tjawab;
            $cek_jwb     = $this->db->query("SELECT bobot, jawaban FROM m_soal WHERE id = '" . $p->$_tidsoal . "'")->getRow();
            //untuknilai bobot
            $bobotnya     = $cek_jwb->bobot;
            $array_bobot[$bobotnya] = empty($array_bobot[$bobotnya]) ? 1 : $array_bobot[$bobotnya] + 1;

            $q_update_jwb = "";
            if ($cek_jwb->jawaban == $jawaban_) {
                //jika jawaban benar
                $jumlah_benar++;
                $array_nilai[$bobotnya] = empty($array_nilai[$bobotnya]) ? 1 : $array_nilai[$bobotnya] + 1;
                $q_update_jwb = "UPDATE m_soal SET jml_benar = jml_benar + 1 WHERE id = '" . $p->$_tidsoal . "'";
            } else {
                //jika jawaban salah
                $array_nilai[$bobotnya] = empty($array_nilai[$bobotnya]) ? 0 : $array_nilai[$bobotnya] + 0;
                $q_update_jwb = "UPDATE m_soal SET jml_salah = jml_salah + 1 WHERE id = '" . $p->$_tidsoal . "'";
            }

            $this->db->query($q_update_jwb);

            $update_    .= "" . $p->$_tidsoal . ":" . $jawaban_ . ",";
        }
        //perhitungan nilai bobot
        ksort($array_bobot);
        ksort($array_nilai);
        $nilai_bobot_benar = 0;
        $nilai_bobot_total = 0;
        foreach ($array_bobot as $key => $value) {
            $nilai_bobot_benar = $nilai_bobot_benar + ($key * $array_nilai[$key]);
            $nilai_bobot_total = $nilai_bobot_total + ($key * $array_bobot[$key]);
        }
        $update_        = substr($update_, 0, -1);
        //$nilai = ($jumlah_benar/($jumlah_soal-1)) * 100;
        // if ($jumlah_soal === 20) {
        //     $bobot_sekarang = 5;
        // } else if ($jumlah_soal === 40) {
        //     $bobot_sekarang = 2.5;
        // } else {
        //     $bobot_sekarang = 1;
        // }

        $nilai_bobot = ($nilai_bobot_benar/$nilai_bobot_total) * 100;
        
        $nilai = ($nilai_bobot / ($jumlah_soal * 100)) * 100;

        // $nilai = $jumlah_benar * $bobot_sekarang;
        // $nilai_bobot = ($nilai_bobot_benar / $nilai_bobot_total) * 100;

        /*
			echo var_dump($array_bobot);
			echo var_dump($array_nilai);
			echo "Benar bobot : ".$nilai_bobot_benar."<br>";
			echo "Jml bobot : ".$nilai_bobot_total."<br>";
			echo "Nilai bobot : ".$nilai_bobot."<br>";
			//akhir perhitungan nilai bobot
			exit;
			*/
        $this->db->query("UPDATE tr_ikut_ujian SET jml_benar = " . $jumlah_benar . ", nilai_bobot = " . $nilai_bobot . ", nilai = '" . $nilai . "', list_jawaban = '" . $update_ . "', status = 'N' WHERE id_tes = '$uri4' AND id_user = '" . $a['sess_konid'] . "'");
        $a['status'] = "ok";
        return j($a);
    }
}
