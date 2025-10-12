<?php

use App\Models\AdmModel;
use CodeIgniter\HTTP\URI;

function gen_menu()
{
    $uri = new URI();
    $sess_level = session('userData.admin_level');
    $url = $uri->getSegments(2);

    $menu = array();

    if ($sess_level == "guru") {
        $menu = array(
            array("icon" => "dashboard", "url" => "", "text" => "Dashboard"),
			array("icon" => "user", "url" => "m_siswa", "text" => "Peserta"),
            array("icon" => "floppy-save", "url" => "m_soal", "text" => "Soal"),
            array("icon" => "paperclip", "url" => "m_ujian", "text" => "Ujian"),
            array("icon" => "gift", "url" => "h_ujian", "text" => "Hasil Ujian"),
            array("icon" => "download-alt", "url" => "h_ujian_peserta", "text" => "Hasil Peserta"),
        );
    } else if ($sess_level == "siswa") {
        $menu = array(
            array("icon" => "dashboard", "url" => "", "text" => "Dashboard"),
            array("icon" => "file", "url" => "ikuti_ujian", "text" => "Ikut Ujian"),
        );
    } else if ($sess_level == "admin") {
        $menu = array(
            array("icon" => "dashboard", "url" => "", "text" => "Dashboard"),
            array("icon" => "user", "url" => "m_siswa", "text" => "Peserta"),
            array("icon" => "bookmark", "url" => "m_guru", "text" => "HRD"),
            array("icon" => "registration-mark", "url" => "m_mapel", "text" => "Kategori Soal"),
            array("icon" => "open-file", "url" => "m_akses_soal", "text" => "Akses Soal"),
            array("icon" => "floppy-save", "url" => "m_soal", "text" => "Soal"),
            array("icon" => "gift", "url" => "h_ujian", "text" => "Hasil Ujian"),
            array("icon" => "download-alt ", "url" => "h_ujian_peserta", "text" => "Hasil Peserta"),
        );
    } else {
        $menu = array(
            array("icon" => "dashboard", "url" => "", "text" => "Dashboard")
        );
        if ($url == "ikut_ujian") {
            $menu = null;
        }
    }

    if ($menu != null) {
        echo '
		<div class="container" style="margin-top: 70px">

		<div class="col-lg-12 row">
		  <div class="panel panel-default">
		    <div class="panel-body">';

        foreach ($menu as $m) {
            if ($url == $m['url']) {
                echo '<a href="' . base_url() . 'adm/' . $m['url'] . '" class="btn btn-sq btn-warning"><i class="glyphicon glyphicon-' . $m['icon'] . ' g3x"></i><br><br/>' . $m['text'] . ' </a>';
            } else {
                echo '<a href="' . base_url() . 'adm/' . $m['url'] . '" class="btn btn-sq btn-primary"><i class="glyphicon glyphicon-' . $m['icon'] . ' g3x"></i><br><br/>' . $m['text'] . ' </a>';
            }
        }

        echo '</div>
		  </div>
		</div>';
    }

    function cmb_dinamis($name, $table, $field, $pk, $selected = null, $extra = null)
    {
        $ci = new AdmModel();
        $cmb = "<select name='$name' class='form-control' $extra>";
        $data = $ci->dynamicTable($table)->getResult();
        foreach ($data as $d) {
            $cmb .= "<option value='" . $d->$pk . "'";
            $cmb .= $selected == $d->$pk ? " selected='selected'" : '';
            $cmb .= ">" .  strtoupper($d->$field) . "</option>";
        }
        $cmb .= "</select>";
        return $cmb;
    }

    function select2_dinamis($name, $table, $field, $placeholder)
    {
        $ci = new AdmModel();
        $select2 = '<select name="' . $name . '" class="form-control select2 select2-hidden-accessible" multiple="" 
               data-placeholder="' . $placeholder . '" style="width: 100%;" tabindex="-1" aria-hidden="true">';
        $data = $ci->dynamicTable($table)->getResult();
        foreach ($data as $row) {
            $select2 .= ' <option>' . $row->$field . '</option>';
        }
        $select2 .= '</select>';
        return $select2;
    }
}


