<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KategoriSoal;
use Config\Database;
use Config\Services;

class AdmKategoriSoalController extends BaseController
{
    private $db;
    protected $kategori;
    public function __construct()
    {
        $this->db = Database::connect();
        $this->kategori = new KategoriSoal();
    }

    public function index()
    {
        //
        //var def session
        $a['sess_level'] = session('userData.admin_level');
        $a['sess_user'] =  session('userData.admin_user');
        $a['sess_konid'] = session('userData.admin_konid');

        $a['huruf_opsi'] = array("a", "b", "c", "d", "e");

        $a['p']    = "m_akses_soal";

        return view('aaa', $a);
    }

    public function getListSoal()
    {
        $start = $this->request->getPost('start');
        $length = $this->request->getPost('length');
        $draw = $this->request->getPost('draw');
        $search = $this->request->getPost('search');

        $d_total_row = $this->db->query("SELECT a.id,a.id_guru, a.id_mapel, b.nama as nama_guru, c.nama as kategori FROM tr_guru_mapel a LEFT JOIN m_guru b ON a.id_guru=b.id LEFT JOIN m_mapel c ON a.id_mapel=c.id WHERE b.nama LIKE '%" . $search['value'] . "%'")->getNumRows();

        $q_datanya = $this->db->query("SELECT a.id,a.id_guru, a.id_mapel, b.nama as nama_guru, c.nama as kategori ,
                                            (SELECT COUNT(id) FROM tr_guru_mapel) AS ada
                                            FROM tr_guru_mapel a LEFT JOIN m_guru b ON a.id_guru=b.id LEFT JOIN m_mapel c ON a.id_mapel=c.id WHERE b.nama LIKE '%" . $search['value'] . "%' ORDER BY a.id DESC LIMIT " . $start . ", " . $length . "")->getResultArray();
        $data = array();
        $no = ($start + 1);

        //
        foreach ($q_datanya as $d) {
            $data_ok = array();
            $data_ok[0] = $no++;
            $data_ok[1] = $d['nama_guru'];
            $data_ok[2] = $d['kategori'];



            $data_ok[3] = '<div class="btn-group">
                    <a href="#" onclick="return m_akses_h(' . $d['id'] . ',' . $d['id_guru'] . ',' . $d['id_mapel'] . ');" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Hapus</a>
                     ';
            // <a href="#" onclick="return m_siswa_e('.$d['id'].');" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-pencil" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Edit</a>
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

    public function simpanSoal()
    {
      
        $p = json_decode(file_get_contents('php://input'));
        $id_guru = $this->request->getVar('id_guru');
        $id_mapel = $this->request->getVar('id_mapel');

        $ket = "tambah";
        $data = [
            'id_guru' => $id_guru,
            'id_mapel' =>$id_mapel
        ];
        $query = $this->kategori->kategoriSoal($data);
        // $query = $this->db->query("INSERT INTO tr_guru_mapel set ('id_guru', 'id_mapel') VALUES ('" .$id_guru. "', '" . $id_mapel . "')");
        // var_dump($query);
        // die;
        if($query){
            $ret_arr['status']     = "ok";
            $ret_arr['caption']    = $ket . " sukses";
        }else{
            $ret_arr['status']     = "Maaf";
            $ret_arr['caption']    = $ket. " Gagal";
        }
        
        return $this->response->setJSON($ret_arr);
    }

    public function detSoal($uri4)
    {
        $a = $this->db->query("SELECT * FROM tr_guru_mapel WHERE id = '$uri4'")->getRow();

        return $this->response->setJSON($a);
    }

    public function delSoal()
    {
        $id = $this->request->getVar('id');
        $id_guru = $this->request->getVar('id_guru');
        $id_mapel = $this->request->getVar('id_mapel');

        // $tes = "SELECT * FROM m_soal WHERE id_guru = '".$id_guru."' and  id_mapel = '".$id_mapel."'";
        // var_dump($tes);
        // die();
        $a = $this->db->query("SELECT id FROM m_soal WHERE id_guru = '" . $id_guru . "' AND  id_mapel = '" . $id_mapel . "'  ")->getNumRows();

        // var_dump($a);
        // die();
        if ($a > 0) {
            $ret_arr['status']     = "Maaf";
            $ret_arr['caption']    = "Data Tidak Bisa dihapus karena ada dalam soal";
            return $this->response->setJSON($ret_arr);
        } else {

            $this->db->query("DELETE FROM tr_guru_mapel WHERE id = '" . $id . "'");
            $ret_arr['status']     = "ok";
            $ret_arr['caption']    = "hapus sukses";
            return $this->response->setJSON($ret_arr);
        }
    }
}
