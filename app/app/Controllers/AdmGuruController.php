<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdmGuruModel;
use Config\Database;
use Config\Services;

class AdmGuruController extends BaseController
{
    private $admGuru;
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->admGuru = new AdmGuruModel();
    }

    public function index()
    {
        //var def session
        $a['sess_level'] = session('userData.admin_level');
        $a['sess_user'] = session('userData.admin_user');
        $a['sess_konid'] = session('userData.admin_konid');
        $a['p']    = "m_guru";

        return view('aaa', $a);
    }

    public function getListGuru()
    {
        $start = $this->request->getPost('start');
        $length = $this->request->getPost('length');
        $draw = $this->request->getPost('draw');
        $search = $this->request->getPost('search');

        $d_total_row = $this->admGuru->getListNumber($search['value'])->getNumRows();

        $q_datanya = $this->admGuru->getList($search['value'], $start,  $length)->getResultArray();
        $data = array();
        $no = ($start + 1);

        foreach ($q_datanya as $d) {
            $data_ok = array();
            $data_ok[0] = $no++;
            $data_ok[1] = $d['nama'];
            $data_ok[2] = $d['nip'];
            $data_ok[3] = '<div class="btn-group">
                          <a href="#" onclick="return m_guru_e(' . $d['id'] . ');" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-pencil" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Edit</a>
                          <a href="#" onclick="return m_guru_h(' . $d['id'] . ');" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Hapus</a>
                          <a href="#" onclick="return m_guru_matkul(' . $d['id'] . ');" class="btn btn-success btn-xs"><i class="glyphicon glyphicon-th-list" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;MaPel Diampu</a>
                         ';

            if ($d['ada'] == "0") {
                $data_ok[3] .= '<a href="#" onclick="return m_guru_u(' . $d['id'] . ');" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-user" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Aktif User</a>';
            } else {
                $data_ok[3] .= '<a href="#" onclick="return m_guru_ur(' . $d['id'] . ');" class="btn btn-warning btn-xs"><i class="glyphicon glyphicon-random" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Reset Pass</a>';
            }

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

    public function getDetail($id)
    {
        $a = $this->admGuru->getDetail($id)->getRow();
        return $this->response->setJSON($a);
    }

    public function simpanGuru()
    {
        $p = json_decode(file_get_contents('php://input'));

        $ket     = "";
        if (!empty($p->id)) {
            try {
                //code...
                $this->admGuru->updateGuru($p);

                $ret_arr['status']     = "ok";
                $ret_arr['caption']    = "Edit Sukses" ;
            } catch (\Throwable $th) {
                //throw $th;
                $ret_arr['status']     = "no";
                $ret_arr['caption']    = $th->getMessage() ;
            }
            
        } else {
            try {
                //code...
                $this->admGuru->insertGuru($p);
                $ret_arr['status']     = "ok";
                $ret_arr['caption']    = "Tambah Sukses" ;
            } catch (\Throwable $th) {
                //throw $th;
                $ret_arr['status']     = "no";
                $ret_arr['caption']    = $th->getMessage() ;
            }
        }
        return $this->response->setJSON($ret_arr);
    }

    public function delGuru($uri4)
    {

        $this->admGuru->delGuru($uri4);

        $ret_arr['status']     = "ok";
        $ret_arr['caption']    = "hapus sukses";
        return $this->response->setJSON($ret_arr);
    }

    public function ambilMatkul($uri4)
    {
        $matkul = $this->admGuru->ambilMatkul($uri4);
        $ret_arr['status'] = "ok";
        $ret_arr['data'] = $matkul;
        return $this->response->setJSON($ret_arr);
    }

    public function simpanMatkul()
    {
        $p = json_decode(file_get_contents('php://input'));
        $ket     = "";
        //echo var_dump($p);
        $ambil_matkul = $this->admGuru->listMapel();
        if (!empty($ambil_matkul)) {
            foreach ($ambil_matkul as $a) {
                $p_sub = "id_mapel_" . $a->id;
                if (!empty($p->$p_sub)) {

                    $cek_sudah_ada = $this->admGuru->checkMapel($p->id_mhs, $a->id);

                    if ($cek_sudah_ada < 1) {
                        $this->admGuru->insertMapel($p->id_mhs, $a->id);
                    } else {
                        $this->admGuru->updateMapel($p->$p_sub, $p->id_mhs, $a->id);
                    }
                } else {
                    //echo "0<br>";
                    $this->admGuru->delMapel($p->id_mhs, $a->id);
                }
            }
        }
        $ret_arr['status']     = "ok";
        $ret_arr['caption']    = $ket . " sukses";

        return $this->response->setJSON($ret_arr);
    }

    public function resetPass($uri4)
    {
        $det_user = $this->admGuru->getDetail($uri4)->getRow();

        $this->admGuru->resetPass($det_user->nip, $det_user->id);

        $ret_arr['status']     = "ok";
        $ret_arr['caption']    = "Update password sukses";
        return $this->response->setJSON($ret_arr);
    }

    public function importGuru()
    {
        $a['sess_level'] = session('userData.admin_level');
        $a['sess_user'] = session('userData.admin_user');
        $a['sess_konid'] = session('userData.admin_konid');
        $a['p']    = "f_guru_import";

        return view('aaa', $a);
    }

    public function userAktif($uri4)
    {
        $det_user = $this->db->query("SELECT id, nip FROM m_guru WHERE id = '$uri4'")->getRow();

        if (!empty($det_user)) {
            $q_cek_username = $this->db->query("SELECT id FROM m_admin WHERE username = '" . $det_user->nip . "' AND level = 'guru'")->getNumRows();

            if ($q_cek_username < 1) {

                $this->db->query("INSERT INTO m_admin VALUES (null, '" . $det_user->nip . "', md5('" . $det_user->nip . "'), 'guru', '" . $det_user->id . "')");
                $ret_arr['status']     = "ok";
                $ret_arr['caption']    = "tambah user sukses";
                $this->response->setJSON($ret_arr);
            } else {
                $ret_arr['status']     = "gagal";
                $ret_arr['caption']    = "Username telah digunakan";
                $this->response->setJSON($ret_arr);
            }
        } else {
            $ret_arr['status']     = "gagal";
            $ret_arr['caption']    = "tambah user gagal";
            $this->response->setJSON($ret_arr);
        }
    }
}
