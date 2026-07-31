<?php
include "atas.php";
?>
<!-- Modal Edit nasabah -->
<div class="modal fade" id="modalEditNasabah" tabindex="-1" aria-labelledby="modalEditNasabahLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditNasabahLabel">Edit nasabah</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="edit_nasabah.php" method="POST">
        <div class="modal-body">
          <input type="hidden" id="editIdnasabah" name="id_nasabah">
           <div class="mb-3">
            <label for="Editnama" class="form-label">Nama Nasabah</label>
            <input type="text" class="form-control" id="namanasabah" name="nama" placeholder="Masukkan Nama Nasabah" required>
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
                    <h2>Data Riwayat Pegadaian Barang</h2>
                    <p class="mb-md-0"> Data akan Memunculkan Riwayat barang yang digadaikan</p>
                  </div>
                  <div class="d-flex">
                    <img src="images/ty.png" alt="logo" style="height: 63px; margin-left: 480px;" class="d-none d-md-block">
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
                    <p class="card-title mb-0">Data Riwayat Barang</p>
                    <a href="export_fil.php" class="btn btn-success">
                                    </em><span> Download Excel</span>
                                </a>
                    </div>
                  <div class="table-responsive">
                  <table id="example" class="display" style="width:100%">
                      <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pegawai</th>
                            <th>NIK Pegawai</th>
                            <th>Tanggal di Gadai</th>
                            <th>Foto Pegawai</th>
                            <th>Tanggal di Terima</th>
                            <th>Verifikasi Foto Pegawai</th>
                            <th>Kode Barang Gadai</th>
                            <th>Detail</th>
                            <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                         <?php
                            $no = 1;
                            include 'config.php';
                            $pegadaian_id = $_SESSION['pegadaian_id'];
                            $sql = mysqli_query($conn, "
                              SELECT 
                                bg.id_barang,
                                bg.tgl_digadai,
                                bg.foto_nasabah,
                                bg.foto_barang,
                                bg.status_barang,
                                bg.deskripsi_barang,
                                bg.nasabah_id,
                                bg.locker_id,
                                bg.jenis_id,
                                bg.rahin_id,
                                bda.id_barang_diambil,
                                bda.tgl_diterima,
                                bda.finishing_foto_nasabah,
                                bda.finishing_foto_barang,
                                bda.nasabah_id AS bda_nasabah_id,
                                bda.locker_id AS bda_locker_id,
                                bda.jenis_id AS bda_jenis_id,
                                bda.rahin_id AS bda_rahin_id,
                                bda.barang_id,
                                n.id_nasabah,
                                n.nama,
                                n.nik,
                                l.id_locker,
                                l.kode_locker,
                                jb.id_jenis_barang,
                                jb.jenis_barang,
                                r.id_rahin,
                                r.nama_rahin,
                                r.nik_rahin,
                                r.no_whatsapp,
                                r.email
                              FROM barang_gadai bg
                              LEFT JOIN barang_diambil bda ON bg.id_barang = bda.barang_id
                              LEFT JOIN nasabah n ON bg.nasabah_id = n.id_nasabah
                              LEFT JOIN locker l ON bg.locker_id = l.id_locker
                              LEFT JOIN jenis_barang jb ON bg.jenis_id = jb.id_jenis_barang
                              LEFT JOIN rahin r ON bg.rahin_id = r.id_rahin
                           WHERE bg.pegadaian_id = '$pegadaian_id' ");
                            while ($data = mysqli_fetch_assoc($sql)) {
                            ?>
                             <tr>
                                 <td><?= $no++; ?></td>
                                 <td><?= $data['nama']; ?> </td>    
                                 <td><?= $data['nik']; ?> </td>    
                                 <td><?= $data['tgl_digadai']; ?> </td>  
                                         <td>
    <?php if (!empty($data['foto_nasabah'])): ?>
        <a href="<?= htmlspecialchars($data['foto_nasabah']); ?>" target="_blank" data-toggle="modal" data-target="#modalFoto<?= $data['id_barang']; ?>">
            <img src="<?= htmlspecialchars($data['foto_nasabah']); ?>" alt="Foto Nasabah" style="max-width: 70px; max-height: 70px; cursor: pointer;">
        </a>

        <!-- Modal Bootstrap -->
        <div class="modal fade" id="modalFoto<?= $data['id_barang']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabel<?= $data['id_barang_diambil']; ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel<?= $data['id_barang']; ?>">Foto Nasabah Ketika barang mau digadaikan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="<?= htmlspecialchars($data['foto_nasabah']); ?>" alt="Foto Barang" style="max-width: 100%; height: auto;">
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <span>Tidak ada foto</span>
    <?php endif; ?>
</td>  
                                 <td><?= $data['tgl_diterima']; ?> </td>   
                                         <td>
    <?php if (!empty($data['finishing_foto_nasabah'])): ?>
        <a href="<?= htmlspecialchars($data['finishing_foto_nasabah']); ?>" target="_blank" data-toggle="modal" data-target="#modalFoto<?= $data['id_barang_diambil']; ?>">
            <img src="<?= htmlspecialchars($data['finishing_foto_nasabah']); ?>" alt="Finishing Foto Nasabah" style="max-width: 70px; max-height: 70px; cursor: pointer;">
        </a>

        <!-- Modal Bootstrap -->
        <div class="modal fade" id="modalFoto<?= $data['id_barang_diambil']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabel<?= $data['id_barang_diambil']; ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel<?= $data['id_barang_diambil']; ?>">Foto Barang yang telah di ambil</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="<?= htmlspecialchars($data['finishing_foto_nasabah']); ?>" alt="Foto Nasabah" style="max-width: 100%; height: auto;">
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <span>Tidak ada foto</span>
    <?php endif; ?>
</td> 
                                 <td><?= $data['nik_rahin']; ?> </td>    
                                 <td><a href="password.php?id_nasabah=<?= urlencode($data['id_nasabah']); ?>" target="_blank" style="text-decoration: none; color: blue;"><i class="mdi mdi-eye"></i> View</a></td>
                                 <td>
                                  <?php if ($data['status_barang'] == 'digadai') { ?>
                                    <button class="btn" style="font-size: 14px; color: white; padding: 8px 12px; background-color: #152453;">Digadai</button>
                                  <?php } elseif ($data['status_barang'] == 'diambil') { ?>
                                    <button class="btn" style="font-size: 14px; color: white; padding: 8px 12px; background-color: #20a1ad;">Diambil</button>
                                  <?php } else { ?>
                                    <button class="btn btn-secondary" disabled><?= htmlspecialchars($data['status_barang']); ?></button>
                                  <?php } ?>
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
        <h5 class="modal-title" id="modalTambahnasabahLabel">Tambah nasabah</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="tambah_nasabah.php" method="POST">
        <div class="modal-body">
          <div class="mb-3">
            <label for="nama" class="form-label">Nama Nasabah</label>
            <input type="text" class="form-control" id="namanasabah" name="nama" placeholder="Masukkan Nama Nasabah" required>
          </div>
          <div class="mb-3">
            <label for="nik" class="form-label">NIK</label>
            <input type="text" class="form-control" id="nik" name="nik" placeholder="Masukkan NIK" required>
          </div>
          <div class="mb-3">
            <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
            <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" placeholder="Masukkan Tempat Lahir" required>
          </div>
          <div class="mb-3">
            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
            <input type="text" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password" required>
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

        <?php
        include "bawah.php";
        ?>
