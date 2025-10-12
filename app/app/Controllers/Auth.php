<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdmModel;
use CodeIgniter\API\ResponseTrait;

class Auth extends BaseController
{
    // use ResponseTrait;

    private $admModel;

    public function __construct() {
        $this->admModel = new AdmModel();
    }
    public function index()
    {
        //
        return view('aaa_login');
    }

    public function act_login(){
        $username    = $this->request->getVar('username');
        $password    = $this->request->getVar('password');

        $password2    = md5($password);

        $q_data        = $this->admModel->getAdmin($username, $password2);
        $j_data        = $q_data->getNumRows();
        $a_data        = $q_data->getRow();

        $_log        = array();
        if ($j_data === 1) {
            $sess_nama_user = "";
            if ($a_data->level == "siswa") {
                $det_user = $this->admModel->getSiswa($a_data->kon_id)->getRow();
                if (!empty($det_user)) {
                    $sess_nama_user = $det_user->nama;
                }
            } else if ($a_data->level == "guru") {
                $det_user = $this->admModel->getGuru($a_data->kon_id)->getRow();
                if (!empty($det_user)) {
                    $sess_nama_user = $det_user->nama;
                }
            } else {
                $sess_nama_user = "Administrator Pusat";
            }
            $data = array(
                'admin_id' => $a_data->id,
                'admin_user' => $a_data->username,
                'admin_level' => $a_data->level,
                'admin_konid' => $a_data->kon_id,
                'admin_nama' => $sess_nama_user,
                'admin_valid' => true
            );

            $loggedUserId = $a_data->id;
            $loggedUserFullName = $sess_nama_user;

            session()->set('loggedUserId' , $loggedUserId);
            session()->set('loggedUserFullName' , $loggedUserFullName);

            session()->set('userData', $data);

            $_log['log']['status']            = "1";
            $_log['log']['keterangan']        = "Login berhasil";
            $_log['log']['detil_admin']        = session('userData');
        } else {
            $_log['log']['status']            = "0";
            $_log['log']['keterangan']        = "Maaf, username dan password tidak ditemukan";
            $_log['log']['detil_admin']        = null;
        }

        return $this->response->setJSON($_log);
    }

    public function logout() {
        if(session()->has('loggedUserId')) {
            session()->remove('loggedUserId');
            session()->remove('userData');
            session()->remove('loggedUserFullName');
            
            return redirect()->to('/')->with('fail', "You are logged out.");
        }
    }
}
