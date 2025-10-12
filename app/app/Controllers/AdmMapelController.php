<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Database\Query;
use Config\Database;
use Config\Services;

class AdmMapelController extends BaseController
{
    private $db;

    public function __construct()
    {
      
        $this->db = Database::connect();
    }

    public function index()
    {
        //
        $a['sess_level'] = session('userData.admin_level');
        $a['sess_user'] =  session('userData.admin_user');
        $a['sess_konid'] = session('userData.admin_konid');

        $a['p']    = "m_mapel";

        return view('aaa', $a);
    }

    public function getListMapel()
    {
        $start = $this->request->getPost('start');
        $length = $this->request->getPost('length');
        $draw = $this->request->getPost('draw');
        $search = $this->request->getPost('search');

        $d_total_row = $this->db->query("SELECT id FROM m_mapel a WHERE a.nama LIKE '%" . $search['value'] . "%'")->getNumRows();

        $q_datanya = $this->db->query("SELECT a.*
											FROM m_mapel a
	                                        WHERE a.nama LIKE '%" . $search['value'] . "%' ORDER BY a.id DESC LIMIT " . $start . ", " . $length . "")->getResultArray();
        $data = array();
        $no = ($start + 1);

        foreach ($q_datanya as $d) {
            $data_ok = array();
            $data_ok[0] = $no++;
            $data_ok[1] = $d['nama'];
            $data_ok[2] = '<div class="btn-group">
                          <a href="#" onclick="return m_mapel_e(' . $d['id'] . ');" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-pencil" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Edit</a>
                          <a href="#" onclick="return m_mapel_h(' . $d['id'] . ');" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Hapus</a>
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

    public function detMapel($uri4)
    {
        $a = $this->db->query("SELECT * FROM m_mapel WHERE id = '$uri4'")->getRow();
        return $this->response->setJSON($a);
    }

    public function simpanMapel()
    {
        $p = json_decode(file_get_contents('php://input'));
        $ket     = "";
        if ($p->id != 0) {
            $this->db->query("UPDATE m_mapel SET nama = '" . bersih($p, "nama") . "'
								WHERE id = '" . bersih($p, "id") . "'");
            $ket = "edit";
        } else {
            $ket = "tambah";
            $this->db->query("INSERT INTO m_mapel VALUES (null, '" . bersih($p, "nama") . "')");
        }

        $ret_arr['status']     = "ok";
        $ret_arr['caption']    = $ket . " sukses";
        // j($ret_arr);
        return $this->response->setJSON($ret_arr);
    }

    public function delMapel($uri4)
    {
        $this->db->query("DELETE FROM m_mapel WHERE id = '" . $uri4 . "'");
        $ret_arr['status']     = "ok";
        $ret_arr['caption']    = "hapus sukses";
        return $this->response->setJSON($ret_arr);
    }
}
