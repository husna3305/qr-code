<?php
require_once("koneksi.php");
if (isset($_POST['keterangan'])) {
  extract($_POST);
  $file = $_FILES['file_template'];
  $name = $file['name'];
  $path = "uploads/" . basename($name);
  if (move_uploaded_file($file['tmp_name'], $path)) {
    $waktu = date("Y-m-d H:i:s");
    $query = "INSERT INTO images(keterangan,path,waktu_upload) VALUES('$keterangan','$path','$waktu')";
    if (mysqli_query($koneksi, $query)) {
      echo "<script>alert('Berhasil melakukan upload template');window.location='index.php';</script>";
    } else {
      echo "<script>alert('Gagal upload template');history.go(-1)</script>";
    }
  } else {
    echo "<script>alert('Gagal upload template-2');history.go(-1)</script>";
  }
}
