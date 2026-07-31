  <?php
  include "atas.php";
  ?>
  <!-- Modal Edit nasabah -->
  <div class="modal fade" id="modalEditNasabah" tabindex="-1" aria-labelledby="modalEditNasabahLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalEditNasabahLabel">Edit Pegawai Pegadaian</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="edit_nasabah.php" method="POST">
          <div class="modal-body">
            <input type="hidden" id="editIdnasabah" name="id_nasabah">
            <div class="mb-3">
              <label for="Editnama" class="form-label">Nama Pegawai</label>
              <input type="text" class="form-control" id="namanasabah" name="nama" placeholder="Masukkan Nama Pegawai" required>
            </div>
            <div class="mb-3">
              <label for="Editnik" class="form-label">NIK</label>
              <input type="text" class="form-control" id="nik" name="nik" placeholder="Masukkan NIK" required>
            </div>
            <div class="mb-3">
              <label for="Edittempat_lahir" class="form-label">Tempat Lahir</label>
              <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" placeholder="Masukkan Tempat Lahir" required>
            </div>
            <div class="mb-3">
              <label for="Edittanggal_lahir" class="form-label">Tanggal Lahir</label>
              <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
            </div>
            <div class="mb-3">
              <label for="Editpassword" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-success">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin">
                <div class="d-flex justify-content-between flex-wrap">
                  <div class="d-flex align-items-end flex-wrap">
                    <div class="mr-md-3 mr-xl-5">
                      <h2>Data Pegawai Pegadaian</h2>
                      <p class="mb-md-0"> Data akan Memunculkan Pegawai Pegadaian yang sudah terdaftar</p>
                    </div>
                    <div class="d-flex">
                      <img src="images/ty.png" alt="logo" style="height: 63px; margin-left: 500px;" class="d-none d-md-block">
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
                      <p class="card-title mb-0">Data Pegawai Pegadaian</p>
                      <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahnasabah">
                                      </em><span> Tambah</span>
                                  </a>
                      </div>
                    <div class="table-responsive">
                    <table id="example" class="display" style="width:100%">
                        <thead>
                          <tr>
                              <th>No</th>
                              <th>Nama Pegawai</th>
                              <th>NIK Pegawai</th>
                              <th>Tempat Lahir</th>
                              <th>Tanggal Lahir</th>
                              <th>password</th>
                              <th>Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                              $no = 1;
                              include 'config.php';
                              $pegadaian_id = $_SESSION['pegadaian_id'];
  $query = mysqli_query($conn, "SELECT * FROM nasabah WHERE pegadaian_id = '$pegadaian_id'");
                              while ($data = mysqli_fetch_assoc($query)) {
                              ?>
                              <tr>
                                  <td><?= $no++; ?></td>
                                  <td><?= $data['nama']; ?> </td>    
                                  <td><?= $data['nik']; ?> </td>    
                                  <td><?= $data['tempat_lahir']; ?> </td>    
                                  <td><?= $data['tanggal_lahir']; ?> </td>    
                                  <td><a href="password.php?id_nasabah=<?= urlencode($data['id_nasabah']); ?>" target="_blank" style="text-decoration: none; color: blue;"><i class="mdi mdi-eye"></i> View</a></td>
                                  
                                
                                  <td>
                                    <a class="btn" 
                        style="font-size: 14px; color: white; padding: 8px 12px; background-color: #20a1ad;" 
                        data-bs-toggle="modal" data-bs-target="#modalEditNasabah" 
          data-id="<?php echo $data['id_nasabah']; ?>"
          data-nama="<?php echo $data['nama']; ?>"
          data-nik="<?php echo $data['nik']; ?>"
          data-tempat_lahir="<?php echo $data['tempat_lahir']; ?>"
          data-tanggal_lahir="<?php echo $data['tanggal_lahir']; ?>"
          data-password="<?php echo $data['password']; ?>">
                        Edit
                      </a>
                                    <a class="btn" href="hapus_nasabah.php?id_nasabah=<?= $data['id_nasabah']; ?>" onclick="return confirm('Yakin Akan Menghapus Data Ini?')"  style="font-size: 14px; color: white; padding: 8px 12px; background-color: #152453;">Hapus</a>
      
                                  </td>
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
          <!-- Modal Tambah nasabah -->
  <div class="modal fade" id="modalTambahnasabah" tabindex="-1" aria-labelledby="modalTambahnasabahLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTambahnasabahLabel">Tambah Pegawai Pegadaian</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="tambah_nasabah.php" method="POST">
          <div class="modal-body">
            <div class="mb-3">
              <label for="nama" class="form-label">Nama Pegawai </label>
              <input type="text" class="form-control" id="namanasabah" name="nama" placeholder="Masukkan Nama Pegawai" required>
            </div>
            <div class="mb-3">
              <label for="nik" class="form-label">NIK Pegawai</label>
              <input type="text" class="form-control" id="nik" name="nik" placeholder="Masukkan NIK" required>
            </div>
            <div class="mb-3">
              <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
              <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" placeholder="Masukkan Tempat Lahir" required>
            </div>
            <div class="mb-3">
              <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
              <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="text" class="form-control" id="password" name="password" placeholder="Masukkan Password" required>
            </div>
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-success">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
  // JavaScript untuk modal edit
  document.addEventListener('DOMContentLoaded', function() {
    var editModal = document.getElementById('modalEditNasabah'); 
    
    editModal.addEventListener('show.bs.modal', function(event) {
      var button = event.relatedTarget;
      var id = button.getAttribute('data-id');
      var nama = button.getAttribute('data-nama');
      var nik = button.getAttribute('data-nik');
      var tempat_lahir = button.getAttribute('data-tempat_lahir');
      var tanggal_lahir = button.getAttribute('data-tanggal_lahir');
      var password = button.getAttribute('data-password');
      
      // Hilangkan prefix LCKR jika ada
      
      // Update modal content
      document.getElementById('editIdnasabah').value = id;
      document.getElementById('namanasabah').value = nama;
      document.getElementById('nik').value = nik;
      document.getElementById('tempat_lahir').value = tempat_lahir;
      document.getElementById('tanggal_lahir').value = tanggal_lahir;
      document.getElementById('password').value = password;
    });
  });
  </script>
          <?php
          include "bawah.php";
          ?>
