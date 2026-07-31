<?php
include "atas.php";
?>

<div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="d-flex justify-content-between flex-wrap">
                <div class="d-flex align-items-end flex-wrap">
                  <div class="mr-md-3 mr-xl-5">
                    <h2>Barang yang digadaikan</h2>
                    <p class="mb-md-0"> Data Barang yang digadaikakn dengan menggunakan tabel </p>
                  </div>
                  <div class="d-flex">
                    <img src="images/ty.png" alt="logo" style="height: 63px; margin-left: 530px;" class="d-none d-md-block">
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
                    <p class="card-title mb-0">
                      Barang yang digadaikan 
                    </p>
                   
                    </div>
                    <!-- Pilih Tanggal -->
                    <!-- <form method="get" class="mb-3">
                      <div class="input-group" style="max-width: 400px;">
                      <input type="date" name="tanggal" class="form-control" value="<?php echo isset($_GET['tanggal']) ? $_GET['tanggal'] : ''; ?>">
                      <button type="submit" class="btn" style="font-size: 14px; color: white; padding: 8px 12px; background-color: #20a1ad;">Tampilkan</button>
                      </div>
                    </form> -->
                  <div class="table-responsive">
                  <table id="example" class="display" style="width:100%">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Foto Barang</th>
                        <th>Tanggal Digadai</th>
                        <th>NIK Nasabah </th>
                        <th>Nama Nasabah</th>
                        <th>Deskripsi Barang</th>
                        <th>Bukti </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $no = 1;
                      include 'config.php';
                      $pegadaian_id = $_SESSION['pegadaian_id'];
                        $sql = mysqli_query($conn, "
                        SELECT 
                          b.id_barang,
                          b.tgl_digadai,
                          b.foto_nasabah,
                          b.foto_barang,
                          n.nama,
                          l.kode_locker,
                          r.nama_rahin,
                          r.nik_rahin,
                          b.deskripsi_barang
                        FROM barang_gadai b
                        LEFT JOIN nasabah n ON b.nasabah_id = n.id_nasabah
                        LEFT JOIN locker l ON b.locker_id = l.id_locker
                        LEFT JOIN jenis_barang j ON b.jenis_id = j.id_jenis_barang 
                        LEFT JOIN rahin r ON b.rahin_id = r.id_rahin
                        WHERE b.status_barang = 'digadai'
                        AND b.pegadaian_id = '$pegadaian_id'
                      ");
                      while ($data = mysqli_fetch_assoc($sql)) {
                      ?>
                      <tr>
                        <td><?= $no++; ?></td>
                        <td>
    <?php if (!empty($data['foto_barang'])): ?>
        <a href="<?= htmlspecialchars($data['foto_barang']); ?>" target="_blank" data-toggle="modal" data-target="#modalFoto<?= $data['id_barang']; ?>">
            <img src="<?= htmlspecialchars($data['foto_barang']); ?>" alt="Foto Barang" style="max-width: 70px; max-height: 70px; cursor: pointer;">
        </a>

        <!-- Modal Bootstrap -->
        <div class="modal fade" id="modalFoto<?= $data['id_barang']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabel<?= $data['id_barang']; ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel<?= $data['id_barang']; ?>">Foto Barang Digadai</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="<?= htmlspecialchars($data['foto_barang']); ?>" alt="Foto Barang" style="max-width: 100%; height: auto;">
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <span>Tidak ada foto</span>
    <?php endif; ?>
</td>
                        <td><?= htmlspecialchars($data['tgl_digadai']); ?></td>
                        <td><?= htmlspecialchars($data['nik_rahin']); ?></td>
                        <td><?= htmlspecialchars($data['nama_rahin']); ?></td>
                        <td><?= htmlspecialchars($data['deskripsi_barang']); ?></td>

<td>
        <a class="btn" href="cetak_pdf.php?id_barang=<?= $data['id_barang']; ?>" target="_blank" style="font-size: 15px; color: white; padding: 8px 12px; background-color: #152453;">
        <i class="mdi mdi-file-check">
        </i> Surat Barang di gadaikan</a>
        </td>
                        
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

        <?php
        include "bawah.php";
        ?>