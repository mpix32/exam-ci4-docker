<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriSoal extends Model
{
    public function kategoriSoal($data){
        return $this->builder('tr_guru_mapel')
                ->insert($data);
    }
}