function tjs($tgl, $tipe)
{
	if ($tgl != "0000-00-00 00:00:00") {
		$pc_satu	= explode(" ", $tgl);
		if (count($pc_satu) < 2) {
			$tgl1		= $pc_satu[0];
			$jam1		= "";
		} else {
			$jam1		= $pc_satu[1];
			$tgl1		= $pc_satu[0];
		}

		$pc_dua		= explode("-", $tgl1);
		$tgl		= $pc_dua[2];
		$bln		= $pc_dua[1];
		$thn		= $pc_dua[0];

		$bln_pendek		= array("Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des");
		$bln_panjang	= array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

		$bln_angka		= intval($bln) - 1;

		if ($tipe == "l") {
			$bln_txt = $bln_panjang[$bln_angka];
		} else if ($tipe == "s") {
			$bln_txt = $bln_pendek[$bln_angka];
		}

		return $tgl . " " . $bln_txt . " " . $thn . "  " . $jam1;
	} else {
		return "Tgl Salah";
	}
}

function handlingUri($segment){
    $uri = new URI();
    $url = $uri->getSegments($segment);

    return $url;
}

function bersih($data, $pil) {
	//return mysql_real_escape_string 
	return $data->$pil;
}

function j($data) {
	header('Content-Type: application/json');
	echo json_encode($data);
}

function waktu_sql(){
    $ci = new AdmModel();
    return $ci->waktu_sql()->waktu;
  
}

function tampil_media($file,$width="320px",$height="240px") {
	$ret = '';

	$pc_file = explode(".", $file);
	$eks = end($pc_file);

	$eks_video = array("mp4","flv","mpeg");
	$eks_audio = array("mp3","acc");
	$eks_image = array("jpeg","jpg","gif","bmp","png");


	if (!in_array($eks, $eks_video) && !in_array($eks, $eks_audio) && !in_array($eks, $eks_image)) {
		$ret .= '';
	} else {
		if (in_array($eks, $eks_video)) {
			if (is_file("./".$file)) {
				$ret .= '<p><video width="'.$width.'" height="'.$height.'" controls>
				  <source src="'.base_url().$file.'" type="video/mp4">
				  <source src="'.base_url().$file.'" type="application/octet-stream">Browser tidak support</video></p>';
			} else {
				$ret .= '';
			}
		} 

		if (in_array($eks, $eks_audio)) {
			if (is_file("./".$file)) {
				$ret .= '<p><audio width="'.$width.'" height="'.$height.'" controls>
				<source src="'.base_url().$file.'" type="audio/mpeg">
				<source src="'.base_url().$file.'" type="audio/wav">Browser tidak support</audio></p>';
			} else {
				$ret .= '';
			}
		}

		if (in_array($eks, $eks_image)) {
			if (is_file("./".$file)) {
				$ret .= '<div class="gambar"><img src="'.base_url().$file.'" style="width: '.$width.'; height: '.$height.'; display: inline; float: left"></div>';
			} else {
				$ret .= '';
			}
		}
	}
	

	return $ret;
}

function obj_to_array($obj, $pilih) {
	$pilihpc	= explode(",", $pilih);
	$array 		= array(""=>"-");

	foreach ($obj as $o) {
		$xx = $pilihpc[0];
		$x = $o->$xx;
		$y = $pilihpc[1];

		$array[$x] = $o->$y; 
	}

	return $array;
}

function tambah_jam_sql($menit) {
	$str = "";
	if ($menit < 60) {
		$str = "00:".str_pad($menit, 2, "0", STR_PAD_LEFT).":00";
	} else if ($menit >= 60) {
		$mod = $menit % 60;
		$bg = floor($menit / 60);
		$str = str_pad($bg, 2, "0", STR_PAD_LEFT).":".str_pad($mod, 2, "0", STR_PAD_LEFT).":00";
	} 
	return $str;
}