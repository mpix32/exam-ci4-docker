-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 26, 2024 at 09:51 AM
-- Server version: 10.1.28-MariaDB
-- PHP Version: 5.6.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hrd_sentralindo`
--

-- --------------------------------------------------------

--
-- Table structure for table `m_admin`
--

CREATE TABLE `m_admin` (
  `id` int(6) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `level` enum('admin','guru','siswa') NOT NULL,
  `kon_id` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_admin`
--

INSERT INTO `m_admin` (`id`, `username`, `password`, `level`, `kon_id`) VALUES
(1, 'admin', 'b3d20fb0d4704409803bc2360c11616b', 'admin', 0);

-- --------------------------------------------------------

--
-- Table structure for table `m_guru`
--

CREATE TABLE `m_guru` (
  `id` int(6) NOT NULL,
  `nip` varchar(30) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_guru`
--

INSERT INTO `m_guru` (`id`, `nip`, `nama`) VALUES
(1, '1053', 'BAGUS');

--
-- Triggers `m_guru`
--
DELIMITER $$
CREATE TRIGGER `hapus_guru` AFTER DELETE ON `m_guru` FOR EACH ROW BEGIN
DELETE FROM m_soal WHERE m_soal.id_guru = OLD.id;
DELETE FROM m_admin WHERE m_admin.level = 'guru' AND m_admin.kon_id = OLD.id;
DELETE FROM tr_guru_mapel WHERE tr_guru_mapel.id_guru = OLD.id;
DELETE FROM tr_guru_tes WHERE tr_guru_tes.id_guru = OLD.id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `m_mapel`
--

CREATE TABLE `m_mapel` (
  `id` int(6) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_mapel`
--

INSERT INTO `m_mapel` (`id`, `nama`) VALUES
(1, 'TES KETELITIAN'),
(2, 'TES DERET BILANGAN DAN HURUF'),
(3, 'TES ANALOGI'),
(4, 'TES PERBENDAHARAAN KATA'),
(5, 'TES PENALARAN LOGIKA DAN ANALITIS'),
(6, 'TES SINONIM DAN ANTONIM');

--
-- Triggers `m_mapel`
--
DELIMITER $$
CREATE TRIGGER `hapus_mapel` AFTER DELETE ON `m_mapel` FOR EACH ROW BEGIN
DELETE FROM m_soal WHERE m_soal.id_mapel = OLD.id;
DELETE FROM tr_guru_mapel WHERE tr_guru_mapel.id_mapel = OLD.id;
DELETE FROM tr_guru_tes WHERE tr_guru_tes.id_mapel = OLD.id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `m_siswa`
--

CREATE TABLE `m_siswa` (
  `id` int(6) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nim` varchar(50) NOT NULL,
  `jurusan` varchar(50) NOT NULL,
  `tgl_lahir` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_siswa`
--

INSERT INTO `m_siswa` (`id`, `nama`, `nim`, `jurusan`, `tgl_lahir`) VALUES
(1, 'Setiaji', '01', 'Corrugator', '1996-06-22');

--
-- Triggers `m_siswa`
--
DELIMITER $$
CREATE TRIGGER `hapus_siswa` AFTER DELETE ON `m_siswa` FOR EACH ROW BEGIN
DELETE FROM tr_ikut_ujian WHERE tr_ikut_ujian.id_user = OLD.id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `m_soal`
--

CREATE TABLE `m_soal` (
  `id` int(6) NOT NULL,
  `id_guru` int(6) NOT NULL,
  `id_mapel` int(6) NOT NULL,
  `bobot` int(2) NOT NULL,
  `file` varchar(150) DEFAULT NULL,
  `tipe_file` varchar(50) DEFAULT NULL,
  `soal` longtext NOT NULL,
  `opsi_a` longtext NOT NULL,
  `opsi_b` longtext NOT NULL,
  `opsi_c` longtext NOT NULL,
  `opsi_d` longtext NOT NULL,
  `opsi_e` longtext NOT NULL,
  `jawaban` varchar(5) NOT NULL,
  `tgl_input` datetime NOT NULL,
  `jml_benar` int(6) NOT NULL,
  `jml_salah` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_soal`
--

INSERT INTO `m_soal` (`id`, `id_guru`, `id_mapel`, `bobot`, `file`, `tipe_file`, `soal`, `opsi_a`, `opsi_b`, `opsi_c`, `opsi_d`, `opsi_e`, `jawaban`, `tgl_input`, `jml_benar`, `jml_salah`) VALUES
(1, 1, 1, 1, NULL, NULL, '<p>Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :</p>\r\n', '#####<p>Antipati</p>\r\n', '#####<p>Antusias</p>\r\n', '#####<p>Antangin</p>\r\n', '#####<p>Antenna</p>\r\n', '#####', 'C', '0000-00-00 00:00:00', 1141, 92),
(2, 1, 1, 1, NULL, NULL, '<p>Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :</p>\r\n', '#####<p>Cangkang</p>\r\n', '#####<p>Cakalang</p>\r\n', '#####<p>Cakram</p>\r\n', '#####<p>Cakrawala</p>\r\n', '#####', 'B', '0000-00-00 00:00:00', 1055, 182),
(3, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Kelenteng', '#####Kelereng', '#####Kelompok', '#####Kelengkeng', '#####', 'D', '2019-03-14 11:09:07', 965, 277),
(4, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Sangkut', '#####Sanggah', '#####Sangka', '#####Sanggurdi', '#####', 'B', '2019-03-14 11:09:07', 1043, 216),
(5, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Gunung', '#####Gunting', '#####Gunjing', '#####Gundukan', '#####', 'D', '2019-03-14 11:09:07', 1102, 130),
(6, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Centong', '#####Centeng', '#####Cengkol', '#####Cengeng', '#####', 'D', '2019-03-14 11:09:07', 1107, 147),
(7, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Karma', '#####Karamel', '#####Karena', '#####Kantor', '#####', 'D', '2019-03-14 11:09:07', 798, 458),
(8, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Sekilas', '#####Seksama', '#####Suaka', '#####Sakit', '#####', 'D', '2019-03-14 11:09:07', 1020, 222),
(9, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Sumber', '#####Sumpah', '#####Sumpel', '#####Sumarah', '#####', 'D', '2019-03-14 11:09:07', 1035, 211),
(10, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Khitan', '#####Khianat', '#####Khiamat', '#####Khazanah', '#####', 'D', '2019-03-14 11:09:07', 844, 403),
(11, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Limbung', '#####Lampir', '#####Limpung', '#####Limpah', '#####', 'B', '2019-03-14 11:09:07', 989, 256),
(12, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Lindung', '#####Lindas', '#####Lintang', '#####Lingkar', '#####', 'B', '2019-03-14 11:09:07', 1063, 177),
(13, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Crough', '#####Crow', '#####Crout', '#####Croven', '#####', 'A', '2019-03-14 11:09:07', 999, 252),
(14, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Drible', '#####Drifter', '#####Drimble', '#####Drimer', '#####', 'A', '2019-03-14 11:09:07', 1130, 113),
(15, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Terima', '#####Terigu', '#####Teritik', '#####Teripang', '#####', 'B', '2019-03-14 11:09:07', 1055, 180),
(16, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Ngelotok', '#####Ngeloyor', '#####Ngengap', '#####Ngengat', '#####', 'A', '2019-03-14 11:09:07', 970, 278),
(17, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Jawab', '#####Jawara', '#####Jauhari', '#####Jawawut', '#####', 'C', '2019-03-14 11:09:07', 947, 312),
(18, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Balairung', '#####Balada', '#####Bakal', '#####Bakat', '#####', 'C', '2019-03-14 11:09:07', 903, 346),
(19, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Gantang', '#####Gangsir', '#####Ganjur', '#####Ganteng', '#####', 'B', '2019-03-14 11:09:07', 1009, 248),
(20, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Gabung', '#####Gadai', '#####Gagah', '#####Gapai', '#####', 'A', '2019-03-14 11:09:07', 1085, 170),
(21, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Kebaya', '#####Kebun', '#####Keburu', '#####Kebutuhan', '#####', 'A', '2019-03-14 11:09:07', 1123, 127),
(22, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Letus', '#####Lentur', '#####Lentera', '#####Lenting', '#####', 'C', '2019-03-14 11:09:07', 1100, 148),
(23, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Melik', '#####Melek', '#####Melati', '#####Lambung', '#####', 'D', '2019-03-14 11:09:07', 934, 323),
(24, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Sengat', '#####Sandung', '#####Sendiri', '#####Sendat', '#####', 'B', '2019-03-14 11:09:07', 877, 371),
(25, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Karyawan', '#####Karunia', '#####Karbohidrat', '#####Kartunis', '#####', 'C', '2019-03-14 11:09:07', 1096, 126),
(26, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Jengkal', '#####Jenggot', '#####Jengkelit', '#####Jenguk', '#####', 'B', '2019-03-14 11:09:07', 967, 276),
(27, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Juragan', '#####Jurang', '#####Jurnalis', '#####Jungkir', '#####', 'D', '2019-03-14 11:09:07', 623, 614),
(28, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Justru', '#####Jurang', '#####Jumawa', '#####Jurnal', '#####', 'C', '2019-03-14 11:09:07', 1044, 188),
(29, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Kampung', '#####Kemudi', '#####Kemplang', '#####Kemuning', '#####', 'A', '2019-03-14 11:09:07', 913, 312),
(30, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Balik', '#####Baling', '#####Balok', '#####Balon', '#####', 'A', '2019-03-14 11:09:07', 1071, 184),
(31, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Basmi', '#####Basket', '#####Baskom', '#####Basuh', '#####', 'B', '2019-03-14 11:09:07', 1050, 194),
(32, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Angkuh', '#####Angklung', '#####Angkut', '#####Angsur', '#####', 'B', '2019-03-14 11:09:07', 992, 263),
(33, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Dampar', '#####Damping', '#####Dampit', '#####Dampak', '#####', 'D', '2019-03-14 11:09:07', 1092, 146),
(34, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Cambang', '#####Cambuk', '#####Campak', '#####Campur', '#####', 'A', '2019-03-14 11:09:07', 1104, 161),
(35, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Cinta', '#####Cincin', '#####Cindera', '#####Citra', '#####', 'B', '2019-03-14 11:09:07', 963, 273),
(36, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Hindar', '#####Hingga', '#####Hinggap', '#####Hingar', '#####', 'A', '2019-03-14 11:09:07', 1091, 142),
(37, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Kuartal', '#####Kuali', '#####Kualitet', '#####Kuasa', '#####', 'B', '2019-03-14 11:09:07', 977, 267),
(38, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Ongkos', '#####Onderdil', '#####Onggok', '#####Ongkang', '#####', 'B', '2019-03-14 11:09:07', 1103, 147),
(39, 1, 1, 1, NULL, NULL, 'Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :', '#####Mawas', '#####Maung', '#####Mantra', '#####Mayang', '#####', 'C', '2019-03-14 11:09:07', 1122, 127),
(40, 1, 1, 1, NULL, NULL, '<p>Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :</p>\r\n', '#####<p>Menggala</p>\r\n', '#####<p>Mengalah</p>\r\n', '#####<p>Mendung</p>\r\n', '#####<p>Menang</p>\r\n', '#####', 'D', '2019-03-14 11:09:07', 937, 324),
(41, 1, 6, 1, NULL, NULL, 'Sinonim dari PARAMETER', '#####Pasukan Cadangan', '#####Standar Ukuran', '#####Tidak Dapat Diukur', '#####Tidak Berukuran', '#####Kecepatan', 'B', '2019-03-14 14:12:15', 489, 171),
(42, 1, 6, 1, NULL, NULL, 'Sinonim dari KONTEMPORER', '#####Aneh', '#####Kuno', '#####Pada Masa Kini', '#####Abstrak', '#####Tidak Beraturan', 'C', '2019-03-14 14:12:15', 129, 511),
(43, 1, 6, 1, NULL, NULL, 'Sinonim dari KORELASI', '#####Identifikasi', '#####Gambaran', '#####Sublimasi', '#####Harapan', '#####Hubungan', 'E', '2019-03-14 14:12:15', 333, 314),
(44, 1, 6, 1, NULL, NULL, 'Sinonim dari TRANSENDENTAL', '#####Bergerak', '#####Berpindah', '#####Kesinambungan', '#####Abstrak', '#####Tembus Pandang', 'D', '2019-03-14 14:12:15', 44, 622),
(45, 1, 6, 1, NULL, NULL, 'Sinonim dari EKUILIBRIUM', '#####Kesempurnaan', '#####Keseimbangan', '#####Kesederhanaan', '#####Kesamaan', '#####Kesesatan', 'B', '2019-03-14 14:12:15', 269, 395),
(46, 1, 6, 1, NULL, NULL, 'Sinonim dari GENERIK', '#####Jenis', '#####Murah', '#####Obat', '#####Spesial', '#####Umum', 'E', '2019-03-14 14:12:15', 163, 518),
(47, 1, 6, 1, NULL, NULL, 'Sinonim dari LEGALITAS', '#####Keabsahan', '#####Masalah Hukum', '#####Tanda Setuju', '#####Tidak Sah', '#####Persetujuan', 'A', '2019-03-14 14:12:15', 242, 442),
(48, 1, 6, 1, NULL, NULL, 'Sinonim dari DIKOTOMI', '#####Dualitas', '#####Dua Kepala', '#####Kembar Dua', '#####Dua Kekuatan', '#####Dwi Fungsi', 'A', '2019-03-14 14:12:15', 179, 502),
(49, 1, 6, 1, NULL, NULL, 'Sinonim dari ANULIR', '#####Pemberatan', '#####Abolisi', '#####Regresi', '#####Penambahan', '#####Analisa', 'B', '2019-03-14 14:12:15', 49, 613),
(50, 1, 6, 1, NULL, NULL, 'Sinonim dari BENCANA', '#####Bantuan', '#####Lawan', '#####Bala', '#####Rapat Sekali', '#####Kawan', 'C', '2019-03-14 14:12:15', 398, 255),
(51, 1, 6, 1, NULL, NULL, 'Antonim dari AMATIR', '#####Palsu', '#####Canggih', '#####Ahli', '#####Partikelir', '#####Anasir', 'C', '2019-03-14 14:12:15', 465, 157),
(52, 1, 6, 1, NULL, NULL, 'Antonim dari LEGISLATIF', '#####Yudikatif', '#####Eksekutif', '#####Hukuman', '#####Undang-Undang', '#####Konstitusi', 'B', '2019-03-14 14:12:15', 142, 525),
(53, 1, 6, 1, NULL, NULL, 'Antonim dari KONSUMEN', '#####Pembagian', '#####Pembeli', '#####Penjual', '#####Pencari', '#####Penghasil', 'E', '2019-03-14 14:12:15', 137, 524),
(54, 1, 6, 1, NULL, NULL, 'Antonim dari OTOMATIS', '#####Semi', '#####Sederhana', '#####Canggih', '#####Modern', '#####Manual', 'E', '2019-03-14 14:12:15', 551, 90),
(55, 1, 6, 1, NULL, NULL, 'Antonim dari ABSURD', '#####Omong Kosong', '#####Pengecualian', '#####Tak Terpakai', '#####Masuk Akal', '#####Mustahil', 'D', '2019-03-14 14:12:15', 410, 260),
(56, 1, 6, 1, NULL, NULL, 'Antonim dari TIMPANG', '#####Benar', '#####Sama', '#####Sempurna', '#####Seimbang', '#####Sejajar', 'D', '2019-03-14 14:12:15', 302, 359),
(57, 1, 6, 1, NULL, NULL, 'Antonim dari PRODUSEN', '#####Pengguna', '#####Pembuat', '#####Pemakai', '#####Konsumen', '#####Penjaja', 'D', '2019-03-14 14:12:15', 425, 240),
(58, 1, 6, 1, NULL, NULL, 'Antonim dari PASCA', '#####Sesudah', '#####Pra', '#####Awal', '#####Purna', '#####Akhir', 'B', '2019-03-14 14:12:15', 224, 454),
(59, 1, 6, 1, NULL, NULL, 'Antonim dari KOLEKTIF', '#####Sendiri', '#####Personal', '#####Individual', '#####Selektif', '#####Komunal', 'C', '2019-03-14 14:12:15', 150, 511),
(61, 1, 6, 1, NULL, NULL, '<p>Antonim dari DESTRUKTIF</p>\r\n', '#####<p>Instruktif</p>\r\n', '#####<p>Produktif</p>\r\n', '#####<p>Vandalisme</p>\r\n', '#####<p>Konstruktif</p>\r\n', '#####<p>Subversif</p>\r\n', 'D', '0000-00-00 00:00:00', 233, 436),
(62, 1, 3, 1, NULL, NULL, '<p><strong>Cari Jawaban yang jenis katanya sama dengan soal atau cari Kesamaan Pola atau Kesamaan Hubungan yang sepadan.</strong></p>\r\n\r\n<p>Teller : Bank</p>\r\n', '#####<p>Kasir : Cek</p>\r\n', '#####<p>Peminjam : Pinjaman</p>\r\n', '#####<p>Artis : Museum</p>\r\n', '#####<p>Ring : Petinju</p>\r\n', '#####<p>Pelayan : Restoran</p>\r\n', 'E', '2019-09-27 09:51:20', 434, 167),
(63, 1, 3, 1, NULL, NULL, '<p><strong>Cari Jawaban yang jenis katanya sama dengan soal atau cari Kesamaan Pola atau Kesamaan Hubungan yang sepadan.</strong></p>\r\n\r\n<p>Emas : Karat</p>\r\n', '#####<p>Membeli : Uang</p>\r\n', '#####<p>Luas : Meter</p>\r\n', '#####<p>Jarak : Mil</p>\r\n', '#####<p>Haus : Minum</p>\r\n', '#####<p>Derajat : Suhu</p>\r\n', 'C', '2019-09-27 09:51:20', 91, 508),
(64, 1, 3, 1, NULL, NULL, '<p><strong>Cari Jawaban yang jenis katanya sama dengan soal atau cari Kesamaan Pola atau Kesamaan Hubungan yang sepadan.</strong></p>\r\n\r\n<p>Dokter : Resep</p>\r\n', '#####<p>Psikiater : Ide</p>\r\n', '#####<p>Montir : Rusak</p>\r\n', '#####<p>Apoteker : Obat</p>\r\n', '#####<p>Koki : Dapur</p>\r\n', '#####<p>Pilot : Pesawat</p>\r\n', 'C', '2019-09-27 09:51:20', 394, 178),
(65, 1, 3, 1, NULL, NULL, '<p><strong>Cari Jawaban yang jenis katanya sama dengan soal atau cari Kesamaan Pola atau Kesamaan Hubungan yang sepadan.</strong></p>\r\n\r\n<p>Kepak : Sayap</p>\r\n', '#####<p>Hirup : Oksigen</p>\r\n', '#####<p>Sandar : Kepala</p>\r\n', '#####<p>Hentak : Kaki</p>\r\n', '#####<p>Tarik : Tali</p>\r\n', '#####<p>Lapar : Makan</p>\r\n', 'C', '2019-09-27 09:51:20', 355, 214),
(66, 1, 3, 1, NULL, NULL, '<p><strong>Cari Jawaban yang jenis katanya sama dengan soal atau cari Kesamaan Pola atau Kesamaan Hubungan yang sepadan.</strong></p>\r\n\r\n<p>Tembakau : Rokok</p>\r\n', '#####<p>Teh : Susu</p>\r\n', '#####<p>Kopi : Gelas</p>\r\n', '#####<p>Gandum : Roti</p>\r\n', '#####<p>Pedas : Cabai</p>\r\n', '#####<p>Gula : Roti</p>\r\n', 'C', '2019-09-27 09:51:20', 437, 158),
(67, 1, 3, 1, NULL, NULL, '<p><strong>Cari Jawaban yang jenis katanya sama dengan soal atau cari Kesamaan Pola atau Kesamaan Hubungan yang sepadan.</strong></p>\r\n\r\n<p>Pelukis : Kuas</p>\r\n', '#####<p>Burung : Sangkar</p>\r\n', '#####<p>Penyair : Pena</p>\r\n', '#####<p>Bensin : Mobil</p>\r\n', '#####<p>Lapar : Makan</p>\r\n', '#####<p>Lampu : Gelap</p>\r\n', 'B', '2019-09-27 09:51:20', 419, 161),
(68, 1, 3, 1, NULL, NULL, '<p><strong>Cari Jawaban yang jenis katanya sama dengan soal atau cari Kesamaan Pola atau Kesamaan Hubungan yang sepadan.</strong></p>\r\n\r\n<p>Rambut : Gundul</p>\r\n', '#####<p>Pakaian : Bugil</p>\r\n', '#####<p>Lantai : Kotor</p>\r\n', '#####<p>Cabut : Rumput</p>\r\n', '#####<p>Mobil : Mogok</p>\r\n', '#####<p>Kepala : Botak</p>\r\n', 'A', '2019-09-27 09:51:20', 228, 373),
(69, 1, 3, 1, NULL, NULL, '<p><strong>Cari Jawaban yang jenis katanya sama dengan soal atau cari Kesamaan Pola atau Kesamaan Hubungan yang sepadan.</strong></p>\r\n\r\n<p>Kurus : Gizi</p>\r\n', '#####<p>Gemuk : Lemak</p>\r\n', '#####<p>Sakit : Dokter</p>\r\n', '#####<p>Pendek : Besar</p>\r\n', '#####<p>Pintar : Belajar</p>\r\n', '#####<p>Bodoh : Ilmu</p>\r\n', 'E', '2019-09-27 09:51:20', 167, 430),
(70, 1, 3, 1, NULL, NULL, '<p><strong>Cari Jawaban yang jenis katanya sama dengan soal atau cari Kesamaan Pola atau Kesamaan Hubungan yang sepadan.</strong></p>\r\n\r\n<p>Beranak : Mamalia</p>\r\n', '#####<p>Bertaring : Karnivora</p>\r\n', '#####<p>Berkaki empat : Herbivora</p>\r\n', '#####<p>Berkelompok : Insekta</p>\r\n', '#####<p>Berjemur : Reptilia</p>\r\n', '#####<p>Bertelur : Unggas</p>\r\n', 'A', '2019-09-27 09:51:20', 93, 489),
(71, 1, 3, 1, NULL, NULL, '<p><strong>Cari Jawaban yang jenis katanya sama dengan soal atau cari Kesamaan Pola atau Kesamaan Hubungan yang sepadan.</strong></p>\r\n\r\n<p>Nelayan : Laut</p>\r\n', '#####<p>Guru : Papan Tulis</p>\r\n', '#####<p>Petani : Padi</p>\r\n', '#####<p>Karyawan : Pegawai</p>\r\n', '#####<p>Pelukis : Kuas</p>\r\n', '#####<p>Penyanyi : Panggung</p>\r\n', 'E', '2019-09-27 09:51:20', 356, 230),
(72, 1, 3, 1, NULL, NULL, '<p><strong>Cari Jawaban yang jenis katanya sama dengan soal atau cari Kesamaan Pola atau Kesamaan Hubungan yang sepadan.</strong></p>\r\n\r\n<p>.... berhubungan dengan Buruh, sebagaimana Supervisor berhubungan dengan ....</p>\r\n', '#####<p>Mandor - Karyawan</p>\r\n', '#####<p>Pabrik - Kantor</p>\r\n', '#####<p>Kecil - Besar</p>\r\n', '#####<p>Demo - Kerja</p>\r\n', '#####<p>Upah - Gaji</p>\r\n', 'A', '2019-09-27 09:51:20', 229, 355),
(73, 1, 3, 1, NULL, NULL, '<p><strong>Cari Jawaban yang jenis katanya sama dengan soal atau cari Kesamaan Pola atau Kesamaan Hubungan yang sepadan.</strong></p>\r\n\r\n<p>Jus berhubungan dengan .... sebagaimana .... berhubungan dengan binatang</p>\r\n', '#####<p>Sehat - Hewan</p>\r\n', '#####<p>Lunak - Jinak</p>\r\n', '#####<p>Blender - Kandang</p>\r\n', '#####<p>Segar - Katak</p>\r\n', '#####<p>Minuman - Angsa</p>\r\n', 'E', '2019-09-27 09:51:20', 294, 302),
(74, 1, 3, 1, NULL, NULL, '<p><strong>Cari Jawaban yang jenis katanya sama dengan soal atau cari Kesamaan Pola atau Kesamaan Hubungan yang sepadan.</strong></p>\r\n\r\n<p>Kaku berhubungan dengan .... sebagaimana .... berhubungan dengan karet</p>\r\n', '#####<p>Tongkat - Gelang</p>\r\n', '#####<p>Batu - Lembut</p>\r\n', '#####<p>Besi - Lentur</p>\r\n', '#####<p>Kaki - Fleksibel</p>\r\n', '#####<p>Kayu - Lateks</p>\r\n', 'C', '2019-09-27 09:51:20', 339, 255),
(75, 1, 3, 1, NULL, NULL, '<p><strong>Cari Jawaban yang jenis katanya sama dengan soal atau cari Kesamaan Pola atau Kesamaan Hubungan yang sepadan.</strong></p>\r\n\r\n<p>Musyawarah berhubungan dengan .... sebagaimana .... berhubungan dengan lulus</p>\r\n', '#####<p>Rapat - Tamat</p>\r\n', '#####<p>Berembuk - Kuliah</p>\r\n', '#####<p>Diskusi - Tugas</p>\r\n', '#####<p>Mufakat - Ujian</p>\r\n', '#####<p>Debat - Kompetisi</p>\r\n', 'D', '2019-09-27 09:51:20', 331, 247),
(76, 1, 3, 1, NULL, NULL, '<p><strong>Cari Jawaban yang jenis katanya sama dengan soal atau cari Kesamaan Pola atau Kesamaan Hubungan yang sepadan.</strong></p>\r\n\r\n<p>Tertawa berhubungan dengan .... sebagaimana .... berhubungan dengan sedih</p>\r\n', '#####<p>Lucu - Derita</p>\r\n', '#####<p>Senang - Menangis</p>\r\n', '#####<p>Emosi - Perasaan</p>\r\n', '#####<p>Puas - Kecewa</p>\r\n', '#####<p>Gembira - Kesal</p>\r\n', 'B', '2019-09-27 09:51:20', 327, 277),
(77, 1, 3, 1, NULL, NULL, '<p><strong>Cari Jawaban yang jenis katanya sama dengan soal atau cari Kesamaan Pola atau Kesamaan Hubungan yang sepadan.</strong></p>\r\n\r\n<p>.... : Mobil = Angin : ....</p>\r\n', '#####<p>Kendaraan - Udara</p>\r\n', '#####<p>Sedan - Dingin</p>\r\n', '#####<p>Bensin - Kincir</p>\r\n', '#####<p>Jalan - Baling-Baling</p>\r\n', '#####<p>Roda - Kipas</p>\r\n', 'C', '2019-09-27 09:51:20', 152, 460),
(78, 1, 3, 1, NULL, NULL, '<p><strong>Cari Jawaban yang jenis katanya sama dengan soal atau cari Kesamaan Pola atau Kesamaan Hubungan yang sepadan.</strong></p>\r\n\r\n<p>.... : Benang = Baja : ....</p>\r\n', '#####<p>Kapas - Pisau</p>\r\n', '#####<p>Plastik - Besi</p>\r\n', '#####<p>Kain - Beton</p>\r\n', '#####<p>Jahit - Tempa</p>\r\n', '#####<p>Tenun - Logam</p>\r\n', 'A', '2019-09-27 09:51:20', 120, 481),
(79, 1, 3, 1, NULL, NULL, '<p><strong>Cari Jawaban yang jenis katanya sama dengan soal atau cari Kesamaan Pola atau Kesamaan Hubungan yang sepadan.</strong></p>\r\n\r\n<p>.... : Bau = Menyilaukan : ....</p>\r\n', '#####<p>Busuk - Terang</p>\r\n', '#####<p>Menyengat - Cahaya</p>\r\n', '#####<p>Harum - Matahari</p>\r\n', '#####<p>Aroma - Lampu</p>\r\n', '#####<p>Sedap - Sinar</p>\r\n', 'B', '2019-09-27 09:51:20', 257, 333),
(80, 1, 3, 1, NULL, NULL, '<p><strong>Cari Jawaban yang jenis katanya sama dengan soal atau cari Kesamaan Pola atau Kesamaan Hubungan yang sepadan.</strong></p>\r\n\r\n<p>Bunga : .... = .... : Kotor</p>\r\n', '#####<p>Wangi - Bangkai</p>\r\n', '#####<p>Semerbak - Bersih</p>\r\n', '#####<p>Sedap - Lalat</p>\r\n', '#####<p>Indah - Sampah</p>\r\n', '#####<p>Harum - Limbah</p>\r\n', 'D', '2019-09-27 09:51:20', 330, 265),
(81, 1, 3, 1, NULL, NULL, '<p><strong>Cari Jawaban yang jenis katanya sama dengan soal atau cari Kesamaan Pola atau Kesamaan Hubungan yang sepadan.</strong></p>\r\n\r\n<p>Cepat : .... = .... : Mengkilap</p>\r\n', '#####<p>Kilat - Memudar</p>\r\n', '#####<p>Singkat - Berpendar</p>\r\n', '#####<p>Perlahan-lahan - Bersinar</p>\r\n', '#####<p>Lambat - Bercahaya</p>\r\n', '#####<p>Gerak - Rapi</p>\r\n', 'B', '2019-09-27 09:51:20', 155, 442),
(82, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Mata', '#####Telinga', '#####Dada', '#####Mulut', '#####Hidung', 'C', '2019-09-27 10:50:52', 759, 20),
(83, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####India', '#####Thailand', '#####Laos', '#####Indonesia', '#####Singapura', 'A', '2019-09-27 10:50:52', 568, 202),
(84, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Harimau', '#####Singa', '#####Buaya', '#####Kucing', '#####Serigala', 'D', '2019-09-27 10:50:52', 339, 420),
(85, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Nike', '#####Adidas', '#####Go Sport', '#####Puma', '#####Reebok', 'C', '2019-09-27 10:50:52', 636, 128),
(86, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Jakarta', '#####Munchen', '#####Paris', '#####London', '#####Tokyo', 'B', '2019-09-27 10:50:52', 369, 388),
(87, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Kopenhagen', '#####Teheran', '#####Prancis', '#####Ankara', '#####Havana', 'C', '2019-09-27 10:50:52', 443, 330),
(88, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Wortel', '#####Bayam', '#####Kangkung', '#####Sawi', '#####Selada', 'A', '2019-09-27 10:50:52', 492, 273),
(89, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Membelah', '#####Memotong', '#####Membagi', '#####Memecah', '#####Memukul', 'E', '2019-09-27 10:50:52', 612, 178),
(90, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Hotel', '#####Istana', '#####Wisma', '#####Griya', '#####Motel', 'B', '2019-09-27 10:50:52', 404, 381),
(91, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Jawa Barat', '#####Banten', '#####Lampung', '#####Bengkulu', '#####Surabaya', 'E', '2019-09-27 10:50:52', 207, 560),
(92, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Bintang Utara', '#####Kompas', '#####Arah', '#####Arloji', '#####Penunjuk Jalan', 'D', '2019-09-27 10:50:52', 370, 399),
(93, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Satu', '#####Tiga', '#####Lima', '#####Tujuh', '#####Sepuluh', 'E', '2019-09-27 10:50:52', 690, 93),
(94, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Pound', '#####Peso', '#####Rupee', '#####Greece', '#####Escudo', 'D', '2019-09-27 10:50:52', 252, 518),
(95, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Wiyogo Atmodarminto', '#####Suryadi Soerdija', '#####Hatta Radjasa', '#####Soetiyoso', '#####Ali Sadikin', 'C', '2019-09-27 10:50:52', 197, 576),
(96, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Spion', '#####Spionase', '#####Jok', '#####Jendela', '#####Ban', 'B', '2019-09-27 10:50:52', 305, 470),
(97, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Kuda', '#####Kambing', '#####Anjing', '#####Kucing', '#####Kerbau', 'C', '2019-09-27 10:50:52', 396, 374),
(98, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Anggur', '#####Pepaya', '#####Timun', '#####Pisang', '#####Semangka', 'C', '2019-09-27 10:50:52', 545, 229),
(99, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Kepulauan Riau', '#####Jambi', '#####Riau', '#####Bandung', '#####Bengkulu', 'D', '2019-09-27 10:50:52', 559, 201),
(100, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Joglo', '#####Limasan', '#####Gadang', '#####Pendapa', '#####Keraton', 'E', '2019-09-27 10:50:52', 185, 576),
(101, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Seruling', '#####Klarinet', '#####Saxofon', '#####Terompet', '#####Biola', 'E', '2019-09-27 10:50:52', 584, 199),
(102, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Komputer', '#####Televisi', '#####Motor', '#####Radio', '#####Kulkas', 'C', '2019-09-27 10:50:52', 659, 130),
(103, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Amerika', '#####Italia', '#####Jerman', '#####Prancis', '#####Belanda', 'A', '2019-09-27 10:50:52', 502, 280),
(104, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Kapal Terbang', '#####Mobil', '#####Truk', '#####Kereta Api', '#####Sepeda Motor', 'A', '2019-09-27 10:50:52', 627, 133),
(105, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Arjuna', '#####Duryudana', '#####Werkudara', '#####Yudistira', '#####Nakula', 'B', '2019-09-27 10:50:52', 214, 551),
(106, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Melati', '#####Pinus', '#####Tulip', '#####Anggrek', '#####Kembang Sepatu', 'B', '2019-09-27 10:50:52', 553, 220),
(107, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Majalah', '#####Telepon', '#####Internet', '#####Televisi', '#####Pesawat', 'E', '2019-09-27 10:50:52', 555, 215),
(108, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Manado', '#####Ternate', '#####Mamuju', '#####Makassar', '#####Gorontalo', 'E', '2019-09-27 10:50:52', 130, 657),
(109, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Sepakbola', '#####Voli', '#####Tenis', '#####Basket', '#####Renang', 'E', '2019-09-27 10:50:52', 731, 47),
(110, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Bromo', '#####Rinjani', '#####Semeru', '#####Kapuas', '#####Slamet', 'D', '2019-09-27 10:50:52', 671, 102),
(111, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Sering', '#####Selalu', '#####Kadang-kadang', '#####Bilamana', '#####Pernah', 'D', '2019-09-27 10:50:52', 583, 204),
(112, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Mencair', '#####Membeku', '#####Menyublim', '#####Menguap', '#####Memanaskan', 'E', '2019-09-27 10:50:52', 463, 319),
(113, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Persebaya', '#####Persija', '#####Arema', '#####Semen Padang', '#####Selangor', 'E', '2019-09-27 10:50:52', 685, 92),
(114, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Solo', '#####Purwakarta', '#####Salatiga', '#####Sragen', '#####Wonogiri', 'B', '2019-09-27 10:50:52', 557, 212),
(115, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Pura', '#####Wihara', '#####Masjid', '#####Gapura', '#####Gereja', 'D', '2019-09-27 10:50:52', 616, 157),
(116, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Dingin', '#####Segar', '#####Sejuk', '#####Panas', '#####Telah', 'E', '2019-09-27 10:50:52', 663, 100),
(117, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Vietnam', '#####Thailand', '#####Hongkong', '#####Myanmar', '#####Singapura', 'C', '2019-09-27 10:50:52', 522, 248),
(118, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####David Beckham', '#####Michael Owen', '#####Cristiano Ronaldo', '#####Wayne Rooney', '#####John Terry', 'C', '2019-09-27 10:50:52', 144, 614),
(119, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Roma', '#####Milan', '#####Venezia', '#####Glasgow', '#####Turin', 'D', '2019-09-27 10:50:52', 477, 287),
(120, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Sapardi Djoko Damono', '#####Chariril Anwar', '#####Aji Santoso', '#####Taufik Ismail', '#####W. S. Rendra', 'C', '2019-09-27 10:50:52', 316, 438),
(121, 1, 4, 1, NULL, NULL, 'Cari yang berbeda diantara kelima kata', '#####Utara', '#####Tenggara', '#####Barat', '#####Atas', '#####Selatan', 'D', '2019-09-27 10:50:52', 735, 39),
(122, 1, 5, 1, NULL, NULL, '<p>Dila lebih pintar daripada Dika. Dila lebih pintar daripada Dina dan Dani.</p>\r\n', '#####<p>Dika lebih pintar daripada Dina.</p>\r\n', '#####<p>Dika lebih pintar daripada Dani.</p>\r\n', '#####<p>Dina dan Dani memiliki tingkat kepintaran yang sama.</p>\r\n', '#####<p>Dila paling pintar di antara mereka.</p>\r\n', '#####<p>Dani lebih pintar dari Dila.</p>\r\n', 'D', '0000-00-00 00:00:00', 888, 172),
(123, 1, 5, 1, NULL, NULL, '<p>Semua bunga di Taman Keputren berwarna putih. Semua putri suka bunga.</p>\r\n\r\n<p>Putri Lestari membawa bunga biru.</p>\r\n', '#####<p>Bunga yang dibawa Putri Lestari bukan dari Keputren.</p>\r\n', '#####<p>Putri Lestari tidak suka bunga.</p>\r\n', '#####<p>Taman Keputren ada bunga birunya.</p>\r\n', '#####<p>Putri suka bunga Biru.</p>\r\n', '#####<p>Sebagian putri tidak suka bunga.</p>\r\n', 'A', '0000-00-00 00:00:00', 734, 314),
(124, 1, 5, 1, NULL, NULL, '<p>Jika Maliyah memakai baju cokelat maka ia memakai celana hitam. Jika Maliyah</p>\r\n\r\n<p>memakai celana hitam maka Permata memakai celana cokelat. Permata memakai&nbsp;celana putih &hellip;.</p>\r\n', '#####<p>Maliyah tidak memakai baju cokelat.</p>\r\n', '#####<p>Permata memakai baju hitam.</p>\r\n', '#####<p>Maliyah memakai celana cokelat.</p>\r\n', '#####<p>Permata tidak memakai baju hitam.</p>\r\n', '#####<p>Maliyah memakai baju merah.</p>\r\n', 'A', '0000-00-00 00:00:00', 336, 718),
(125, 1, 5, 1, NULL, NULL, '<p>Semua murid pandai berhitung dan sopan. Dadidu tidak sopan, tapi pandai berhitung.</p>\r\n', '#####<p>Dadidu adalah seorang murid yang pandai berhitung.</p>\r\n', '#####<p>Dadidu adalah seorang murid yang tidak sopan.</p>\r\n', '#####<p>Dadidu adalah seorang murid yang pandai berhitung dan tidak sopan.</p>\r\n', '#####<p>Dadidu adalah bukan seorang murid meskipun pandai berhitung.</p>\r\n', '#####<p>Dadidu adalah bukan seorang murid yang sopan.</p>\r\n', 'D', '0000-00-00 00:00:00', 325, 729),
(126, 1, 5, 1, NULL, NULL, '<p>Setiap siswa peserta kesenian adalah peserta beladiri atau renang. Tidak ada siswa&nbsp;peserta beladiri atau renang yang bukan peserta melukis. Inda bukan peserta melukis.</p>\r\n', '#####<p>Inda adalah bukan peserta beladiri maupun kesenian.</p>\r\n', '#####<p>Inda adalah peserta melukis dan bukan peserta kesenian.</p>\r\n', '#####<p>Inda adalah bukan peserta kesenian tetapi peserta renang.</p>\r\n', '#####<p>Inda adalah peserta renang dan bukan peserta melukis.</p>\r\n', '#####<p>Inda adalah bukan peserta kesenian tetapi peserta beladiri.</p>\r\n', 'A', '0000-00-00 00:00:00', 408, 625),
(127, 1, 5, 1, NULL, NULL, '<p>Jika Tono lulus kuliah kurang dari sama dengan 4 tahun maka ia akan diterima bekerja&nbsp;sebagai karyawan di perusahaan A. Jika Tono sudah bekerja di perusahaan A maka&nbsp;ayahnya akan membelikan Tono sebuah sepeda motor. Tono tidak mendapat sepeda&nbsp;motor dari ayahnya.</p>\r\n', '#####<p>Tono menyelesaikan studinya kurang dari 4 tahun.</p>\r\n', '#####<p>Tono menyelesaikan studinya lebih dari 4 tahun.</p>\r\n', '#####<p>Tono bekerja di perusahaan A.</p>\r\n', '#####<p>Tono menyelesaikan studinya tepat 4 tahun.</p>\r\n', '#####<p>Tono bekerja dengan sepeda motor.</p>\r\n', 'B', '0000-00-00 00:00:00', 571, 470),
(128, 1, 5, 1, NULL, NULL, '<p>Semua siswa mengikuti senam pagi. Beberapa siswa memakai sepatu putih.</p>\r\n', '#####<p>Ada siswa yang tidak mengikuti senam pagi.</p>\r\n', '#####<p>Semua siswa memakai sepatu putih.</p>\r\n', '#####<p>Beberapa siswa peserta senam pagi bersepatu putih.</p>\r\n', '#####<p>Ada siswa bersepatu putih tidak mengikuti senam pagi.</p>\r\n', '#####<p>&nbsp;Semua siswa peserta senam pagi bersepatu putih.</p>\r\n', 'C', '0000-00-00 00:00:00', 780, 219),
(129, 1, 5, 1, NULL, NULL, '<p>Sebagian intan bersifat kuat. Semua yang kuat tidak mudah patah.</p>\r\n', '#####<p>Semua intan tidak mudah patah.</p>\r\n', '#####<p>Sebagian intan tidak mudah patah.</p>\r\n', '#####<p>Hanya intan yang tidak mudah patah.</p>\r\n', '#####<p>Kekuatan berasal dari intan.</p>\r\n', '#####<p>Semua jawaban salah.</p>\r\n', 'B', '0000-00-00 00:00:00', 522, 520),
(130, 1, 5, 1, NULL, NULL, '<p>Semua ponsel ada fasilitas SMS. Sebagian ponsel ada fasilitas internet.</p>\r\n', '#####<p>Semua ponsel adas fasilitas SMS dan internet.</p>\r\n', '#####<p>Sebagian ponsel ada fasilitas SMS dan internet.</p>\r\n', '#####<p>Sebagian ponsel ada fasilitas internet namun tidak ada fasilitas SMS.</p>\r\n', '#####<p>Semua yang ada fasilitas internet selalu ada fasilitas SMS.</p>\r\n', '#####<p>Semua ponsel tidak ada fasilitas SMS dan Internet.</p>\r\n', 'B', '0000-00-00 00:00:00', 255, 853),
(131, 1, 5, 1, NULL, NULL, '<p>Semua menu makanan restoran B diolah dari bahan organik. Sebagian menu makanan diolah&nbsp;tanpa menggunakan minyak (tidak digoreng).</p>\r\n', '#####<p>Semua menu yang diolah dengan digoreng bukan menu restoran B.</p>\r\n', '#####<p>Semua menu restoran B diolah tanpa digoreng dengan minyak.</p>\r\n', '#####<p>Sebagian menu restoran B dengan bahan organik diolah dengan digoreng.</p>\r\n', '#####<p>Semua menu diolah dengan cara digoreng menggunakan bahan organik.</p>\r\n', '#####<p>Semua menu dengan dengan bahan organik diolah dengan cara tidak digoreng.</p>\r\n', 'C', '0000-00-00 00:00:00', 338, 700),
(132, 1, 5, 1, NULL, NULL, '<p>Lima tim sepak bola A, B, C, D, dan E bertanding dalam sebuah turnamen. Setiap tim bertemu&nbsp;lawan yang sama dua kali, sekali dikandangnya dan sekali di kandang lawan. Untuk setiap&nbsp;pertandingan, tim pemenang diberi nilai 3, tim yang seri diberi nilai 1, dan tim yang kalah diberi&nbsp;nilai 0. hasil pertandingan adalah sebagai berikut :</p>\r\n\r\n<p>&bull;&nbsp;A dan E menang dua kali, B dan D seri empat kali, A dan C kalah dua kali.</p>\r\n\r\n<p>&bull;&nbsp;A mempunyai nilai lebih besar daripada E, namun lebih kecil daripada B.</p>\r\n\r\n<p>&bull;&nbsp;A dan E memiliki selisih nilai 4, demikian pula antara D dan E.</p>\r\n\r\n<p>&bull;&nbsp;B dan C memiliki jumlah kemenangan sama, tetapi nilainya berbeda satu.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Tim manakah yang memenangkan turnamen?</strong></p>\r\n', '#####<p>A</p>\r\n', '#####<p>B</p>\r\n', '#####<p>C</p>\r\n', '#####<p>D</p>\r\n', '#####<p>E</p>\r\n', 'B', '0000-00-00 00:00:00', 535, 513),
(133, 1, 5, 1, NULL, NULL, '<p>Lima tim sepak bola A, B, C, D, dan E bertanding dalam sebuah turnamen. Setiap tim bertemu&nbsp;lawan yang sama dua kali, sekali dikandangnya dan sekali di kandang lawan. Untuk setiap&nbsp;pertandingan, tim pemenang diberi nilai 3, tim yang seri diberi nilai 1, dan tim yang kalah diberi&nbsp;nilai 0. hasil pertandingan adalah sebagai berikut :</p>\r\n\r\n<p>&bull;&nbsp;A dan E menang dua kali, B dan D seri empat kali, A dan C kalah dua kali.</p>\r\n\r\n<p>&bull;&nbsp;A mempunyai nilai lebih besar daripada E, namun lebih kecil daripada B.</p>\r\n\r\n<p>&bull;&nbsp;A dan E memiliki selisih nilai 4, demikian pula antara D dan E.</p>\r\n\r\n<p>&bull;&nbsp;B dan C memiliki jumlah kemenangan sama, tetapi nilainya berbeda satu.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Dua tim manakah yang memiliki nilai yang sama?</strong></p>\r\n', '#####<p>A dan C</p>\r\n', '#####<p>B dan D</p>\r\n', '#####<p>C dan E</p>\r\n', '#####<p>D dan A</p>\r\n', '#####<p>E dan B</p>\r\n', 'D', '0000-00-00 00:00:00', 160, 878),
(134, 1, 5, 1, NULL, NULL, '<p>Lima tim sepak bola A, B, C, D, dan E bertanding dalam sebuah turnamen. Setiap tim bertemu&nbsp;lawan yang sama dua kali, sekali dikandangnya dan sekali di kandang lawan. Untuk setiap&nbsp;pertandingan, tim pemenang diberi nilai 3, tim yang seri diberi nilai 1, dan tim yang kalah diberi&nbsp;nilai 0. hasil pertandingan adalah sebagai berikut :</p>\r\n\r\n<p>&bull;&nbsp;A dan E menang dua kali, B dan D seri empat kali, A dan C kalah dua kali.</p>\r\n\r\n<p>&bull;&nbsp;A mempunyai nilai lebih besar daripada E, namun lebih kecil daripada B.</p>\r\n\r\n<p>&bull;&nbsp;A dan E memiliki selisih nilai 4, demikian pula antara D dan E.</p>\r\n\r\n<p>&bull;&nbsp;B dan C memiliki jumlah kemenangan sama, tetapi nilainya berbeda satu.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Urutan tim yang mungkin berdasarkan perolehan nilai tertinggi ke terendah adalah?</strong></p>\r\n', '#####<p>B-C-D-A-E</p>\r\n', '#####<p>B-A-D-E-C</p>\r\n', '#####<p>C-B-A-D-E</p>\r\n', '#####<p>C-B-A-E-D</p>\r\n', '#####<p>D-C-B-A-E</p>\r\n', 'A', '0000-00-00 00:00:00', 360, 681),
(135, 1, 5, 1, NULL, NULL, '<p>Lembaga pelatihan buka tiap hari dengan skala 6 pelatihan utama, yaitu seni suara, seni lukis,&nbsp;olahraga, seni tari, seni musik dan seni drama. Setiap hari hanya ada 1 pelatihan utama, tetapi&nbsp;tiap 2 hari sekali ada tambahan pelatihan bahasa. Awal minggu dimulai dengan hari Senin. Pada&nbsp;hari Rabu diberikan pelatihan seni lukis dan bahasa.</p>\r\n\r\n<p>a).&nbsp;Olahraga ada di antara seni lukis dan seni drama.</p>\r\n\r\n<p>b).&nbsp;Seni lukis diberikan 2 hari setelah seni tari.</p>\r\n\r\n<p>c). Seni musik ditawarkan 2 kali seminggu, tetapi tidak boleh berurutan.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Pernyataan dibawah ini yang paling benar adalah?</strong></p>\r\n', '#####<p>Pada hari Kamis hanya ada seni drama</p>\r\n', '#####<p>Seni musik diberikan tiap hari Rabu dan Minggu</p>\r\n', '#####<p>Seni Tari diberikan setiap hari Minggu</p>\r\n', '#####<p>Hanya olahraga yang diajarkan pada Hari Kamis</p>\r\n', '#####<p>Seni suara dan bahasa diberikan pada hari Selasa.</p>\r\n', 'D', '0000-00-00 00:00:00', 302, 798),
(136, 1, 5, 1, NULL, NULL, '<p>Lembaga pelatihan buka tiap hari dengan skala 6 pelatihan utama, yaitu seni suara, seni lukis,&nbsp;olahraga, seni tari, seni musik dan seni drama. Setiap hari hanya ada 1 pelatihan utama, tetapi&nbsp;tiap 2 hari sekali ada tambahan pelatihan bahasa. Awal minggu dimulai dengan hari Senin. Pada&nbsp;hari Rabu diberikan pelatihan seni lukis dan bahasa.</p>\r\n\r\n<p>a).&nbsp;Olahraga ada di antara seni lukis dan seni drama.</p>\r\n\r\n<p>b).&nbsp;Seni lukis diberikan 2 hari setelah seni tari.</p>\r\n\r\n<p>c). Seni musik ditawarkan 2 kali seminggu, tetapi tidak boleh berurutan.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Pelatihan yang mungkin diselenggarakan pada hari Sabtu adalah?</strong></p>\r\n', '#####<p>Seni Drama</p>\r\n', '#####<p>Seni Lukis</p>\r\n', '#####<p>Olahraga</p>\r\n', '#####<p>Seni Tari</p>\r\n', '#####<p>Seni Suara</p>\r\n', 'E', '0000-00-00 00:00:00', 249, 807),
(137, 1, 5, 1, NULL, NULL, '<p>Lembaga pelatihan buka tiap hari dengan skala 6 pelatihan utama, yaitu seni suara, seni lukis,&nbsp;olahraga, seni tari, seni musik dan seni drama. Setiap hari hanya ada 1 pelatihan utama, tetapi&nbsp;tiap 2 hari sekali ada tambahan pelatihan bahasa. Awal minggu dimulai dengan hari Senin. Pada&nbsp;hari Rabu diberikan pelatihan seni lukis dan bahasa.</p>\r\n\r\n<p>a).&nbsp;Olahraga ada di antara seni lukis dan seni drama.</p>\r\n\r\n<p>b).&nbsp;Seni lukis diberikan 2 hari setelah seni tari.</p>\r\n\r\n<p>c). Seni musik ditawarkan 2 kali seminggu, tetapi tidak boleh berurutan.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Urutan pelatihan utama dari hari Senin - Jumat adalah?</strong></p>\r\n', '#####<p>Seni Tari, Seni Musik, Seni Lukis, Olahraga, Seni Drama</p>\r\n', '#####<p>Seni Tari, Seni Musik, Seni Lukis, Seni Drama, Olahraga</p>\r\n', '#####<p>Seni Tari, Seni Suara, Seni Lukis, Olahraga, Seni Drama</p>\r\n', '#####<p>Seni Tari, Seni Drama, Seni Lukis, Olahraga, Seni Musik</p>\r\n', '#####<p>Seni Tari, Seni Lukis, Olahraga, Seni Drama, Seni Musik</p>\r\n', 'A', '0000-00-00 00:00:00', 339, 730),
(138, 1, 5, 1, NULL, NULL, '<p>Lembaga pelatihan buka tiap hari dengan skala 6 pelatihan utama, yaitu seni suara, seni lukis,&nbsp;olahraga, seni tari, seni musik dan seni drama. Setiap hari hanya ada 1 pelatihan utama, tetapi&nbsp;tiap 2 hari sekali ada tambahan pelatihan bahasa. Awal minggu dimulai dengan hari Senin. Pada&nbsp;hari Rabu diberikan pelatihan seni lukis dan bahasa.</p>\r\n\r\n<p>a).&nbsp;Olahraga ada di antara seni lukis dan seni drama.</p>\r\n\r\n<p>b).&nbsp;Seni lukis diberikan 2 hari setelah seni tari.</p>\r\n\r\n<p>c). Seni musik ditawarkan 2 kali seminggu, tetapi tidak boleh berurutan.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Jika Arya datang pada hari Jumat maka pelatihan yang diadakan adalah?</strong></p>\r\n', '#####<p>Seni Musik dan Bahasa</p>\r\n', '#####<p>Seni Lukis dan Bahasa</p>\r\n', '#####<p>Seni Suara dan Bahasa</p>\r\n', '#####<p>Seni Drama dan Bahasa</p>\r\n', '#####<p>Olahraga dan Bahasa</p>\r\n', 'D', '0000-00-00 00:00:00', 244, 812),
(139, 1, 5, 1, NULL, NULL, '<p>Seorang calon Legislatif melakukan kampanye pada lima kecamatan yaitu, J, K, L, M, dan N&nbsp;dengan ketentuan sebagai berikut :</p>\r\n\r\n<p>&bull;&nbsp;Ia dapat berkunjung ke kecamatan M jika telah ke L dan N.</p>\r\n\r\n<p>&bull;&nbsp;Ia tidak bisa mengunjungi kecamatan N sebelum mengunjungi kecamatan J.</p>\r\n\r\n<p>&bull;&nbsp;Kecamatan kedua yang harus dikunjungi adalah K.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Kecamatan yang pertama harus dikunjungi adalah?</strong></p>\r\n', '#####<p>Kecamatan J</p>\r\n', '#####<p>Kecamatan K</p>\r\n', '#####<p>Kecamatan L</p>\r\n', '#####<p>Kecamatan M</p>\r\n', '#####<p>Kecamatan N</p>\r\n', 'A', '0000-00-00 00:00:00', 697, 337),
(140, 1, 5, 1, NULL, NULL, '<p>Seorang calon Legislatif melakukan kampanye pada lima kecamatan yaitu, J, K, L, M, dan N&nbsp;dengan ketentuan sebagai berikut :</p>\r\n\r\n<p>&bull;&nbsp;Ia dapat berkunjung ke kecamatan M jika telah ke L dan N.</p>\r\n\r\n<p>&bull;&nbsp;Ia tidak bisa mengunjungi kecamatan N sebelum mengunjungi kecamatan J.</p>\r\n\r\n<p>&bull;&nbsp;Kecamatan kedua yang harus dikunjungi adalah K.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Dua Kecamatan dapat dikunjungi setelah kecamatan N adalah kecamatan?</strong></p>\r\n', '#####<p>Kecamatan J dan K</p>\r\n', '#####<p>Kecamatan L dan M</p>\r\n', '#####<p>Kecamatan M dan J</p>\r\n', '#####<p>Kecamatan N dan K</p>\r\n', '#####<p>Kecamatan K dan L</p>\r\n', 'B', '0000-00-00 00:00:00', 468, 591),
(141, 1, 5, 1, NULL, NULL, '<p>Seorang calon Legislatif melakukan kampanye pada lima kecamatan yaitu, J, K, L, M, dan N&nbsp;dengan ketentuan sebagai berikut :</p>\r\n\r\n<p>&bull;&nbsp;Ia dapat berkunjung ke kecamatan M jika telah ke L dan N.</p>\r\n\r\n<p>&bull;&nbsp;Ia tidak bisa mengunjungi kecamatan N sebelum mengunjungi kecamatan J.</p>\r\n\r\n<p>&bull;&nbsp;Kecamatan kedua yang harus dikunjungi adalah K.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Rencana kunjungan yang sebaiknya dipilih agar lima kecamatan dapat dikunjungi adalah?</strong></p>\r\n', '#####<p>J, K, N, L, M</p>\r\n', '#####<p>K, J, L, N, M</p>\r\n', '#####<p>K, M, L, J, N</p>\r\n', '#####<p>L, K, J, N, M</p>\r\n', '#####<p>M, K, N, J, L</p>\r\n', 'A', '0000-00-00 00:00:00', 609, 482),
(142, 1, 2, 1, NULL, NULL, '<p><strong>Petunjuk Soal : Tes ini berupa soal yang terdiri atas deretan angka atau huruf yang belum selesai,&nbsp;dalam setiap deret terdapat suatu pola. Tugas anda mencari angka atau huruf selanjutnya&nbsp;yang sesuai berdasarkan pola - pola tertentu.</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>0, 4, 10, 18, 28, ... , ...</p>\r\n', '#####<p>40, 54</p>\r\n', '#####<p>40, 48</p>\r\n', '#####<p>38, 54</p>\r\n', '#####<p>38, 48</p>\r\n', '#####<p>38, 40</p>\r\n', 'A', '0000-00-00 00:00:00', 728, 620),
(143, 1, 2, 1, NULL, NULL, '<p><strong>Petunjuk Soal : Tes ini berupa soal yang terdiri atas deretan angka atau huruf yang belum selesai,&nbsp;dalam setiap deret terdapat suatu pola. Tugas anda mencari angka atau huruf selanjutnya&nbsp;yang sesuai berdasarkan pola - pola tertentu.</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>3, 4, 8, 9, 18, 19, ... , ...</p>\r\n', '#####<p>28, 29</p>\r\n', '#####<p>54, 81</p>\r\n', '#####<p>16, 21</p>\r\n', '#####<p>27, 36</p>\r\n', '#####<p>38, 39</p>\r\n', 'E', '0000-00-00 00:00:00', 659, 672),
(144, 1, 2, 1, NULL, NULL, '<p><strong>Petunjuk Soal : Tes ini berupa soal yang terdiri atas deretan angka atau huruf yang belum selesai,&nbsp;dalam setiap deret terdapat suatu pola. Tugas anda mencari angka atau huruf selanjutnya&nbsp;yang sesuai berdasarkan pola - pola tertentu.</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>92, 88, 84, 80, 76, ... , ...</p>\r\n', '#####<p>72 dan 68</p>\r\n', '#####<p>71 dan 69</p>\r\n', '#####<p>70 dan 68</p>\r\n', '#####<p>69 dan 65</p>\r\n', '#####<p>68 dan 62</p>\r\n', 'A', '0000-00-00 00:00:00', 1136, 199),
(145, 1, 2, 1, NULL, NULL, '<p><strong>Petunjuk Soal : Tes ini berupa soal yang terdiri atas deretan angka atau huruf yang belum selesai,&nbsp;dalam setiap deret terdapat suatu pola. Tugas anda mencari angka atau huruf selanjutnya&nbsp;yang sesuai berdasarkan pola - pola tertentu.</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>2040, 2040, 1020, 340, 85, ...</p>\r\n', '#####<p>68</p>\r\n', '#####<p>51</p>\r\n', '#####<p>42</p>\r\n', '#####<p>21</p>\r\n', '#####<p>17</p>\r\n', 'E', '0000-00-00 00:00:00', 422, 884),
(146, 1, 2, 1, NULL, NULL, '<p><strong>Petunjuk Soal : Tes ini berupa soal yang terdiri atas deretan angka atau huruf yang belum selesai,&nbsp;dalam setiap deret terdapat suatu pola. Tugas anda mencari angka atau huruf selanjutnya&nbsp;yang sesuai berdasarkan pola - pola tertentu.</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>3, 8, 6, 12, 11, ... , ...</p>\r\n', '#####<p>18 dan 18</p>\r\n', '#####<p>17 dan 21</p>\r\n', '#####<p>17 dan 15</p>\r\n', '#####<p>16 dan 22</p>\r\n', '#####<p>16 dan 18</p>\r\n', 'A', '0000-00-00 00:00:00', 396, 919),
(147, 1, 2, 1, NULL, NULL, '<p><strong>Petunjuk Soal : Tes ini berupa soal yang terdiri atas deretan angka atau huruf yang belum selesai,&nbsp;dalam setiap deret terdapat suatu pola. Tugas anda mencari angka atau huruf selanjutnya&nbsp;yang sesuai berdasarkan pola - pola tertentu.</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>3, -1, 6, 2, 9, 5, 12, ...</p>\r\n', '#####<p>5</p>\r\n', '#####<p>6</p>\r\n', '#####<p>8</p>\r\n', '#####<p>9</p>\r\n', '#####<p>12</p>\r\n', 'C', '0000-00-00 00:00:00', 886, 446),
(148, 1, 2, 1, NULL, NULL, '<p><strong>Petunjuk Soal : Tes ini berupa soal yang terdiri atas deretan angka atau huruf yang belum selesai,&nbsp;dalam setiap deret terdapat suatu pola. Tugas anda mencari angka atau huruf selanjutnya&nbsp;yang sesuai berdasarkan pola - pola tertentu.</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>11, 4, 22, 9, ... , 14, 44, 19, 55</p>\r\n', '#####<p>33</p>\r\n', '#####<p>28</p>\r\n', '#####<p>25</p>\r\n', '#####<p>24</p>\r\n', '#####<p>20</p>\r\n', 'A', '0000-00-00 00:00:00', 694, 631),
(149, 1, 2, 1, NULL, NULL, '<p><strong>Petunjuk Soal : Tes ini berupa soal yang terdiri atas deretan angka atau huruf yang belum selesai,&nbsp;dalam setiap deret terdapat suatu pola. Tugas anda mencari angka atau huruf selanjutnya&nbsp;yang sesuai berdasarkan pola - pola tertentu.</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>ABA, ABE, ABI, ... , ...</p>\r\n', '#####<p>ABM dan ABQ</p>\r\n', '#####<p>ABU dan ABO</p>\r\n', '#####<p>ABE dan ABZ</p>\r\n', '#####<p>ABJ dan ABG</p>\r\n', '#####<p>ABD dan ABX</p>\r\n', 'A', '0000-00-00 00:00:00', 618, 677),
(150, 1, 2, 1, NULL, NULL, '<p><strong>Petunjuk Soal : Tes ini berupa soal yang terdiri atas deretan angka atau huruf yang belum selesai,&nbsp;dalam setiap deret terdapat suatu pola. Tugas anda mencari angka atau huruf selanjutnya&nbsp;yang sesuai berdasarkan pola - pola tertentu.</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>2, 3, 5, 8, 8, 12, 11, 17, ... , ...</p>\r\n', '#####<p>14, 21</p>\r\n', '#####<p>13, 20</p>\r\n', '#####<p>12, 19</p>\r\n', '#####<p>15, 24</p>\r\n', '#####<p>16, 24</p>\r\n', 'A', '0000-00-00 00:00:00', 447, 870),
(151, 1, 2, 1, NULL, NULL, '<p><strong>Petunjuk Soal : Tes ini berupa soal yang terdiri atas deretan angka atau huruf yang belum selesai,&nbsp;dalam setiap deret terdapat suatu pola. Tugas anda mencari angka atau huruf selanjutnya&nbsp;yang sesuai berdasarkan pola - pola tertentu.</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>C, F, I, L, O, ... , ...</p>\r\n', '#####<p>T dan V</p>\r\n', '#####<p>R dan U</p>\r\n', '#####<p>R dan S</p>\r\n', '#####<p>Q dan T</p>\r\n', '#####<p>P dan S</p>\r\n', 'B', '0000-00-00 00:00:00', 911, 429),
(152, 1, 2, 1, NULL, NULL, '<p><strong>Petunjuk Soal : Tes ini berupa soal yang terdiri atas deretan angka atau huruf yang belum selesai,&nbsp;dalam setiap deret terdapat suatu pola. Tugas anda mencari angka atau huruf selanjutnya&nbsp;yang sesuai berdasarkan pola - pola tertentu.</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>3, 9, 27, 81, ...</p>\r\n', '#####<p>90</p>\r\n', '#####<p>162</p>\r\n', '#####<p>225</p>\r\n', '#####<p>243</p>\r\n', '#####<p>100</p>\r\n', 'D', '0000-00-00 00:00:00', 949, 371),
(153, 1, 2, 1, NULL, NULL, '<p><strong>Petunjuk Soal : Tes ini berupa soal yang terdiri atas deretan angka atau huruf yang belum selesai,&nbsp;dalam setiap deret terdapat suatu pola. Tugas anda mencari angka atau huruf selanjutnya&nbsp;yang sesuai berdasarkan pola - pola tertentu.</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>T, L, P, N, L, P, ...</p>\r\n', '#####<p>J</p>\r\n', '#####<p>H</p>\r\n', '#####<p>O</p>\r\n', '#####<p>Q</p>\r\n', '#####<p>R</p>\r\n', 'B', '0000-00-00 00:00:00', 384, 956);
INSERT INTO `m_soal` (`id`, `id_guru`, `id_mapel`, `bobot`, `file`, `tipe_file`, `soal`, `opsi_a`, `opsi_b`, `opsi_c`, `opsi_d`, `opsi_e`, `jawaban`, `tgl_input`, `jml_benar`, `jml_salah`) VALUES
(154, 1, 2, 1, NULL, NULL, '<p><strong>Petunjuk Soal : Tes ini berupa soal yang terdiri atas deretan angka atau huruf yang belum selesai,&nbsp;dalam setiap deret terdapat suatu pola. Tugas anda mencari angka atau huruf selanjutnya&nbsp;yang sesuai berdasarkan pola - pola tertentu.</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>... , ... , 9, 16, 25, 36, 49</p>\r\n', '#####<p>0 dan 2</p>\r\n', '#####<p>1 dan 4</p>\r\n', '#####<p>2 dan 5</p>\r\n', '#####<p>3 dan 6</p>\r\n', '#####<p>5 dan 6</p>\r\n', 'B', '0000-00-00 00:00:00', 497, 849),
(155, 1, 2, 1, NULL, NULL, '<p><strong>Petunjuk Soal : Perhatikan abjad pada kolom di bawah ini kemudian tentukan abjad yang kosong (?) pada kolom tersebut</strong></p>\r\n\r\n<table border=\"1\" cellspacing=\"0\">\r\n	<tbody>\r\n		<tr>\r\n			<td style=\"width:1.0cm\">\r\n			<p><strong>C</strong></p>\r\n			</td>\r\n			<td style=\"width:1.0cm\">\r\n			<p><strong>G</strong></p>\r\n			</td>\r\n			<td style=\"width:1.0cm\">\r\n			<p><strong>K</strong></p>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td style=\"width:1.0cm\">\r\n			<p><strong>D</strong></p>\r\n			</td>\r\n			<td style=\"width:1.0cm\">\r\n			<p><strong>H</strong></p>\r\n			</td>\r\n			<td style=\"width:1.0cm\">\r\n			<p><strong>L</strong></p>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td style=\"width:1.0cm\">\r\n			<p><strong>A</strong></p>\r\n			</td>\r\n			<td style=\"width:1.0cm\">\r\n			<p><strong>E</strong></p>\r\n			</td>\r\n			<td style=\"width:1.0cm\">\r\n			<p><strong>?</strong></p>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n\r\n<p>&nbsp;</p>\r\n', '#####<p>H</p>\r\n', '#####<p>I</p>\r\n', '#####<p>K</p>\r\n', '#####<p>M</p>\r\n', '#####<p>O</p>\r\n', 'B', '0000-00-00 00:00:00', 845, 498),
(156, 1, 2, 1, NULL, NULL, '<p><strong>Petunjuk Soal : Perhatikan kotak-kotak berikut ini dan tentukanlah berapa angka yang tepat yang harus di isikan pada kotak yang masih kosong (?)</strong></p>\r\n\r\n<table border=\"1\" cellspacing=\"0\">\r\n	<tbody>\r\n		<tr>\r\n			<td style=\"width:1.0cm\">\r\n			<p><strong>9</strong></p>\r\n			</td>\r\n			<td style=\"width:1.0cm\">\r\n			<p><strong>3</strong></p>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td style=\"width:1.0cm\">\r\n			<p><strong>16</strong></p>\r\n			</td>\r\n			<td style=\"width:1.0cm\">\r\n			<p><strong>4</strong></p>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td style=\"width:1.0cm\">\r\n			<p><strong>27</strong></p>\r\n			</td>\r\n			<td style=\"width:1.0cm\">\r\n			<p><strong>9</strong></p>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td style=\"width:1.0cm\">\r\n			<p><strong>28</strong></p>\r\n			</td>\r\n			<td style=\"width:1.0cm\">\r\n			<p><strong>N</strong></p>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n\r\n<p>Berapakah N ?&nbsp;</p>\r\n', '#####<p>24</p>\r\n', '#####<p>28</p>\r\n', '#####<p>7</p>\r\n', '#####<p>9</p>\r\n', '#####<p>12</p>\r\n', 'C', '0000-00-00 00:00:00', 675, 656),
(157, 1, 2, 1, NULL, NULL, '<table border=\"1\" cellspacing=\"0\">\r\n	<tbody>\r\n		<tr>\r\n			<td style=\"width:1.0cm\">\r\n			<p><strong>12</strong></p>\r\n			</td>\r\n			<td style=\"width:1.0cm\">\r\n			<p><strong>6</strong></p>\r\n			</td>\r\n			<td style=\"width:1.0cm\">\r\n			<p><strong>4</strong></p>\r\n			</td>\r\n			<td style=\"width:1.0cm\">\r\n			<p><strong>8</strong></p>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td style=\"width:1.0cm\">\r\n			<p><strong>8</strong></p>\r\n			</td>\r\n			<td style=\"width:1.0cm\">\r\n			<p><strong>4</strong></p>\r\n			</td>\r\n			<td style=\"width:1.0cm\">\r\n			<p><strong>7</strong></p>\r\n			</td>\r\n			<td style=\"width:1.0cm\">\r\n			<p><strong>14</strong></p>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td style=\"width:1.0cm\">\r\n			<p><strong>6</strong></p>\r\n			</td>\r\n			<td style=\"width:1.0cm\">\r\n			<p><strong>2</strong></p>\r\n			</td>\r\n			<td style=\"width:1.0cm\">\r\n			<p><strong>3</strong></p>\r\n			</td>\r\n			<td style=\"width:1.0cm\">\r\n			<p><strong>9</strong></p>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td style=\"width:1.0cm\">\r\n			<p><strong>18</strong></p>\r\n			</td>\r\n			<td style=\"width:1.0cm\">\r\n			<p><strong>3</strong></p>\r\n			</td>\r\n			<td style=\"width:1.0cm\">\r\n			<p><strong>2</strong></p>\r\n			</td>\r\n			<td style=\"width:1.0cm\">\r\n			<p><strong>N</strong></p>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n\r\n<p><strong>Berapakah nilai N ?</strong></p>\r\n', '#####<p>18</p>\r\n', '#####<p>20</p>\r\n', '#####<p>12</p>\r\n', '#####<p>21</p>\r\n', '#####<p>22</p>\r\n', 'C', '0000-00-00 00:00:00', 476, 829),
(158, 1, 2, 1, NULL, NULL, '<p><strong>Berapakah nilai X dan Y ?</strong></p>\r\n\r\n<table border=\"1\" cellspacing=\"0\">\r\n	<tbody>\r\n		<tr>\r\n			<td style=\"width:40.85pt\">\r\n			<p><strong>2</strong></p>\r\n			</td>\r\n			<td style=\"width:42.55pt\">\r\n			<p><strong>8</strong></p>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td style=\"width:40.85pt\">\r\n			<p><strong>4</strong></p>\r\n			</td>\r\n			<td style=\"width:42.55pt\">\r\n			<p><strong>64</strong></p>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td style=\"width:40.85pt\">\r\n			<p><strong>6</strong></p>\r\n			</td>\r\n			<td style=\"width:42.55pt\">\r\n			<p><strong>216</strong></p>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td style=\"width:40.85pt\">\r\n			<p><strong>X</strong></p>\r\n			</td>\r\n			<td style=\"width:42.55pt\">\r\n			<p><strong>Y</strong></p>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n', '#####<p>5 dan 255</p>\r\n', '#####<p>8 dan 356</p>\r\n', '#####<p>8 dan 512</p>\r\n', '#####<p>8 dan 566</p>\r\n', '#####<p>10 dan 1000</p>\r\n', 'C', '0000-00-00 00:00:00', 641, 673),
(159, 1, 2, 1, NULL, NULL, '<p><strong>Petunjuk Soal : Tes ini berupa soal yang terdiri atas deretan angka atau huruf yang belum selesai,&nbsp;dalam setiap deret terdapat suatu pola. Tugas anda mencari angka atau huruf selanjutnya&nbsp;yang sesuai berdasarkan pola - pola tertentu.</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>A, B, C, B, C, D, C, D, E, D, E, F, ... , ... , ...</p>\r\n', '#####<p>E, F, G</p>\r\n', '#####<p>D, E, F</p>\r\n', '#####<p>D, G, H</p>\r\n', '#####<p>F, G, H</p>\r\n', '#####<p>E, G, F</p>\r\n', 'A', '0000-00-00 00:00:00', 881, 445),
(160, 1, 2, 1, NULL, NULL, '<p><strong>Petunjuk Soal : Tes ini berupa soal yang terdiri atas deretan angka atau huruf yang belum selesai,&nbsp;dalam setiap deret terdapat suatu pola. Tugas anda mencari angka atau huruf selanjutnya&nbsp;yang sesuai berdasarkan pola - pola tertentu.</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>... , ... , QRX, STW, UVV, WXU, YZT</p>\r\n', '#####<p>UPY dan OPQ</p>\r\n', '#####<p>MNA dan PQR</p>\r\n', '#####<p>MNZ dan OPY</p>\r\n', '#####<p>MNR dan OPS</p>\r\n', '#####<p>PQZ dan QRY</p>\r\n', 'C', '0000-00-00 00:00:00', 406, 919),
(161, 1, 2, 1, NULL, NULL, '<p><strong>Petunjuk Soal : Tes ini berupa soal yang terdiri atas deretan angka atau huruf yang belum selesai,&nbsp;dalam setiap deret terdapat suatu pola. Tugas anda mencari angka atau huruf selanjutnya&nbsp;yang sesuai berdasarkan pola - pola tertentu.</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>A, Z, B, X, C, Y, D, ... , ...</p>\r\n', '#####<p>E dan W</p>\r\n', '#####<p>F dan V</p>\r\n', '#####<p>G dan W</p>\r\n', '#####<p>V dan E</p>\r\n', '#####<p>W dan E</p>\r\n', 'E', '0000-00-00 00:00:00', 692, 636),
(162, 1, 4, 1, NULL, NULL, '<p>Cari yang berbeda dari kelima kata</p>\r\n', '#####<p>Indonesia</p>\r\n', '#####<p>Kamboja</p>\r\n', '#####<p>Laos</p>\r\n', '#####<p>Thailand</p>\r\n', '#####<p>India</p>\r\n', 'E', '0000-00-00 00:00:00', 464, 169),
(163, 1, 4, 1, NULL, NULL, '<p>Cari yang berbeda dari kelima kata</p>\r\n', '#####<p>Libra</p>\r\n', '#####<p>Gemini</p>\r\n', '#####<p>Virgo</p>\r\n', '#####<p>Mars</p>\r\n', '#####<p>Leo</p>\r\n', 'D', '0000-00-00 00:00:00', 553, 76),
(164, 1, 4, 1, NULL, NULL, '<p>Cari yang berbeda dari kelima kata</p>\r\n', '#####<p>Saringan</p>\r\n', '#####<p>Jala</p>\r\n', '#####<p>Kelambu</p>\r\n', '#####<p>Payung</p>\r\n', '#####<p>Tapisan</p>\r\n', 'D', '0000-00-00 00:00:00', 438, 176),
(165, 1, 6, 1, NULL, NULL, '<p>Sinonim dari INTUISI</p>\r\n', '#####<p>Seni</p>\r\n', '#####<p>Baju besi</p>\r\n', '#####<p>Bisikan Hati</p>\r\n', '#####<p>Pertentangan</p>\r\n', '#####<p>Keinginan</p>\r\n', 'C', '0000-00-00 00:00:00', 174, 316),
(166, 1, 6, 1, NULL, NULL, '<p>Sinonim dari PROTEKSI</p>\r\n', '#####<p>Pengamanan</p>\r\n', '#####<p>Pengawasaan</p>\r\n', '#####<p>Perlindungan</p>\r\n', '#####<p>Aturan</p>\r\n', '#####<p>Penjagaan</p>\r\n', 'C', '0000-00-00 00:00:00', 264, 251),
(167, 1, 6, 1, NULL, NULL, '<p>Antonim dari PRAKTIS</p>\r\n', '#####<p>Ahli</p>\r\n', '#####<p>Teoritis</p>\r\n', '#####<p>Cepat</p>\r\n', '#####<p>Simpel</p>\r\n', '#####<p>Efektif</p>\r\n', 'B', '0000-00-00 00:00:00', 300, 213),
(168, 1, 6, 1, NULL, NULL, '<p>Antonim dari HIGENIS</p>\r\n', '#####<p>Kesehatan</p>\r\n', '#####<p>Kotor</p>\r\n', '#####<p>Rusak</p>\r\n', '#####<p>Budaya&nbsp;</p>\r\n', '#####<p>Bersih</p>\r\n', 'B', '0000-00-00 00:00:00', 412, 106),
(169, 1, 2, 1, NULL, NULL, '<p><strong>Petunjuk Soal : Tes ini berupa soal yang terdiri atas deretan angka atau huruf yang belum selesai,&nbsp;dalam setiap deret terdapat suatu pola. Tugas anda mencari angka atau huruf selanjutnya&nbsp;yang sesuai berdasarkan pola - pola tertentu.</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>103...136...126...252...285...270...540...</p>\r\n', '#####<p>550</p>\r\n', '#####<p>1080</p>\r\n', '#####<p>573</p>\r\n', '#####<p>503</p>\r\n', '#####<p>560</p>\r\n', 'C', '0000-00-00 00:00:00', 469, 693),
(170, 1, 2, 1, NULL, NULL, '<p><strong>Petunjuk Soal : Tes ini berupa soal yang terdiri atas deretan angka atau huruf yang belum selesai,&nbsp;dalam setiap deret terdapat suatu pola. Tugas anda mencari angka atau huruf selanjutnya&nbsp;yang sesuai berdasarkan pola - pola tertentu.</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>5...10...20...40...80...</p>\r\n', '#####<p>120</p>\r\n', '#####<p>100</p>\r\n', '#####<p>140</p>\r\n', '#####<p>160</p>\r\n', '#####<p>110</p>\r\n', 'D', '0000-00-00 00:00:00', 912, 236),
(171, 1, 1, 1, NULL, NULL, '<p>Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :</p>\r\n', '#####<p>Aksentologi</p>\r\n', '#####<p>Aksentuasi</p>\r\n', '#####<p>Agostrologi</p>\r\n', '#####<p>Agroikos</p>\r\n', '#####', 'C', '0000-00-00 00:00:00', 847, 254),
(172, 1, 1, 1, NULL, NULL, '<p>Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :</p>\r\n', '#####<p>Manila</p>\r\n', '#####<p>Manula</p>\r\n', '#####<p>Malika</p>\r\n', '#####<p>Makila</p>\r\n', '#####', 'D', '0000-00-00 00:00:00', 837, 272),
(173, 1, 1, 1, NULL, NULL, '<p>Manakah dari kata-kata berikut yang merupakan urutan pertama dalam abjad :</p>\r\n', '#####<p>Garansi</p>\r\n', '#####<p>Garasi</p>\r\n', '#####<p>Garda</p>\r\n', '#####<p>Ganda</p>\r\n', '#####', 'D', '0000-00-00 00:00:00', 762, 326),
(174, 1, 5, 1, NULL, NULL, '<p>Semua penyanyi adalah artis. Sebagian&nbsp;penyanyi adalah bintang film.</p>\r\n\r\n<p>&nbsp;</p>\r\n', '#####<p>Sementara bintang film adalah artis.</p>\r\n', '#####<p>Sementara artis adalah bukan penyanyi.</p>\r\n', '#####<p>Semua bintang film adalah artis.</p>\r\n', '#####<p>Sebagian&nbsp;penyanyi bukan bintang film.</p>\r\n', '#####<p>Sementara penyanyi bukan artis.</p>\r\n', 'D', '0000-00-00 00:00:00', 340, 550),
(175, 1, 3, 1, NULL, NULL, '<p><strong>Cari jawaban yang jenis katanya sama dengan soal atau cari kesamaan hubungan yang sepadan</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>DOKTOR : DISERTASI&nbsp; =&nbsp; ............&nbsp;: ............</p>\r\n', '#####<p>Kyai : Jamaah</p>\r\n', '#####<p>Buruh : Upah</p>\r\n', '#####<p>Sarjana : Skripsi</p>\r\n', '#####<p>Kuliah : Praktikum</p>\r\n', '#####<p>Menteri : Kepmen</p>\r\n', 'C', '0000-00-00 00:00:00', 217, 227),
(176, 1, 2, 1, NULL, NULL, '<p><strong>Petunjuk Soal : Tes ini berupa soal yang terdiri atas deretan angka atau huruf yang belum selesai,&nbsp;dalam setiap deret terdapat suatu pola. Tugas anda mencari angka atau huruf selanjutnya&nbsp;yang sesuai berdasarkan pola - pola tertentu</strong></p>\r\n\r\n<p>15...45...60...40...120...135...</p>\r\n', '#####<p>405</p>\r\n', '#####<p>150</p>\r\n', '#####<p>115</p>\r\n', '#####<p>225</p>\r\n', '#####<p>150</p>\r\n', 'C', '0000-00-00 00:00:00', 404, 744),
(177, 1, 5, 1, NULL, NULL, '<p>Semua orang tua menyayangi anaknya. Sebagian guru menyayangi anaknya.</p>\r\n', '#####<p>Sebagian orang tua menyayangi anaknya</p>\r\n', '#####<p>Sebagian guru adalah orang tua</p>\r\n', '#####<p>Semua guru menyayangi anaknya</p>\r\n', '#####<p>Semua orang tua adalah guru</p>\r\n', '#####<p>Semua guru adalah orang tua</p>\r\n', 'B', '0000-00-00 00:00:00', 391, 519),
(178, 1, 5, 1, NULL, NULL, '<p>Semua burung bernafas dengan paru-paru. Semua merpati adalah burung.</p>\r\n', '#####<p>Semua merpati tidak bernafas dengan paru-paru</p>\r\n', '#####<p>Tidak semua merpati bernafas dengan paru-paru</p>\r\n', '#####<p>Semua merpati bernafas dengan paru-paru</p>\r\n', '#####<p>Sebagian merpati adalah burung</p>\r\n', '#####<p>Sebagian merpati bernafas dengan paru-paru</p>\r\n', 'C', '0000-00-00 00:00:00', 800, 126),
(180, 1, 3, 1, NULL, NULL, '<p><strong>Cari jawaban yang jenis katanya sama dengan soal atau cari kesamaan hubungan yang sepadan</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>NASI : BERAS&nbsp; =&nbsp; TAPE&nbsp;: ............</p>\r\n', '#####<p>NANAS</p>\r\n', '#####<p>SINGKONG</p>\r\n', '#####<p>UBI</p>\r\n', '#####<p>GANDUM</p>\r\n', '#####<p>PISANG</p>\r\n', 'B', '0000-00-00 00:00:00', 417, 44),
(181, 1, 3, 1, NULL, NULL, '<p><strong>Cari jawaban yang jenis katanya sama dengan soal atau cari kesamaan hubungan yang sepadan</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>TAMBANG : EMAS&nbsp; =&nbsp; LAUT&nbsp;: ............</p>\r\n', '#####<p>NELAYAN</p>\r\n', '#####<p>ASIN</p>\r\n', '#####<p>KARANG</p>\r\n', '#####<p>BADAI</p>\r\n', '#####<p>KAPAL</p>\r\n', 'C', '0000-00-00 00:00:00', 248, 194),
(182, 1, 3, 1, NULL, NULL, '<p><strong>Cari jawaban yang jenis katanya sama dengan soal atau cari kesamaan hubungan yang sepadan</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>............ : KARYA SENI&nbsp; =&nbsp; LUCU&nbsp;: ............</p>\r\n', '#####<p>SASTRA - LAWAK</p>\r\n', '#####<p>PENARI - JENAKA</p>\r\n', '#####<p>INDAH - BADUT</p>\r\n', '#####<p>LUKISAN - TERTAWA</p>\r\n', '#####<p>SENIMAN - LELUCON</p>\r\n', 'C', '0000-00-00 00:00:00', 33, 431),
(183, 1, 3, 1, NULL, NULL, '<p><strong>Cari jawaban yang jenis katanya sama dengan soal atau cari kesamaan hubungan yang sepadan</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;RABUN : ............&nbsp; =&nbsp; GAGAP&nbsp;: ............</p>\r\n', '#####<p>KACA MATA - LIDAH</p>\r\n', '#####<p>PANDANGAN - MULUT</p>\r\n', '#####<p>JAUH - TIDAK JELAS</p>\r\n', '#####<p>MATA - BICARA</p>\r\n', '#####<p>BUTA - BISU</p>\r\n', 'E', '0000-00-00 00:00:00', 21, 424),
(184, 1, 3, 1, NULL, NULL, '<p><strong>Cari jawaban yang jenis katanya sama dengan soal atau cari kesamaan hubungan yang sepadan</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp; ............ : KATAK&nbsp; =&nbsp; ULAT&nbsp;: ............</p>\r\n', '#####<p>SERANGGA - KUPU-KUPU</p>\r\n', '#####<p>NYAMUK - BURUNG</p>\r\n', '#####<p>KOLAM - BULU</p>\r\n', '#####<p>BERLENDIR - GATAL</p>\r\n', '#####<p>SAWAH - KEPOMPONG</p>\r\n', 'B', '0000-00-00 00:00:00', 117, 360),
(185, 1, 3, 1, NULL, NULL, '<p><strong>Cari jawaban yang jenis katanya sama dengan soal atau cari kesamaan hubungan yang sepadan</strong></p>\r\n\r\n<p>APOTKER : OBAT = ............. : .............</p>\r\n', '#####<p>GURU : SEKOLAH</p>\r\n', '#####<p>KOKI : MASAKAN</p>\r\n', '#####<p>MONTIR : BENGKEL</p>\r\n', '#####<p>MAHASISWA : DOSEN</p>\r\n', '#####<p>PENJAHAT : PENJARA</p>\r\n', 'B', '0000-00-00 00:00:00', 335, 124),
(186, 1, 3, 1, NULL, NULL, '<p>LICIN : .......... = BERDURI : ...........</p>\r\n', '#####<p>TERPELESET : TERTUSUK</p>\r\n', '#####<p>LENDIR : TAJAM&nbsp;</p>\r\n', '#####<p>BELUT : LANDAK</p>\r\n', '#####<p>BASAH : KERING</p>\r\n', '#####<p>BAHAYA : SAKIT</p>\r\n', 'C', '0000-00-00 00:00:00', 103, 361),
(187, 1, 3, 1, NULL, NULL, '<p><strong>Cari jawaban yang jenis katanya sama dengan soal atau cari kesamaan hubungan yang sepadan</strong></p>\r\n\r\n<p>GELOMBANG : OMBAK = .............. : ...............</p>\r\n', '#####<p>GUNUNG : BUKIT</p>\r\n', '#####<p>BERENANG : LARI</p>\r\n', '#####<p>DANAU : LAUT</p>\r\n', '#####<p>NUSA : PULAU</p>\r\n', '#####<p>GURUN : GERSANG</p>\r\n', 'A', '0000-00-00 00:00:00', 205, 223),
(188, 1, 3, 1, NULL, NULL, '<p><strong>Cari jawaban yang jenis katanya sama dengan soal atau cari kesamaan hubungan yang sepadan</strong></p>\r\n\r\n<p>KEHIDUPAN&nbsp;: ............ = KEHAMILAN&nbsp;: ...............</p>\r\n', '#####<p>MATI : LAHIR</p>\r\n', '#####<p>NYAWA : IBU</p>\r\n', '#####<p>BAHGIA : JANIN</p>\r\n', '#####<p>TUA : BAYI</p>\r\n', '#####<p>BERAKHIR : AWAL</p>\r\n', 'A', '0000-00-00 00:00:00', 184, 252),
(189, 1, 6, 1, NULL, NULL, '<p>Sinonim dari MOBILITAS</p>\r\n', '#####<p>Lambat</p>\r\n', '#####<p>Motivasi</p>\r\n', '#####<p>Gerak</p>\r\n', '#####<p>Dorongan</p>\r\n', '#####<p>Diam</p>\r\n', 'C', '0000-00-00 00:00:00', 262, 233),
(190, 1, 6, 1, NULL, NULL, '<p>Sinonim dari Dedikasi</p>\r\n', '#####<p>Berjaya</p>\r\n', '#####<p>Berjuang</p>\r\n', '#####<p>Pengampunan</p>\r\n', '#####<p>Pengabdian</p>\r\n', '#####<p>Pengalaman</p>\r\n', 'D', '0000-00-00 00:00:00', 309, 177),
(191, 1, 6, 1, NULL, NULL, '<p>Sinonim dari RELATIF</p>\r\n', '#####<p>Statis</p>\r\n', '#####<p>Tentative</p>\r\n', '#####<p>Ukuran</p>\r\n', '#####<p>Nisbi</p>\r\n', '#####<p>abstrak</p>\r\n', 'D', '0000-00-00 00:00:00', 21, 457),
(192, 1, 6, 1, NULL, NULL, '<p>Antonim dari KAPABEL</p>\r\n', '#####<p>Rajin</p>\r\n', '#####<p>Pintar&nbsp;</p>\r\n', '#####<p>Malas</p>\r\n', '#####<p>Bodoh</p>\r\n', '#####<p>Baik</p>\r\n', 'D', '0000-00-00 00:00:00', 81, 433),
(193, 1, 6, 1, NULL, NULL, '<p>Antonim dari LESTARI</p>\r\n', '#####<p>Sementara</p>\r\n', '#####<p>Langgeng</p>\r\n', '#####<p>Bunyi</p>\r\n', '#####<p>Hampa&nbsp;</p>\r\n', '#####<p>Lenggang</p>\r\n', 'A', '0000-00-00 00:00:00', 164, 332),
(194, 1, 6, 1, NULL, NULL, '<p>Antonim dari KRUSIAL</p>\r\n', '#####<p>Berarti</p>\r\n', '#####<p>Berharga</p>\r\n', '#####<p>Penting</p>\r\n', '#####<p>Biasa</p>\r\n', '#####<p>Sepele</p>\r\n', 'E', '0000-00-00 00:00:00', 110, 396),
(196, 1, 5, 1, NULL, NULL, '<p>Jhon adalah adik Tyas. Nina adalah kakak Jhon&nbsp;dan lebih muda daripada Tyas. Siapa yang paling tua?</p>\r\n', '#####<p>Tyas</p>\r\n', '#####<p>Andre</p>\r\n', '#####<p>John</p>\r\n', '#####<p>Tidak bisa ditentukan</p>\r\n', '#####<p>Tyas dan Nina&nbsp;</p>\r\n', 'A', '0000-00-00 00:00:00', 616, 257),
(197, 1, 5, 1, NULL, NULL, '<p>Tono akan ke luar negeri selama 1 bulan untuk urusan dagangnya sehingga ia harus memilih salah satu dari 3 bawahannya yang akan menggantikannya di kantor. Beberapa informasi yang menjadi dasar keputusan Tono sebagai berikut.<br />\r\nA. Anto cukup kreatif dan cukup baik memimpin namun ia sering sakit sehingga terkesan kurang rajin bekerja.<br />\r\nB. Hasan paling kreatif dan cukup sehat, tetapi masih kalah dari Rudi dalam hal kerajinan dan masih kalah dari Anto dalam kepemimpinan.<br />\r\nC. Dalam kreativitas, Rudi masih di bawah Hasan dan Anto, tetapi ia paling rajin, paling sehat, dan paling bagus memimpin.</p>\r\n\r\n<p>Jika dilihat keunggulan masing-masing aspek, maka orang yang paling besar peluangnya untuk dipilih Tono adalah ?</p>\r\n', '#####<p>Anto</p>\r\n', '#####<p>Hasan</p>\r\n', '#####<p>Rudi</p>\r\n', '#####<p>Anto dan Hasan sama besar peluangya</p>\r\n', '#####<p>Ketiganya sama besar peluanngnya</p>\r\n', 'C', '0000-00-00 00:00:00', 561, 309),
(198, 1, 5, 1, NULL, NULL, '<p>Tono akan ke luar negeri selama 1 bulan untuk urusan dagangnya sehingga ia harus memilih salah satu dari 3 bawahannya yang akan menggantikannya di kantor. Beberapa informasi yang menjadi dasar keputusan Tono sebagai berikut.<br />\r\nA. Anto cukup kreatif dan cukup baik memimpin namun ia sering sakit sehingga terkesan kurang rajin bekerja.<br />\r\nB. Hasan paling kreatif dan cukup sehat, tetapi masih kalah dari Rudi dalam hal kerajinan dan masih kalah dari Anto dalam kepemimpinan.<br />\r\nC. Dalam kreativitas, Rudi masih di bawah Hasan dan Anto, tetapi ia paling rajin, paling sehat, dan paling bagus memimpin.</p>\r\n\r\n<p>Pernyataan berikut yang tidak tepat adalah ?</p>\r\n', '#####<p>Anto masih lebih rajin daripada Hasan dan lebih baik dalam memimpin.</p>\r\n', '#####<p>Anto dan Rudi mengalahkan Hasan dalam hal kepemimpinan.</p>\r\n', '#####<p>Anton dan Hasan masih kalah sehat dibandingkan dengan Rudi</p>\r\n', '#####<p>Kekurangan Anto dibandingkan dengan Hasan dan Rudi adalah dalam hal kerajinan dan kesehatan.</p>\r\n', '#####<p>Tidak ada yang dapat mengalahkan Hasan dalam hal kreativitas.</p>\r\n', 'A', '0000-00-00 00:00:00', 231, 687),
(199, 1, 5, 1, NULL, NULL, '<p>Tono akan ke luar negeri selama 1 bulan untuk urusan dagangnya sehingga ia harus memilih salah satu dari 3 bawahannya yang akan menggantikannya di kantor. Beberapa informasi yang menjadi dasar keputusan Tono sebagai berikut.<br />\r\nA. Anto cukup kreatif dan cukup baik memimpin namun ia sering sakit sehingga terkesan kurang rajin bekerja.<br />\r\nB. Hasan paling kreatif dan cukup sehat, tetapi masih kalah dari Rudi dalam hal kerajinan dan masih kalah dari Anto dalam kepemimpinan.<br />\r\nC. Dalam kreativitas, Rudi masih di bawah Hasan dan Anto, tetapi ia paling rajin, paling sehat, dan paling bagus memimpin.</p>\r\n\r\n<p>Calon pengganti Tono yang paling berpeluang untuk dipilih secara berurutan adalah ?</p>\r\n', '#####<p>Hasan - Rudi - Anto</p>\r\n', '#####<p>Anto - Rudi - Hasan</p>\r\n', '#####<p>Hasan - Anto&nbsp;- Rudi</p>\r\n', '#####<p>Rudi - Hasan - Anto</p>\r\n', '#####<p>Rudi - Anto - Hasan</p>\r\n', 'D', '0000-00-00 00:00:00', 353, 487),
(200, 1, 5, 1, NULL, NULL, '<p>Kejujuran S tidak sebaik D. Terkadang M kurang jujur, tapi sesungguhnya dia masih lebih jujur daripada R. B lebih suka berbohong daripada H. D cukup jujur, tapi secara umum M lebih jujur daripada D. Dan I sama jujurnya dengan K. D lebih jujur daripada H dan K.<br />\r\nSiapakah di antara mereka yang paling jujur?</p>\r\n', '#####<p>K</p>\r\n', '#####<p>H</p>\r\n', '#####<p>M</p>\r\n', '#####<p>I</p>\r\n', '#####<p>D</p>\r\n', 'C', '0000-00-00 00:00:00', 489, 368),
(201, 1, 5, 1, NULL, NULL, '<p>Suatu proyek pembangunan terdiri atas beberapa jenis proyek kecil, yakni proyek P, Q, R, S, T, dan U. Proyek kecil ini berkaitan satu dengan yang lain sehingga tiap-tiap jenis pekerjaan diatur sebagai berikut:</p>\r\n\r\n<ul>\r\n	<li>Proyek Q tidak boleh dikerjakan bersamaan dengan proyek S</li>\r\n	<li>Proyek P boleh dikerjakan bersama dengan proyek T</li>\r\n	<li>Proyek Q hanya boleh dikerjakan bersama dengan proyek R</li>\r\n	<li>Proyek T dikerjakan jika dan hanya jika proyek U dikerjakan</li>\r\n</ul>\r\n\r\n<p>&nbsp;<strong>Jika pekerja tidak mengerjakan proyek R, maka ?</strong></p>\r\n', '#####<p>Pekerja tidak akan mengerjakan proyek Q<br />\r\n&nbsp;</p>\r\n', '#####<p>Pekerja tidak akan mengerjakan proyek S</p>\r\n', '#####<p>Pekerja tidak akan mengerjakan proyek P</p>\r\n', '#####<p>Pekerja tidak akan mengerjakan proyek U</p>\r\n', '#####<p>Pekerja tidak akan mengerjakan proyek T</p>\r\n', 'A', '0000-00-00 00:00:00', 542, 299),
(202, 1, 4, 1, NULL, NULL, '<p>Carilah yang berbeda dari kelima kata</p>\r\n', '#####<p>Kotak</p>\r\n', '#####<p>Persegi&nbsp;</p>\r\n', '#####<p>Lingkaran</p>\r\n', '#####<p>Trapesium</p>\r\n', '#####<p>Segitiga</p>\r\n', 'C', '0000-00-00 00:00:00', 319, 316),
(203, 1, 4, 1, NULL, NULL, '<p>Carilah yang berbeda dari kelima kata</p>\r\n', '#####<p>Mesir</p>\r\n', '#####<p>Israel</p>\r\n', '#####<p>Nigeria</p>\r\n', '#####<p>Ghana</p>\r\n', '#####<p>Kamerun</p>\r\n', 'B', '0000-00-00 00:00:00', 239, 375);

-- --------------------------------------------------------

--
-- Table structure for table `tr_guru_mapel`
--

CREATE TABLE `tr_guru_mapel` (
  `id` int(6) NOT NULL,
  `id_guru` int(6) NOT NULL,
  `id_mapel` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tr_guru_mapel`
--

INSERT INTO `tr_guru_mapel` (`id`, `id_guru`, `id_mapel`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6);

-- --------------------------------------------------------

--
-- Table structure for table `tr_guru_tes`
--

CREATE TABLE `tr_guru_tes` (
  `id` int(6) NOT NULL,
  `id_guru` int(6) NOT NULL,
  `id_mapel` int(6) NOT NULL,
  `nama_ujian` varchar(200) NOT NULL,
  `jumlah_soal` int(6) NOT NULL,
  `waktu` int(6) NOT NULL,
  `jenis` enum('acak','set') NOT NULL,
  `detil_jenis` varchar(500) NOT NULL,
  `tgl_mulai` datetime NOT NULL,
  `terlambat` int(3) NOT NULL,
  `token` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tr_guru_tes`
--

INSERT INTO `tr_guru_tes` (`id`, `id_guru`, `id_mapel`, `nama_ujian`, `jumlah_soal`, `waktu`, `jenis`, `detil_jenis`, `tgl_mulai`, `terlambat`, `token`) VALUES
(1, 1, 1, 'PSIKOTES', 40, 20, 'acak', '', '2024-03-20 09:15:00', 180, 'MTIHY'),
(2, 1, 6, 'PSIKOTES', 20, 10, 'acak', '', '2024-03-15 09:00:00', 180, 'ISLCT'),
(3, 1, 3, 'PSIKOTES', 20, 20, 'acak', '', '2024-03-01 09:15:00', 180, 'DLVYY'),
(4, 1, 5, 'PSIKOTES', 20, 25, 'acak', '', '2024-03-20 09:15:00', 180, 'VSQLJ'),
(5, 1, 4, 'PSIKOTES', 40, 15, 'acak', '', '2024-03-15 09:00:00', 180, 'UVMZF'),
(6, 1, 2, 'PSIKOTES', 20, 30, 'acak', '', '2024-03-20 09:15:00', 180, 'UUGNP');

-- --------------------------------------------------------

--
-- Table structure for table `tr_ikut_ujian`
--

CREATE TABLE `tr_ikut_ujian` (
  `id` int(6) NOT NULL,
  `id_tes` int(6) NOT NULL,
  `id_user` int(6) NOT NULL,
  `list_soal` longtext NOT NULL,
  `list_jawaban` longtext NOT NULL,
  `jml_benar` int(6) NOT NULL,
  `nilai` decimal(10,2) NOT NULL,
  `nilai_bobot` decimal(10,2) NOT NULL,
  `tgl_mulai` datetime NOT NULL,
  `tgl_selesai` datetime NOT NULL,
  `status` enum('Y','N') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tr_ikut_ujian`
--


--
-- Indexes for dumped tables
--

--
-- Indexes for table `m_admin`
--
ALTER TABLE `m_admin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kon_id` (`kon_id`);

--
-- Indexes for table `m_guru`
--
ALTER TABLE `m_guru`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_mapel`
--
ALTER TABLE `m_mapel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_siswa`
--
ALTER TABLE `m_siswa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_soal`
--
ALTER TABLE `m_soal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_guru` (`id_guru`),
  ADD KEY `id_mapel` (`id_mapel`);

--
-- Indexes for table `tr_guru_mapel`
--
ALTER TABLE `tr_guru_mapel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_guru` (`id_guru`),
  ADD KEY `id_mapel` (`id_mapel`);

--
-- Indexes for table `tr_guru_tes`
--
ALTER TABLE `tr_guru_tes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_guru` (`id_guru`),
  ADD KEY `id_mapel` (`id_mapel`);

--
-- Indexes for table `tr_ikut_ujian`
--
ALTER TABLE `tr_ikut_ujian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tes` (`id_tes`),
  ADD KEY `id_user` (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `m_admin`
--
ALTER TABLE `m_admin`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `m_guru`
--
ALTER TABLE `m_guru`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `m_mapel`
--
ALTER TABLE `m_mapel`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `m_siswa`
--
ALTER TABLE `m_siswa`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `m_soal`
--
ALTER TABLE `m_soal`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=204;

--
-- AUTO_INCREMENT for table `tr_guru_mapel`
--
ALTER TABLE `tr_guru_mapel`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tr_guru_tes`
--
ALTER TABLE `tr_guru_tes`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tr_ikut_ujian`
--
ALTER TABLE `tr_ikut_ujian`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
