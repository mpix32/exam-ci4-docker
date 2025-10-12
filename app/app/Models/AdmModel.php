<?php

namespace App\Models;

use CodeIgniter\Model;

class AdmModel extends Model
{
    public function getAdmin( $username = '',  $password2 =''){
       return $this->db->query("SELECT * FROM m_admin WHERE username = '".$username."' AND password = '$password2'");
    }

    public function getSiswa($id = ''){
        return $this->db->query("SELECT * FROM m_siswa WHERE id = '".$id."'");
    }

    public function getGuru($id){
        return $this->db->query("SELECT nama FROM m_guru WHERE id = '".$id."'");
    }

    public function dynamicTable($table){
        return $this->db->table($table)->get();
    }

    public function waktu_sql(){
        return $this->db->query("SELECT NOW() AS waktu")->getRowObject();
    }

    public function updateSiswa($p){
        return $this->db->query("UPDATE m_siswa SET nama = '" . bersih($p, "nama") . "', nim = '" . bersih($p, "nim") . "', jurusan = '" . bersih($p, "jurusan") . "', tgl_lahir = '" . bersih($p, "tgl_lahir") . "'	WHERE id = '" . bersih($p, "id") . "'");
    }

    public function getSiswaSelect($p){
        // return $this->db->query("SELECT id from m_siswa where nim='" . bersih($p, "nim") . "' and nama = '" . bersih($p, "nama") . "' and tgl_lahir = '" . bersih($p, "tgl_lahir") . "'");
        return $this->db->query("SELECT id from m_siswa where nim='" . bersih($p, "nim") . "' ");
    }

    public function insertSiswa($p){
        return $this->db->query("INSERT INTO m_siswa (nama,nim,jurusan,tgl_lahir) VALUES ('" . bersih($p, "nama") . "', '" . bersih($p, "nim") . "', '" . bersih($p, "jurusan") . "', '" . bersih($p, "tgl_lahir") . "')");
    }

    public function deleteSiswa($uri4){
        return $this->db->query("DELETE FROM m_siswa WHERE id = '" . $uri4 . "'");
    }

    public function deleteSiswaAdmin($uri4){
        return $this->db->query("DELETE FROM m_admin WHERE level = 'siswa' AND kon_id = '" . $uri4 . "'");
    }

    public function getAdminSelect($nim){
        return $this->db->query("SELECT id FROM m_admin WHERE username = '" . $nim . "' AND level = 'siswa'");
    }

    public function insertAdmin($nim,$id){
        return $this->db->query("INSERT INTO m_admin (username,password,level,kon_id) VALUES ('" . $nim . "', md5('" . $nim . "'), 'siswa', '" . $id . "')");
    }

    public function updateAdmin($nim,$id){
        return $this->db->query("UPDATE m_admin SET password = md5('" . $nim . "'), username='" . $nim . "' WHERE level = 'siswa' AND kon_id = '" . $id . "'");
    }

    public function datatabelSiswa($search = ''){
        return $this->db->query("SELECT id FROM m_siswa a WHERE a.nama LIKE '%" . $search . "%'");
    }

    public function datatabelSiswaLength($search = '', $start = '',$length = '',$urutan = 'DESC', $coloumn = 0){
        if($coloumn == 1){
            $coloumns = 'nama';
        }elseif($coloumn == 2){
            $coloumns = 'nim';
        }elseif($coloumn == 2){
            $coloumns = 'tgl_lahir';
        }else{
            $coloumns = 'id';
        }
        return $this->db->query("SELECT a.*,
        (SELECT COUNT(id) FROM m_admin WHERE level = 'siswa' AND kon_id = a.id) AS ada
        FROM m_siswa a
        WHERE a.nama LIKE '%" . $search. "%' OR a.nim LIKE '%" . $search. "%' ORDER BY a." . $coloumns . "  " . $urutan . " LIMIT " . $start . ", " . $length . "");
    }


    // SOAL

    public function updateSoal($data = [],$id = ''){
        return $this->builder('m_soal')->where('id',$id)->set($data)->update();
    }

    public function insertSoal($data){
        return $this->builder('m_soal')->insert($data);
    }

    public function updateSoals($data = [],$id = ''){
        return $this->builder('m_soal')->where('id',$id)->set($data)->update();
    }
}
