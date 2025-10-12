<?php

use CodeIgniter\HTTP\URI;

$uri = new URI;
$uri4 = $uri->getSegments(4);

$session = \Config\Services::session();
?>

<div class="row col-md-12 ini_bodi">
  <div class="panel panel-info">
    <div class="panel-heading"><b>Data Soal</b>
      <div class="tombol-kanan">
        <a class="btn btn-success btn-sm" href="<?php echo base_url(); ?>adm/m_soal/edit/0"><i class="glyphicon glyphicon-plus" style="margin-left: 0px; color: #fff"></i> &nbsp;&nbsp;Tambah Data</a>        
        <!-- <a class="btn btn-warning btn-sm tombol-kanan" href="<?php echo base_url(); ?>/upload/format_soal_download.xlsx" ><i class="glyphicon glyphicon-download"></i> &nbsp;&nbsp;Download Format Import</a> -->
        <!-- <a class="btn btn-info btn-sm tombol-kanan" href="<?php echo base_url(); ?>/adm/m_soal/import" ><i class="glyphicon glyphicon-upload"></i> &nbsp;&nbsp;Import</a> -->
      </div>
    </div>
    <div class="panel-body">
        
        <?php echo $session->getFlashdata('k'); ?>
        
        <table class="table table-bordered display" id="datatabel">
          <thead>
            <tr>
              <td width="8%">No</td>
              <td width="10%">ID Soal</td>
              <td width="50%">Soal</td>
              <td width="15%">Materi soal/Instruktur</td>
              <td width="15%">Analisa</td>
              <td width="15%">Aksi</td>
            </tr>
          </thead>

          <tbody>

          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</div>
