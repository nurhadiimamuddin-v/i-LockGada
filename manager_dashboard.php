
<?php
     include "atas.php";
     
     ?>
     <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="d-flex justify-content-between flex-wrap">
                <div class="d-flex align-items-end flex-wrap">
                  <div class="mr-md-3 mr-xl-5">
                    <h2>Welcome Back to Smart Locker Pegadaian,</h2>
                    <p class="mb-md-0"> Sistem penyimpanan pintar berbasis teknologi yang digunakan oleh PT Pegadaian (Persero) </p>
                    <p class="mb-md-0">untuk memberikan layanan mandiri kepada nasabah dalam menyimpan, mengambil, atau menebus barang gadai</p>
                  </div>
                  <div class="d-flex">
                    <img src="images/ty.png" alt="logo" style="height: 83px; margin-left: 150px;" class="d-none d-md-block">
                  </div>
                </div>
                
              </div>
            </div>
          </div>
          <div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">

        <div class="d-flex flex-wrap justify-content-xl-between mb-1">
          <div class="d-flex flex-grow-1 align-items-center justify-content-center p-3 item" style="min-width: 0;">
            <a href="export.php" class="btn btn-block" style="background-color: #152453; color: white; min-width: 180px; min-height: 50px; font-size: 1rem;">
              <i class="mdi mdi-download mr-2"></i> Barang Yang Digadaikan
            </a>
          </div>
          <div class="d-flex flex-grow-1 align-items-center justify-content-center p-3 item" style="min-width: 0;">
            <a href="export_file.php" class="btn btn-block" style="background-color: #73bf43; color: white; min-width: 180px; min-height: 50px; font-size: 1rem;">
              <i class="mdi mdi-download mr-2"></i> Barang Yang Telah Diambil
            </a>
          </div>
          <div class="d-flex flex-grow-1 align-items-center justify-content-center p-3 item" style="min-width: 0;">
            <a href="export_data.php" class="btn btn-block" style="background-color: #20a1ad; color: white; min-width: 180px; min-height: 50px; font-size: 1rem;">
              <i class="mdi mdi-download mr-2"></i> Data Pegawai Pegadaian
            </a>
          </div>
        </div>




      </div> <!-- end card-body -->
    </div>
  </div>
</div>

         <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body dashboard-tabs p-0">
                  <ul class="nav nav-tabs px-4" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="overview-tab" data-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Profile Manager</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="sales-tab" data-toggle="tab" href="#sales" role="tab" aria-controls="sales" aria-selected="false">Info Pegadaian</a>
                    </li>
                   
                  </ul>
                  <div class="tab-content py-0 px-0">
                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                      <div class="d-flex flex-wrap justify-content-xl-between">
                        
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                        <i class="mdi mdi-contact-mail mr-3 icon-lg" style="color: #bed33b;"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Nama Manager</small>
                            <h5 class="mr-2 mb-0"><?php echo $_SESSION['nama']; ?></h5>
                          </div>
                        </div>
                         <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-clipboard-account mr-3 icon-lg" style="color: #152453;"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Jabatan</small>
                            <h5 class="mr-2 mb-0"><?php echo $_SESSION['role']; ?></h5>
                          </div>
                        </div>

                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-clipboard-account mr-3 icon-lg" style="color: #00ab4d;"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Username Manager</small>
                            <h5 class="mr-2 mb-0"><?php echo $_SESSION['username']; ?></h5>
                          </div>
                        </div>
                       
                      </div>
                    </div>
                    <div class="tab-pane fade" id="sales" role="tabpanel" aria-labelledby="sales-tab">
                      <div class="d-flex flex-wrap justify-content-xl-between">
                       
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-bank mr-3 icon-lg text-success" ></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Kode Pegadaian</small>
                            <h5 class="mr-2 mb-0"><?php echo $_SESSION['kode_pegadaian']; ?></h5>
                          </div>
                        </div>
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                           <i class="mdi mdi-bank mr-3 icon-lg" style="color: #20a1ad;"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Lokasi Pegadaian</small>
                            <h5 class="mr-2 mb-0"><?php echo $_SESSION['lokasi_pegadaian']; ?></h5>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="purchases" role="tabpanel" aria-labelledby="purchases-tab">
                      <div class="d-flex flex-wrap justify-content-xl-between">

                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-information-outline mr-3 icon-lg text-success"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Barang Yang di gadaikan</small>
                            <h5 class="mr-2 mb-0">9</h5>
                          </div>
                        </div>
                       

                        <div class="d-flex py-3 border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-checkbox-marked mr-3 icon-lg text-primary"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Barang yang sudah di ambil</small>
                            <h5 class="mr-2 mb-0">9</h5>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 stretch-card">
              <div class="card">
                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
          <p class="card-title mb-0">Data Pegawai Pegadaian <?php echo $_SESSION['lokasi_pegadaian']; ?></p>
          <!-- <a href="export_data.php" class="btn btn-success" ><i class="mdi mdi-download"></i>  Download
          </a> -->
        </div>
                  <div class="table-responsive">
                  

 <table id="example" class="display" style="width:100%">
  <thead>
    <tr>
      <th>No</th>
      <th>Nama Pegawai</th>
      <th>Tempat Lahir</th>
      <th>Tanggal Lahir</th>
    </tr>
  </thead>
  <tbody>
    <?php
    include 'config.php';
    $no = 1;
    $pegadaian_id = $_SESSION['pegadaian_id'];
$query = mysqli_query($conn, "SELECT * FROM nasabah WHERE pegadaian_id = '$pegadaian_id'");


    while ($data = mysqli_fetch_assoc($query)) {
    ?>
    <tr>
      <td><?= $no++; ?></td>
      <td><?= htmlspecialchars($data['nama']); ?></td>
      <td><?= htmlspecialchars($data['tempat_lahir']); ?></td>
      <td>
        <?php
          $tanggal = $data['tanggal_lahir'];
          // Format: 01 Januari 2000
          echo date('d F Y', strtotime($tanggal));
        ?>
      </td> </tr>
    <?php } ?>
  </tbody>
</table>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
     <?php
     include "bawah.php";
     ?>