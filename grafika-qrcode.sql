-- phpMyAdmin SQL Dump
-- version 5.1.3deb1+focal2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 02 Jan 2023 pada 19.37
-- Versi server: 8.0.31-0ubuntu0.20.04.1
-- Versi PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `grafika-qrcode`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `images`
--

CREATE TABLE `images` (
  `id` int NOT NULL,
  `keterangan` varchar(200) NOT NULL,
  `path` varchar(300) NOT NULL,
  `waktu_upload` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Dumping data untuk tabel `images`
--

INSERT INTO `images` (`id`, `keterangan`, `path`, `waktu_upload`) VALUES
(1, 'Konser Kotak - Ex. Bandara - Reguler', 'uploads/6aaa081b-0313-47f1-b408-605b61528c8e.jpeg', '2023-01-02 12:14:31'),
(4, 'Konser Mahalini', 'uploads/1.png', '2023-01-01 15:59:42'),
(5, 'Konser Kotak - Ex. Bandara - VIP', 'uploads/83876bb6-558a-4a96-904a-f39bc472479c.jpeg', '2023-01-02 16:02:14');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `images`
--
ALTER TABLE `images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
