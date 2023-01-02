<?php 

$koneksi = mysqli_connect('localhost','root','root','grafika-qrcode');
if (!$koneksi) {
  die("Koneksi dengan database gagal ".mysqli_connect_error());
}