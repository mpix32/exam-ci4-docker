<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdmModel;
use Config\Database;

class AdmController extends BaseController
{
    private $waktu_sql;
    private $admModel;
    private $db;

    public function __construct()
    {
        $this->admModel = new AdmModel();
        $this->db = Database::connect();
        $this->waktu_sql = $this->admModel->waktu_sql()->waktu;
    }

    public function index()
    {
        $a['sess_level'] = session('userData.admin_level');
        $a['sess_user'] = session('userData.admin_user');
        $a['sess_konid'] = session('userData.admin_konid');

        $a['p']            = "v_main";
        $a['waktu_sql']    = $this->waktu_sql;

        return view('aaa', $a);
    }

    public function m_siswa()
    {
        //var def session
        $a['sess_level'] = session('userData.admin_level');
        $a['sess_user'] = session('userData.admin_user');
        $a['sess_konid'] = session('userData.admin_konid');

        $a['p']    = "m_siswa";
        return view('aaa', $a);
    }

    public function getSiswaList()
    {
        $start = $this->request->getVar('start');
        $length = $this->request->getVar('length');
        $draw = $this->request->getVar('draw');
        $search = $this->request->getVar('search');
        $order = $this->request->getVar('order');
        $urutan = $order[0]['dir'];
        $coloumn = $order[0]['column'];

        $d_total_row = $this->admModel->datatabelSiswa($search['value'])->getNumRows();

        $q_datanya = $this->admModel->datatabelSiswaLength($search['value'], $start, $length, $urutan, $coloumn)->getResultArray();
        $data = array();
        $no = ($start + 1);

        foreach ($q_datanya as $d) {
            $data_ok = array();
            $data_ok[0] = $no++;
            $data_ok[1] = $d['nama'];
            $data_ok[2] = $d['nim'];
            $data_ok[3] = $d['jurusan'];
            $data_ok[4] = tjs($d['tgl_lahir'], 's');

            $data_ok[5] = '<div class="btn-group">
                      <a href="#" onclick="return m_siswa_e(' . $d['id'] . ');" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-pencil" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Edit</a>
                      <a href="#" onclick="return m_siswa_h(' . $d['id'] . ');" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-remove" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Hapus</a>
                     ';

            if ($d['ada'] == "0") {
                $data_ok[5] .= '<a href="#" onclick="return m_siswa_u(' . $d['id'] . ');" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-user" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Aktifkan User</a>';
            } else {
                $data_ok[5] .= '<a href="#" onclick="return m_siswa_ur(' . $d['id'] . ');" class="btn btn-warning btn-xs"><i class="glyphicon glyphicon-random" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Reset Password</a>';
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

    public function detailSiswa($uri4)
    {
        $a = $this->admModel->getSiswa($uri4)->getRowObject();
        return $this->response->setJSON($a);
    }

    public function delSiswa($uri4)
    {
        $this->admModel->deleteSiswa($uri4);
        $this->admModel->deleteSiswaAdmin($uri4);
        $ret_arr['status']     = "ok";
        $ret_arr['caption']    = "hapus sukses";
        // j($ret_arr);
        return $this->response->setJSON($ret_arr);
        // exit();
    }

    public function activeSiswa($uri4)
    {

        $det_user = $this->admModel->getSiswa($uri4)->getRow();

        if (!empty($det_user)) {
            $q_cek_username = $this->admModel->getAdminSelect($det_user->nim)->getNumRows();

            if ($q_cek_username < 1) {

                $this->admModel->insertAdmin($det_user->nim, $det_user->id);

                $ret_arr['status']     = "ok";
                $ret_arr['caption']    = "tambah user sukses";
                // j($ret_arr);
            } else {
                $ret_arr['status']     = "gagal";
                $ret_arr['caption']    = "Username telah digunakan";
                // j($ret_arr);
            }
        } else {
            $ret_arr['status']     = "gagal";
            $ret_arr['caption']    = "tambah user gagal";
            // j($ret_arr);
        }

        return $this->response->setJSON($ret_arr);
    }

    public function resetSiswa($uri4)
    {
        $det_user = $this->admModel->getSiswa($uri4)->getRow();

        $this->admModel->updateAdmin($det_user->nim, $det_user->id);

        $ret_arr['status']     = "ok";
        $ret_arr['caption']    = "Update password sukses";
        return $this->response->setJSON($ret_arr);
    }

    public function addSiswa()
    {
        
        // var_dump($p->id);
        // die;
        $rules = [
            'nim' => 'required|max_length[16]|alpha_numeric',
        ];

        if (!$this->validate($rules)) {
            $ret_arr['status']     = "no";
            $ret_arr['caption']    = 'Please check NIK for number and min 16 Character';
            return $this->response->setJSON($ret_arr);
        } else {
            $p = json_decode(file_get_contents('php://input'));
            $ket     = "";
            if (!empty($p->id)) {
                try {
                    //code...
                    $this->admModel->updateSiswa($p);
                    $ket = "edit";

                    $ret_arr['status']     = "ok";
                    $ret_arr['caption']    = "Update sukses";
                } catch (\Throwable $th) {
                    //throw $th;
                    $ret_arr['status']     = "no";
                    $ret_arr['caption']    = $th->getMessage();
                }

                return $this->response->setJSON($ret_arr);
            } else {
                $ket = "Tambah";
                $det = $this->admModel->getSiswaSelect($p)->getNumRows();

                if ($det < 1) {
                    $this->admModel->insertSiswa($p);
                    $ret_arr['status']     = "ok";
                    $ret_arr['caption']    = $ket . " sukses";
                    //j($ret_arr);
                } else {
                    $ret_arr['status']     = "Gagal";
                    $ret_arr['caption']    = $ket . " Gagal Cek NIK";
                    //j($ret_arr);
                }
                return $this->response->setJSON($ret_arr);
            }
        }
    }

    public function siswaImport()
    {

        $a['sess_level'] = session('userData.admin_level');
        $a['sess_user'] = session('userData.admin_user');
        $a['sess_konid'] = session('userData.admin_konid');

        $a['p']    = "f_siswa_import";
        return view('aaa', $a);
    }

    public function rubah_password()
    {
        //var def session
        $a['sess_admin_id'] = session('userData.admin_id');

        $a['sess_level'] = session('userData.admin_level');
        $a['sess_user'] = session('userData.admin_user');
        $a['sess_konid'] = session('userData.admin_konid');

        $data = $this->db->query("SELECT id, kon_id, level, username FROM m_admin WHERE id = '" . $a['sess_admin_id'] . "'")->getRow();
        return $this->response->setJSON($data);
    }

    public function rubah_password_simpan()
    {

        $a['sess_admin_id'] = session('userData.admin_id');

        $a['sess_level'] = session('userData.admin_level');
        $a['sess_user'] = session('userData.admin_user');
        $a['sess_konid'] = session('userData.admin_konid');

        $p = json_decode(file_get_contents('php://input'));

        $p1_md5 = md5($p->p1);
        $p2_md5 = md5($p->p2);
        $p3_md5 = md5($p->p3);
        $cek_pass_lama = $this->db->query("SELECT password FROM m_admin WHERE id = '" . $a['sess_admin_id'] . "'")->getRow();
        if ($cek_pass_lama->password != $p1_md5) {
            $ret['status'] = "error";
            $ret['msg'] = "Password lama tidak sama...";
        } else if ($p2_md5 != $p3_md5) {
            $ret['status'] = "error";
            $ret['msg'] = "Password baru konfirmasinya tidak sama...";
        } else if (strlen($p->p2) < 6) {
            $ret['status'] = "error";
            $ret['msg'] = "Password baru minimal terdiri dari 6 huruf..";
        } else {
            $this->db->query("UPDATE m_admin SET password = '" . $p3_md5 . "' WHERE id = '" . $a['sess_admin_id'] . "'");
            $ret['status'] = "ok";
            $ret['msg'] = "Password berhasil diubah...";
        }
        return $this->response->setJSON($ret);
    }
}
