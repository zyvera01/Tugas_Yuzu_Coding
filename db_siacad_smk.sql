-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 19 Okt 2026 pada 05.08
-- Versi server: 10.4.6-MariaDB
-- Versi PHP: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_siacad_smk`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `guru`
--

CREATE TABLE `guru` (
  `id` int(11) NOT NULL,
  `nip` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `guru`
--

INSERT INTO `guru` (`id`, `nip`, `nama`, `jenis_kelamin`, `user_id`) VALUES
(8901, '1231', 'aji', 'L', 202612),
(8902, '1232', 'anindita', 'P', 202613),
(8903, '1233', 'dicky', 'L', 202614),
(8904, '1234', 'sulastri', 'P', 202615),
(8905, '1235', 'vera', 'P', 202611),
(8911, '12345', 'Kirana', 'P', 202647),
(8912, '0937', 'giring', 'L', 202648);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mapel`
--

CREATE TABLE `mapel` (
  `id` int(11) NOT NULL,
  `kode_mapel` varchar(10) NOT NULL,
  `nama_mapel` varchar(100) NOT NULL,
  `guru_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `mapel`
--

INSERT INTO `mapel` (`id`, `kode_mapel`, `nama_mapel`, `guru_id`) VALUES
(981, 'bi02', 'bahasa inggris', 8903),
(982, 'mtk01', 'matematika', 8905),
(983, 'sb01', 'seni budaya', 8901),
(984, 'bi01', 'bahasa indonesia', 8904),
(985, 'ip03', 'ipa', 8902);

-- --------------------------------------------------------

--
-- Struktur dari tabel `nilai_rapor`
--

CREATE TABLE `nilai_rapor` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `nilai_kkm` int(11) NOT NULL,
  `nilai_pengetahuan` int(11) NOT NULL,
  `nilai_keterampilan` int(11) NOT NULL,
  `keterangan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `nilai_rapor`
--

INSERT INTO `nilai_rapor` (`id`, `siswa_id`, `mapel_id`, `nilai_kkm`, `nilai_pengetahuan`, `nilai_keterampilan`, `keterangan`) VALUES
(1, 2, 984, 75, 80, 87, 'baik'),
(2, 1, 982, 75, 99, 99, 'sangat baik'),
(3, 3, 985, 80, 91, 88, 'baik'),
(4, 4, 983, 70, 89, 93, 'baik'),
(5, 5, 982, 75, 90, 86, 'baik');

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswa`
--

CREATE TABLE `siswa` (
  `id` int(11) NOT NULL,
  `nis` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kelas` varchar(10) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `siswa`
--

INSERT INTO `siswa` (`id`, `nis`, `nama`, `kelas`, `jenis_kelamin`, `user_id`) VALUES
(1, '112601', 'bilqis', '11', 'P', 202601),
(2, '112602', 'zyvera', '11', 'L', 202602),
(3, '112603', 'dini', '11', 'P', 202605),
(4, '112604', 'rasya', '11', 'L', 202603),
(5, '112605', 'alwan', '11', 'L', 202604),
(10, '112699', 'bila', '12', 'L', 202639);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','guru','siswa') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(202601, 'bilqis', '$2y$10$RovsiwVWntrkcYI77PAHOuvThpTVNvyWlTdwhIUL4fUYnkX6PPFfG', 'siswa', '2026-08-24 04:45:01'),
(202602, 'Zyvera', '$2y$10$74imMz0hE.ByvI5bhp0ezOg16znXVyrLwq233jjoiqGib10KyGwfm', 'siswa', '2026-08-24 04:45:01'),
(202603, 'rasya', '$2y$10$H6YRjm5ABht0VstZDTIoAOtCb.4bC/v5c8dVcx7pwhUIYAS4IJDRy', 'siswa', '2026-08-24 04:45:01'),
(202604, 'alwan', '$2y$10$K8rO0UmADSb0dNCcHRYCH.JrBMJESAsR4FqxND/RYTl4GVGltTDEa', 'siswa', '2026-08-24 04:45:01'),
(202605, 'dini', '$2y$10$vyzhtUwVyH06V4e17XY0DuNOWSNNvt1JxC7WgZ4vxyN6S1WbEEl42', 'siswa', '2026-08-24 04:45:01'),
(202611, 'vera', '$2y$10$Qaf7LE4Ixs0.970YY1.gkewtgkbM8BJAgJQvSa.cSCsJTigRGJ/p.', 'guru', '2026-08-24 05:02:04'),
(202612, 'aji', '$2y$10$1kIvwUVohvli52GnZ5GryOWm0k8WfRP.HYGIDu/UGWxJG1gqKX952', 'guru', '2026-08-24 05:02:04'),
(202613, 'anindita', '$2y$10$YA24oPkHChSHukf1AYN04u3yfljwtOXHealzvT/.dXGNEg/By8vEC', 'guru', '2026-08-24 05:02:04'),
(202614, 'dicky', '$2y$10$22kL5Mvt9piGPSW2jpNgM.urOxP3r9wwG0oiIZtVzEPdm1sMoiSUO', 'guru', '2026-08-24 05:02:04'),
(202615, 'sulastrii', '$2y$10$3HiudPN7jDBF3Lx8ZP3Tmun3FaTJK267qlMQXf4ZHap1qAI7hXa9K', 'guru', '2026-08-24 05:02:04'),
(202638, 'razka', '$2y$10$fuWaTZCetD06zVpnUtVMjO67VZGtFD3Y9ahYHx5LUcy8uW.dmu4ki', 'admin', '2026-09-14 04:31:34'),
(202639, 'bila', '$2y$10$H4TB2pNDFpxQh4p3V2S2legtwEczj/Of1gmLQzQ9oUhpUsJEbLLMq', 'siswa', '2026-09-14 04:39:37'),
(202647, 'kiraa', '$2y$10$Whcjeqk3x/hwskxVBxPXUOyMu.b7yL7t4DArEihwqJKM87hG0QAn.', 'guru', '2026-10-14 06:58:51'),
(202648, 'giring', '$2y$10$lYzP9551RSqjeaq3eKRzEOv07qxKpxCLAog0yoZWiWqnZIPZQlvhe', 'guru', '2026-10-14 06:59:27'),
(202649, 'nisa', '$2y$10$5wJkUOyVbzLN//K..pNhZ.Qkv5zBJUDX6i5O.142Lix.qFBJL9Lme', 'admin', '2026-10-14 07:16:07'),
(202650, 'ali', '$2y$10$cgYnd1XLbty9w4hoHLZboO3Nt6FI/eVhbsIu0tFL0vANI.NEQ1xUq', 'admin', '2026-10-14 07:16:47');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `mapel`
--
ALTER TABLE `mapel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guru_id` (`guru_id`);

--
-- Indeks untuk tabel `nilai_rapor`
--
ALTER TABLE `nilai_rapor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `siswa_id` (`siswa_id`),
  ADD KEY `mapel_id` (`mapel_id`);

--
-- Indeks untuk tabel `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `guru`
--
ALTER TABLE `guru`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8913;

--
-- AUTO_INCREMENT untuk tabel `mapel`
--
ALTER TABLE `mapel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=988;

--
-- AUTO_INCREMENT untuk tabel `nilai_rapor`
--
ALTER TABLE `nilai_rapor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202651;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `guru`
--
ALTER TABLE `guru`
  ADD CONSTRAINT `guru_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `mapel`
--
ALTER TABLE `mapel`
  ADD CONSTRAINT `mapel_ibfk_1` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `nilai_rapor`
--
ALTER TABLE `nilai_rapor`
  ADD CONSTRAINT `nilai_rapor_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nilai_rapor_ibfk_2` FOREIGN KEY (`mapel_id`) REFERENCES `mapel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
