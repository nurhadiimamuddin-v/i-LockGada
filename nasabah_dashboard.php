
     <?php
     include "header.php";
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
                <div class="card-body dashboard-tabs p-0">
                  
                  <div class="tab-content py-0 px-0">
                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                      <div class="d-flex flex-wrap justify-content-xl-between">
                        
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-account mr-3 icon-lg" style="color: #20a1ad;"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Nama Pegawai</small>
                            <h5 class="mr-2 mb-0"><?php echo $_SESSION['nama']; ?></h5>
                          </div>
                        </div>

                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-account-card-details mr-3 icon-lg text-success"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">NIK Pegawai</small>
                            <h5 class="mr-2 mb-0"><?php echo $_SESSION['nik']; ?></h5>
                          </div>
                        </div>
                        <div class="d-flex py-3 border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-contact-mail mr-3 icon-lg" style="color: #152453;"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Tempat, Tanggal Lahir</small>
                            <h5 class="mr-2 mb-0"><?php echo $_SESSION['tempat_lahir']; ?>, <?php echo $_SESSION['tanggal_lahir']; ?></h5>
                          </div>
                        </div>
                        <div class="d-flex py-3 border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-account-key mr-3 icon-lg" style="color: #00ab4d;"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Jabatan</small>
                            <h5 class="mr-2 mb-0">Pegawai</h5>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="sales" role="tabpanel" aria-labelledby="sales-tab">
                      <div class="d-flex flex-wrap justify-content-xl-between">
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-google-physical-web mr-3 icon-lg text-success"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Kode Pegadaian</small>
                            <h5 class="mr-2 mb-0">2233783</h5>
                          </div>
                        </div>
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-bank mr-3 icon-lg" style="color: #20a1ad;"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Alamat Pegadaian</small>
                            <h5 class="mr-2 mb-0">Mastrip, Sumbersari, Kabupaten Jember, Jawa Timur</h5>
                          </div>
                        </div>
                        
                      </div>
                    </div>
                    <div class="tab-pane fade" id="purchases" role="tabpanel" aria-labelledby="purchases-tab">
                      <div class="d-flex flex-wrap justify-content-xl-between">

                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-information-outline mr-3 icon-lg" style="color: #152453;"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Barang Yang di gadaikan</small>
                            <h5 class="mr-2 mb-0">9</h5>
                          </div>
                        </div>
                       

                        <div class="d-flex py-3 border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-checkbox-marked mr-3 icon-lg" style="color: #20a1ad;"></i>
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
                  <p class="card-title">Data Pegawai </p>
                  <div class="table-responsive">
                 <table id="example" class="display" style="width:100%">
  <thead>
    <tr>
      <th>No</th>
      <th>Kode Locker</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <?php
    include 'config.php';
    $no = 1;
    $pegadaian_id = $_SESSION['pegadaian_id'];
    $query = mysqli_query($conn, "SELECT * FROM locker WHERE pegadaian_id = '$pegadaian_id'");

    while ($data = mysqli_fetch_assoc($query)) {
    ?>
    <tr>  
      <td><?= $no++; ?></td>
      <td><?= htmlspecialchars($data['kode_locker']); ?></td>
      <td><?= htmlspecialchars($data['status']); ?></td>
    </tr>
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
     include "footer.php";
     ?>