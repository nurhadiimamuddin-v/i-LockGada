<?php
include "header.php";
?>

<div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="d-flex justify-content-between flex-wrap">
                <div class="d-flex align-items-end flex-wrap">
                  <div class="mr-md-3 mr-xl-5">
                    <h2>Data Barang yang diambil</h2>
                    <p class="mb-md-0"> Data Barang yang diambil dengan menggunakan tabel </p>
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
                      Data Barang yang diambil 
                    </p>
                    <!-- <a href="export_file.php" class="btn btn-success">
                      <span>Download Excel</span>
                    </a> -->
                    </div>
                    <!-- Pilih Tanggal -->
                    
                  <div class="table-responsive">
                 <table id="example" class="display" style="width:100%">
  <thead>
    <tr>
      <th>No</th>
      <th>Foto Barang</th>
      <th>Tanggal Diterima</th>
      <th>NIK Nasabah</th>
      <th>NIK Pegawai</th>
      <th>Bukti Barang diterima</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $no = 1;
    include 'config.php';
    $pegadaian_id = $_SESSION['pegadaian_id'];
    $sql = mysqli_query($conn, "
      SELECT 
        bd.*,
        
        n.nama AS nama_pegawai,
        n.nik AS nik_pegawai,

        l.kode_locker,
        j.jenis_barang,

        r.nama_rahin,
        r.nik_rahin,

        bg.id_barang
      FROM barang_diambil bd
      LEFT JOIN barang_gadai bg ON bd.barang_id = bg.id_barang
      LEFT JOIN nasabah n ON bd.nasabah_id = n.id_nasabah
      LEFT JOIN locker l ON bd.locker_id = l.id_locker
      LEFT JOIN jenis_barang j ON bd.jenis_id = j.id_jenis_barang
      LEFT JOIN rahin r ON bd.rahin_id = r.id_rahin
      WHERE bd.pegadaian_id = '$pegadaian_id' ORDER BY bd.tgl_diterima DESC
    ");
    while ($data = mysqli_fetch_assoc($sql)) {
    ?>
      <tr>
        <td><?= $no++; ?></td>
        <td>
    <?php if (!empty($data['finishing_foto_barang'])): ?>
        <a href="<?= htmlspecialchars($data['finishing_foto_barang']); ?>" target="_blank" data-toggle="modal" data-target="#modalFoto<?= $data['id_barang_diambil']; ?>">
            <img src="<?= htmlspecialchars($data['finishing_foto_barang']); ?>" alt="Finishing Foto Barang" style="max-width: 70px; max-height: 70px; cursor: pointer;">
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
                        <img src="<?= htmlspecialchars($data['finishing_foto_barang']); ?>" alt="Foto Barang" style="max-width: 100%; height: auto;">
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <span>Tidak ada foto</span>
    <?php endif; ?>
</td>
        <td><?= htmlspecialchars($data['tgl_diterima']); ?></td>
        <td><?= htmlspecialchars($data['nik_rahin']); ?></td>
        <td><?= htmlspecialchars($data['nik_pegawai']); ?></td>
<td>
        <a class="btn" href="cetak_surat.php?id_barang_diambil=<?= $data['id_barang_diambil']; ?>" target="_blank" style="font-size: 15px; color: white; padding: 8px 12px; background-color: #20a1ad;">
        <i class="mdi mdi-file">
        </i>Cetak Surat</a>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>

                  </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <?php
        include "footer.php";
        ?>