<!DOCTYPE html>
<html lang="en">
<base href="">
<?php
require_once('koneksi.php') ?>

<head>
  <meta charset="utf-8">
  <title>Web Generate QR-Code</title>
  <meta name="description" content="particles.js is a lightweight JavaScript library for creating particles.">
  <meta name="author" content="Vincent Garreau" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <link rel="stylesheet" media="screen" href="css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="node_modules/qrious/dist/qrious.js"></script>
  <script src="node_modules/jszip/dist/jszip.js"></script>
  <script src="node_modules/jszip/vendor/FileSaver.js"></script>
  <style>
    canvas {
      max-width: 100%;
    }
  </style>
</head>

<body>
  <!-- particles.js container -->
  <div class="container">
    <div id="particles-js"></div>
    <div class="row mt-5">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header mt-1">
            <h4 class="text-center">Generate QR-Code Tiket Konser</h4>
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-2">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header">Grafik Upload Template</div>
          <div class="card-body">
            <div>
              <canvas id="myChart" style="width: 100%;"></canvas>
            </div>
            <?php

            $begin = new DateTime(date('Y-m') . '-01');
            $end = new DateTime(date('Y-m-t'));
            $list_tanggal = [];
            $list_data = [];
            for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
              $tanggal = $i->format("Y-m-d");
              $result = mysqli_query($koneksi, "SELECT COUNT(id) AS total FROM images WHERE DATE(waktu_upload)='$tanggal'");
              $row = mysqli_fetch_assoc($result);
              $list_data[] = $row['total'];
              $list_tanggal[] = $tanggal;
            }
            ?>
            <script>
              const ctx = document.getElementById('myChart');
              new Chart(ctx, {
                type: 'bar',
                data: {
                  labels: <?= json_encode($list_tanggal) ?>,
                  datasets: [{
                    label: 'Data Upload Template',
                    data: <?= json_encode($list_data) ?>,
                    borderWidth: 1
                  }]
                },
                options: {
                  scales: {
                    y: {
                      beginAtZero: true
                    }
                  }
                }
              });
            </script>
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-2 pb-5">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header">Tabel Upload Template</div>
          <div class="card-body">
            <!-- <button class="btn btn-primary mb-3" onclick="showModalUploadTemplate()">Upload Template</button> -->
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modal-upload-template">
              Upload Template
            </button>
            <table class="table table-bordered table-condensed">
              <thead>
                <th style="width: 7%;">No.</th>
                <th>Keterangan</th>
                <th style="width: 17%;text-align:center">Aksi</th>
              </thead>
              <?php $result = mysqli_query($koneksi, "SELECT * FROM images ORDER BY waktu_upload DESC");
              foreach ($result as $key => $res) { ?>
                <tr>
                  <td><?= $key + 1 ?></td>
                  <td><?= $res['keterangan'] ?></td>
                  <td>
                    <button type="button" class="btn btn-sm btn-info" onclick="lihatTemplate('<?= $res['path'] ?>')">Lihat</button>
                    <button type="button" class="btn btn-sm btn-primary" onclick="generateQr('<?= $res['path'] ?>')">Generate QR</button>
                  </td>
                </tr>
              <?php } ?>
            </table>
          </div>

          <!-- Modal -->
          <div class="modal fade" id="modal-upload-template" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-upload-template-label" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="modal-upload-template-label">Upload Template</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="proses.php" enctype="multipart/form-data">
                  <div class="modal-body">
                    <div class="mb-3">
                      <label for="keterangan" class="form-label">Keterangan</label>
                      <input type="text" class="form-control" id="keterangan" name="keterangan" required>
                    </div>
                    <div class="mb-3">
                      <label for="file_template" class="form-label">Pilih File Template</label>
                      <input type="file" class="form-control" id="file_template" name="file_template" accept="image/*" required>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <div class="modal fade" id="modal-lihat-template" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-lihat-template-label" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="modal-lihat-template-label">Template</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <img src="" alt="" id="img-template" class="thumbnail" width="100%">
                </div>
              </div>
            </div>
          </div>
          <script>
            function lihatTemplate(params) {
              var myModal = new bootstrap.Modal(document.getElementById("modal-lihat-template"), {});
              var set_image = params;
              img = document.getElementById('img-template');
              img.setAttribute('src', set_image)
              myModal.show();
            }
          </script>

          <div class="modal fade" id="modal-generate-template" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-generate-template-label" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="modal-generate-template-label">Generate QR-Code</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-sm-4">
                      <input type="number" class="form-control" id="start" placeholder="Start Number">
                    </div>
                    <div class="col-sm-4">
                      <input type="number" class="form-control" id="end" placeholder="End Number">
                    </div>
                    <div class="col-sm-4">
                      <input type="number" class="form-control" id="size_qr" placeholder="Size QR" value="350">
                    </div>
                    <div class="col-sm-3 mt-3">
                      <button type="button" class="btn btn-primary" onclick="setGenerateQr()">Generate QR</button>
                    </div>
                  </div>
                  <hr>
                  <div id="setPosisiQr" style="display: none;">
                    <div class="row mt-3 mb-3">
                      <div class="col-sm-3">
                        <input type="number" class="form-control" id="position_x" placeholder="X">
                      </div>
                      <div class="col-sm-3">
                        <input type="number" class="form-control" id="position_y" placeholder="Y">
                      </div>
                      <div class="col-sm-2">
                        <button type="button" class="btn btn-primary" onclick="previewQr()">Preview</button>
                      </div>
                      <div class="col-sm-2">
                        <button type="button" class="btn btn-success" onclick="downloadQr()">Download</button>
                      </div>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-6">
                      <img src="" alt="" id="img-template-generate" style="display: none;">
                      <img src="" alt="" id="img-template-generate-preview" class="thumbnail" width="100%">
                    </div>
                    <div class="col-sm-6">
                      <div id="setImages" style="display: none;"></div>
                      <canvas id="canvasPreview"></canvas>
                      <canvas id="canvasResult" style="display: none;"></canvas>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <script>
            var myModal = new bootstrap.Modal(document.getElementById("modal-generate-template"), {});

            function generateQr(params) {
              var set_image = params;
              img = document.getElementById('img-template-generate');
              img.setAttribute('src', set_image)
              img = document.getElementById('img-template-generate-preview');
              img.setAttribute('src', set_image)
              myModal.show();
            }

            function setGenerateQr() {
              var start = document.getElementById('start').value;
              var end = document.getElementById('end').value;
              if (start == '' || end == '') {
                alert("Tentukan start/end terlebih dahulu");
                return false;
              }
              if (parseInt(start) > parseInt(end)) {
                alert("Start Number Lebih Besar Dari End Number")
                return false;
              }

              if (parseInt(end) - parseInt(start) > 50) {
                alert("Maksimal 50 QR-Code")
                return false;
              }

              document.getElementById('setImages').innerHTML = '';

              var size_qr = document.getElementById('size_qr').value;
              for (let index = start; index <= end; index++) {
                var img = document.createElement('img');
                img.setAttribute("id", index);
                document.getElementById('setImages').appendChild(img);
                new QRious({
                  element: document.getElementById(index),
                  size: size_qr,
                  value: index.toString()
                });
              }
              var setPosisiQr = document.getElementById('setPosisiQr');
              setPosisiQr.style.display = 'block';
            }

            function downloadQr() {
              var zip = new JSZip();
              var result_canvas = document.getElementById("canvasResult");
              var context = result_canvas.getContext("2d");
              var image_template = document.getElementById('img-template-generate');
              result_canvas.width = image_template.width;
              result_canvas.height = image_template.height;

              var start = document.getElementById('start').value;
              var end = document.getElementById('end').value;
              var x = document.getElementById('position_x').value;
              var y = document.getElementById('position_y').value;
              for (let index = start; index <= end; index++) {
                context.drawImage(image_template, 0, 0);
                let img_qr = document.getElementById(index);
                context.drawImage(img_qr, x, y);

                var image = getBase64Image(result_canvas);
                zip.file("qr_" + index + ".png", image, {
                  base64: true
                });
              }
              zip.generateAsync({
                type: "blob"
              }).then(function(content) {
                saveAs(content, "qr_code " + start + "-" + end + ".zip");
              });
            }

            function previewQr() {
              canvasPreview = document.getElementById('canvasPreview');
              var ctx_preview = canvasPreview.getContext("2d");
              image_template = document.getElementById('img-template-generate');
              canvasPreview.width = image_template.width;
              canvasPreview.height = image_template.height;
              ctx_preview.drawImage(image_template, 0, 0);

              var index = document.getElementById('start').value;
              var x = document.getElementById('position_x').value;
              var y = document.getElementById('position_y').value;
              img_qr = document.getElementById(index);
              ctx_preview.drawImage(img_qr, x, y);
            }

            function getBase64Image(img) {
              var canvas = document.createElement("canvas");
              canvas.width = img.width;
              canvas.height = img.height;
              var ctx = canvas.getContext("2d");
              ctx.drawImage(img, 0, 0);
              var dataURL = canvas.toDataURL("image/png");
              return dataURL.replace(/^data:image\/(png|jpg);base64,/, "");
            }
          </script>
        </div>
      </div>
    </div>
  </div>

  <!-- scripts -->
  <script src="particles.js/particles.min.js"></script>
  <script src="js/app.js"></script>
</body>

</html>