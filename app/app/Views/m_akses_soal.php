<div class="row col-md-12 ini_bodi">
  <div class="panel panel-info">
    <div class="panel-heading">Data Akses Soal
      <div class="tombol-kanan">
        <a class="btn btn-success btn-sm tombol-kanan" href="#" onclick="return m_akses_e();"><i class="glyphicon glyphicon-plus"></i> &nbsp;&nbsp;Tambah</a>        
      </div>
    </div>
    <div class="panel-body">
      <table class="table table-bordered" id="datatabel">
        <thead>
          <tr>
            <th width="5%">No</th>
            <th width="25%">HRD / Instruktur</th>
            <th width="13%">Kategori</th>
            <th width="35%">Aksi</th>
          </tr>
        </thead>

        <tbody></tbody>
      </table>
    
      </div>
    </div>
  </div>
</div>
                    
<div class="modal fade" id="m_akses" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 id="myModalLabel">Data Akses Soal</h4>
      </div>
      <div class="modal-body">
          <form name="f_akses" id="f_akses" onsubmit="return m_akses_s();">
            <input type="hidden" name="id" id="id" value="0">
              <table class="table table-form">
                <tr>
                    <td style="width: 25%">HRD</td>
                    <td style="width: 75%"><?php echo cmb_dinamis('id_guru', 'm_guru', 'nama','id')?></td>
                </tr>
                <tr>
                    <td style="width: 25%">Kategori Soal</td>
                    <td style="width: 75%"><?php echo cmb_dinamis('id_mapel', 'm_mapel', 'nama','id')?></td>
                </tr>
              </table>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary"><i class="fa fa-check"></i> Simpan</button>
        <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="fa fa-minus-circle"></i> Tutup</button>
      </div>
        </form>
    </div>
  </div>
</div>
