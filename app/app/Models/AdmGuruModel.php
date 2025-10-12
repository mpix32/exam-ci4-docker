<?php

namespace App\Models;

use CodeIgniter\Model;

class AdmGuruModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'm_guru';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = false;
    protected $allowedFields    = [];

    public function getListNumber($search = '')
    {
        return $this->db->query("SELECT id FROM m_guru a WHERE a.nama LIKE '%" . $search . "%'");
    }

    public function getList($search = '', $start='', $length='')
    {
        return $this->db->query("SELECT a.*,
                    (SELECT COUNT(id) FROM m_admin WHERE level = 'guru' AND kon_id = a.id) AS ada
                    FROM m_guru a
                    WHERE a.nama LIKE '%" . $search . "%' OR a.nip LIKE '%" . $search . "%' ORDER BY a.id DESC LIMIT " . $start . ", " . $length . "");
    }

    public function getDetail($id){
        return $this->db->query("SELECT * FROM m_guru WHERE id = '$id'");
    }

    public function updateGuru($p){
        return $this->db->query("UPDATE m_guru SET nama = '" . bersih($p, "nama") . "', nip = '" . bersih($p, "nip") . "' WHERE id = '" . bersih($p, "id") . "'");
    }

    public function insertGuru($p){
        return $this->db->query("INSERT INTO m_guru (nip,nama) VALUES ('" . bersih($p, "nip") . "', '" . bersih($p, "nama") . "')");
    }

    public function delGuru($uri4){
                $this->db->query("DELETE FROM m_guru WHERE id = '".$uri4."'");
        return $this->db->query("DELETE FROM m_admin WHERE level = 'guru' AND kon_id = '".$uri4."'");
    }

    public function ambilMatkul($uri4){
        return $this->db->query("SELECT m_mapel.*,
										(SELECT COUNT(id) FROM tr_guru_mapel WHERE id_guru = ".$uri4." AND id_mapel = m_mapel.id) AS ok
										FROM m_mapel
										")->getResult();
    }

    public function listMapel(){
        return $this->db->query("SELECT id FROM m_mapel ORDER BY id ASC")->getResult();
    }

    public function checkMapel($id_mhs,$id){
        return $this->db->query("SELECT id FROM tr_guru_mapel WHERE  id_guru = '".$id_mhs."' AND id_mapel = '".$id."'")->getNumRows();
    }

    public function insertMapel($id_mhs,$id) {
       return $this->db->query("INSERT INTO tr_guru_mapel VALUES (null, '" . $id_mhs . "', '" . $id . "')");
    }

    public function updateMapel($p_sub,$id_mhs,$id) {
        return $this->db->query("UPDATE tr_guru_mapel SET id_mapel = '" . $p_sub . "' WHERE id_guru = '" . $id_mhs . "' AND id_mapel = '" . $id . "'");
    }

    public function delMapel($id_mhs,$id){
        return $this->db->query("DELETE FROM tr_guru_mapel WHERE id_guru = '" . $id_mhs . "' AND id_mapel = '" . $id . "'");
    }

    public function resetPass($nip,$id){
        return $this->db->query("UPDATE m_admin SET password = md5('".$nip."'), username ='".$nip."' WHERE level = 'guru' AND kon_id = '".$id."'");
    }

    // Dates

}
