<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');

$routes->group('', ['filter' => 'AlreadyLoggedIn'], function ($routes) {
    $routes->get('/', 'Auth::index');
    $routes->post('adm/act_login', 'Auth::act_login');
});

$routes->group('adm', ['filter' => 'AuthCheck'], function ($routes) {
    $routes->get('/', 'AdmController::index');
    $routes->get('logout', 'Auth::logout');
    $routes->get('rubah_password', 'AdmController::rubah_password');
    
    $routes->group('m_siswa', static function ($routes) {
        $routes->get('/', 'AdmController::m_siswa');
        $routes->post('getSiswa', 'AdmController::getSiswaList');
        $routes->get('det/(:any)', 'AdmController::detailSiswa/$1');
        $routes->get('hapus/(:any)', 'AdmController::delSiswa/$1');
        $routes->get('user/(:any)', 'AdmController::activeSiswa/$1');
        $routes->get('user_reset/(:any)', 'AdmController::activeSiswa/$1');
        $routes->post('simpan', 'AdmController::addSiswa');
        $routes->get('import', 'AdmController::siswaImport');
    });

    $routes->group('m_guru', static function ($routes) {
        $routes->get('/', 'AdmGuruController::index');
        $routes->post('data', 'AdmGuruController::getListGuru');
        $routes->get('det/(:any)', 'AdmGuruController::getDetail/$1');
        $routes->post('simpan', 'AdmGuruController::simpanGuru');
        $routes->get('hapus/(:any)', 'AdmGuruController::delGuru/$1');
        $routes->get('ambil_matkul/(:any)', 'AdmGuruController::ambilMatkul/$1');
        $routes->post('simpan_matkul', 'AdmGuruController::simpanMatkul');
        $routes->get('user_reset/(:any)', 'AdmGuruController::resetPass/$1');
        $routes->get('import', 'AdmGuruController::ImportGuru');
        $routes->get('user/(:any)', 'AdmGuruController::userAktif/$1');
    });

    $routes->group('m_mapel', static function ($routes) {
        $routes->get('/', 'AdmMapelController::index');
        $routes->post('data', 'AdmMapelController::getListMapel');
        $routes->get('det/(:any)', 'AdmMapelController::detMapel/$1');
        $routes->post('simpan', 'AdmMapelController::simpanMapel');
        $routes->get('hapus/(:any)', 'AdmMapelController::delMapel/$1');
    });

    $routes->group('m_akses_soal', static function ($routes) {
        $routes->get('/', 'AdmKategoriSoalController::index');
        $routes->post('data', 'AdmKategoriSoalController::getListSoal');
        $routes->get('det/(:any)', 'AdmKategoriSoalController::detSoal/$1');
        $routes->post('simpan', 'AdmKategoriSoalController::simpanSoal');
        $routes->get('hapus', 'AdmKategoriSoalController::delSoal');
    });

    $routes->group('m_soal', static function ($routes) {
        $routes->get('/', 'AdmSoalController::index');
        $routes->post('data', 'AdmSoalController::getListSoal');
        $routes->get('edit/(:any)', 'AdmSoalController::editSoal/$1');
        $routes->post('simpan', 'AdmSoalController::simpanSoal');
        $routes->get('pilih_mapel/(:any)', 'AdmSoalController::pilihMapel/$1');
        $routes->get('import', 'AdmSoalController::importSoal');
    });

    $routes->group('m_ujian', static function ($routes) {
        $routes->get('/', 'AdmUjianController::index');
        $routes->get('det/(:any)', 'AdmUjianController::detUjian/$1');
        $routes->get('jumlah_soal/(:any)', 'AdmUjianController::jmlSoal/$1');
        $routes->get('refresh_token/(:any)', 'AdmUjianController::refreshToken/$1');
        $routes->post('data', 'AdmUjianController::getListUjian');
        $routes->post('simpan', 'AdmUjianController::simpanUjian');
        $routes->get('hapus/(:any)', 'AdmUjianController::hapusUjian');
    });

    $routes->group('h_ujian', static function ($routes) {
        $routes->get('/', 'AdmUjianController::hasilUjianIndex');
        $routes->post('data', 'AdmUjianController::hasilUjian');
        $routes->get('det/(:any)', 'AdmUjianController::detHasilUjian/$1');
        $routes->post('data_det/(:any)', 'AdmUjianController::dataDetailUjian/$1');
        $routes->get('batalkan_ujian/(:any)/(:any)', 'AdmUjianController::batalUjian/$1/$2');
    });


    $routes->get('hasil_ujian_cetak/(:any)', 'AdmUjianController::hasilUjianCetak/$1');
    $routes->get('h_ujian_peserta', 'AdmUjianController::hasilUjianPeserta');
    $routes->post('h_ujian_peserta/data', 'AdmUjianController::listUjianPeserta');
    $routes->get('h_ujian_peserta_cetak/(:any)', 'AdmUjianController::ujianPesertaCetak/$1');
    $routes->get('ikuti_ujian', 'AdmUjianController::ikutiUjian');

    $routes->group('ikut_ujian', static function ($routes) {
        $routes->get('token/(:any)', 'AdmUjianController::ikutUjianToken/$1');
        $routes->get('_/(:any)', 'AdmUjianController::prosesIkutUjian/$1');
        $routes->post('simpan_satu/(:any)', 'AdmUjianController::simpanSatu/$1');
        $routes->post('simpan_akhir/(:any)', 'AdmUjianController::simpanAkhir/$1');
    });

    $routes->get('sudah_selesai_ujian/(:any)', 'AdmUjianController::selesaiUjian/$1');
    $routes->get('get_servertime', 'AdmUjianController::get_servertime');

});

$routes->group('import', ['filter' => 'AuthCheck'], function ($routes) {
    $routes->post('soal', 'ImportController::soal');
});