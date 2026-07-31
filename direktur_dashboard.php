
<?php
     include "head.php";
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
                    <p class="mb-md-0"> Selamat Datang kepada Bapak <mark class="bg-success text-black">Direktur</mark> Pegadaian (BUMN) </p>
                    <p class="mb-md-0"> Mengatasi Masalah tanpa Masalah </p>
                  </div>
                  <div class="d-flex">
                    <img src="images/ty.png" alt="logo" style="height: 83px; margin-left: 250px;" class="d-none d-md-block">
                  </div>
                </div>
                
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body dashboard-tabs p-0">
                  <ul class="nav nav-tabs px-4" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="overview-tab" data-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Profile Direktur</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="sales-tab" data-toggle="tab" href="#sales" role="tab" aria-controls="sales" aria-selected="false">Pegadaian</a>
                    </li>
                   
                  </ul>
                  <div class="tab-content py-0 px-0">
                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                      <div class="d-flex flex-wrap justify-content-xl-between">
                        
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-account mr-3 icon-lg text-success"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Nama</small>
                            <h5 class="mr-2 mb-0"><?php echo $_SESSION['nama']; ?></h5>
                          </div>
                        </div>

                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-clipboard-account mr-3 icon-lg" style="color: #20a1ad;"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Jabatan</small>
                            <h5 class="mr-2 mb-0"><?php echo $_SESSION['role']; ?></h5>
                          </div>
                        </div>
                        <div class="d-flex py-3 border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-key mr-3 icon-lg" style="color: #152453;"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Username</small>
                            <h5 class="mr-2 mb-0"><?php echo $_SESSION['username']; ?></h5>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="sales" role="tabpanel" aria-labelledby="sales-tab">
                      <div class="d-flex flex-wrap justify-content-xl-between">
                        <?php
                        include 'config.php';
                        $sql = mysqli_query($conn, "SELECT COUNT(*) AS total FROM pegadaian");
                        $data = mysqli_fetch_assoc($sql);
                        ?>
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-bank mr-3 icon-lg text-success" ></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Jumlah Pegadaian yang sudah terdaftar</small>
                            <h5 class="mr-2 mb-0"><?= $data['total']; ?> Pegadaian</h5>
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
          <p class="card-title mb-0">Data Pegadaian Di Seluruh Indonesia</p>
          <a href="export_excel.php" class="btn" style="color:white; background-color: #20a1ad;"><i class="mdi mdi-download"></i>  Download
          </a>
        </div>
                  <div class="table-responsive">
                  <table id="example" class="display" style="width:100%">
                      <thead>
                        <tr>
                          <th>Kode Pegadaian</th>
                          <th>Lokasi Pegadaian</th>
                          <th>Nama Manager</th>
                          <th>Username Manager</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php
          $no = 1;
          include 'config.php';
          $sql = mysqli_query($conn, "SELECT Manager.*, Pegadaian.kode_pegadaian, Pegadaian.lokasi_pegadaian 
                                          FROM Manager 
                                          JOIN Pegadaian ON Manager.pegadaian_id = Pegadaian.id");
          while ($data = mysqli_fetch_assoc($sql)) {
          ?>
           <tr>
             <td><?= $data['kode_pegadaian']; ?> </td>    
             <td><?= $data['lokasi_pegadaian']; ?> </td>    
             <td><?= $data['nama']; ?> </td>    
             <td><?= $data['username']; ?> </td>    
      
         <?php } ?>
                        </tr>
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
     include "foot.php";
     ?>