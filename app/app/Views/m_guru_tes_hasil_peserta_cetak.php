<!DOCTYPE html>
<html>
<head>
  <title>Laporan Hasil Ujian</title>
  <link href='<?php echo base_url(); ?>___/css/style_print.css' rel='stylesheet' media='' type='text/css'/>
  <style type="text/css">
        table {
			border: 1px solid #232222;
		}
	</style>
</head>
<body>

<table >
	<tr>
		<td style="border : 1px solid #232222;" colspan="8"><h2 style="text-align: center;">RESUME HASIL TEST TULIS</h2></td>
	</tr>
	<tr>
		<td colspan="2" style="width: 30px; height:30px">Diisi oleh HRD</td>
		<td colspan="6">: </td>
	</tr>
	<tr>
		<td colspan="2" style="width: 30px; height:30px"> 1. Nama Peserta</td>
		<td colspan="6" style="width: 200px">: <?php echo $detil_tes->nama; ?></td>
	</tr>
	<tr>
		<td colspan="2" style="width: 30px; height:30px"> 2. Tanggal Lahir</td>
		<td colspan="6" style="width: 200px">: <?php echo tjs($detil_tes->tgl_lahir,'s'); ?></td>
	</tr>
	<tr>
		<td colspan="2" style="width: 30px;  height:30px"> 3. Untuk Divisi/Bagian</td>
		<td colspan="6" style="width: 200px">: </td>
	</tr>
	<tr>
		<td colspan="2" style="width: 30px;  height:30px"> 4. Tanggal Tes</td>
		<td colspan="6" style="width: 200px">: <?php echo tjs($detil_tes->tgl_selesai,'s'); ?></td>
	</tr>
	<tr>
		<td colspan="2" style="width: 30px;  height:30px"> 5. Hasil Test Wawancara HRD</td>
		<td colspan="6" style="width: 200px">: </td>
	</tr>
	<tr>
		<td colspan="8"><br/></td>
	</tr>
	<tr style="border : 1px solid #232222;">
		<td  colspan="2" style="width: 30px;  height:30px">Diisi oleh User</td>
		<td colspan="6">: </td>
	</tr>
	<tr>
		<td  colspan="2" style="width: 30px;  height:30px">1. Hasil Tes Wawancara User</td>
		<td colspan="6">: </td>
	<tr>
	<tr>
		<td  colspan="2" style="width: 30px;  height:30px">2. Hasil Tes Kompetensi</td>
		<!-- <td colspan="6">: </td> -->
		<td>: <input type="checkbox" /> Skill Lapangan</td>
		<td>: <input type="checkbox" /> Listrik </td>
		<td>: <input type="checkbox" /> Akutansi</td>
		<td>: <input type="checkbox" /> Komputer</td>
	<tr>
	<!-- <tr>
		<td  colspan="2" style="width: 30px;  height:30px; text-align: justify; text-indent: 0.5in;">  - Produksi</td>
		<td colspan="6">: </td>
	<tr>
	<tr>
		<td  colspan="2" style="width: 30px;  height:30px; text-align: justify; text-indent: 0.5in;">  - Listrik</td>
		<td colspan="6">: </td>
	<tr>
	<tr>
		<td colspan="2" style="width: 30px;  height:30px; text-align: justify; text-indent: 0.5in;">  - Akutansi</td>
		<td colspan="6">: </td>
	<tr>
	<tr>
		<td colspan="2" style="width: 30px;  height:30px; text-align: justify; text-indent: 0.5in;">  - Komputer</td>
		<td colspan="6">: </td>
	<tr> -->
	
	<tr>
		<td colspan="2" style="width: 30px;  height:30px; ">3. Status</td>
		<td>: <input type="checkbox" /> Diterima</td>
		<td>: <input type="checkbox" /> Ditolak </td>
		<td>: <input type="checkbox" /> Pending</td>
		<td> </td>
		<td></td>
		<td> </td>
	<tr>
	
	<tr>
		<td  colspan="2" style="width: 30px;  height:30px">4. Tanggal Masuk</td>
		<td colspan="6">: </td>
	<tr>
	
	<tr>
		<td  colspan="2" style="width: 30px;  height:30px">5. Grade</td>
		<td colspan="6">: </td>
	<tr>
	
	<tr>
		<td  colspan="2" style="width: 30px;  height:30px">6. Jabatan</td>
		<td colspan="6">: </td>
	<tr>
	
	<tr>
		<td  colspan="2" style="width: 30px;  height:30px">7. Hierarki</td>
		<td colspan="6">: </td>
	<tr>
	
	<tr>
		<td  colspan="2" style="width: 30px;  height:30px">8. Grup Kerja</td>
		<td>: <input type="checkbox" /> 6 hari kerja</td>
		<td>: <input type="checkbox" /> 5 hari kerja </td>
		<td></td>
	<tr>
	
	<tr>
		<td colspan="8"><br/></td>
	</tr>
	
	<tr>
		<td colspan="8">
			<table class="table-bordered">
			  <thead>
				<tr>
				  <th width="5%">No</th>
				  <th width="25%">Soal Ujian</th>
				  <th width="10%">Nama Ujian</th>
				  <th width="10%">Jumlah Benar</th>
				  <th width="10%">Nilai</th>
				</tr>
			  </thead>

			  <tbody>
				<?php 
				  if (!empty($hasil)) {
					$no = 1;
					$tot = 0;
					$b=0;
					foreach ($hasil as $d) {
					  echo '<tr>
							<td class="ctr">'.$no.'</td>
							<td>'.$d->nama.'</td>
							<td>'.$d->nama_ujian.'</td>
							<td class="ctr">'.$d->jml_benar.'</td>
							<td class="ctr">'.$d->nilai_bobot.'</td>
							</tr>
							';
						$no++;
						$b = $d->jum_soal;
						$tot = $tot + $d->nilai_bobot;
					}
					
						$lobang = $tot/$b;
					
					echo '
						<td class="ctr" colspan="4">Total</td>
						<td class="ctr">'.$lobang.'</td>';
				  } else {
					echo '<tr><td colspan="4">Belum ada data</td></tr>';
				  }
				?>
			  </tbody>
			</table>
		</td>
	<tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td><span style="align : right">Cikarang, &nbsp &nbsp &nbsp &nbsp 20.....</span></td>
		<td></td>
	</tr>
	<tr>
		<td>
			
		</td>
		<td>
			<div>
				<span style="align : left" >Pewawancara I</span>
			</div>
		</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td>
			<div>
				<span style="align : left;" >Pewawancara II</span>
			</div>
		</td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td>
			
		</td>
		<td>
			<div>
				<span style="align : left" >Manager HRD</span>
			</div>
		</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td>
			<div>
				<span style="align : left" >Departement Terkait</span>
			</div>
		</td>
		<td></td>
	<tr>
</table>


</body>
</html>
