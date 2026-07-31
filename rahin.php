<?php
include "header.php";
?>
<!-- Modal Edit Locker -->
<div class="modal fade" id="modalEditRahin" tabindex="-1" aria-labelledby="modalEditRahinLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditRahinLabel">Edit Nasabah</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="edit_rahin.php" method="POST">
        <div class="modal-body">
          <input type="hidden" id="editIdRahin" name="id_rahin">
                   <div class="mb-3">
           <label for="nama_rahin" class="form-label">Nama Nasabah</label>
           <input type="text" class="form-control" id="nama_rahin" name="nama_rahin" placeholder="Masukkan Nama Nasabah" required>
         </div>
           <div class="mb-3">
            <label for="nik_rahin" class="form-label">NIK</label>
            <input type="text" class="form-control" id="nik_rahin" name="nik_rahin" placeholder="Masukkan Nomor Induk Kependudukan" required>
          </div>
           <div class="mb-3">
            <label for="no_whatsapp" class="form-label">Nomer Whatsapp</label>
            <input type="text" class="form-control" id="no_whatsapp" name="no_whatsapp" placeholder="Masukkan Nomer Whatsapp" required>
          </div>
          <div class="mb-3">
            <label for="kodeLocker" class="form-label">Email</label>
            <div class="input-group">
              <input type="text" class="form-control" id="email" name="email" placeholder="Email" required>
              <span class="input-group-text" id="basic-addon1">@gmail.com</span>
            </div>
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
                    <h2>Data Nasabah</h2>
                    <p class="mb-md-0"> Data Nasabah (Orang yang menggadaikan) dengan menggunakan tabel </p>
                  </div>
                  <div class="d-flex">
                    <img src="images/ty.png" alt="logo" style="height: 63px; margin-left: 380px;" class="d-none d-md-block">
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
                    <p class="card-title mb-0">Data Nasabah</p>
                    <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahRahin">
                                    </em><span> Tambah</span>
                                </a>
                    </div>
                  <div class="table-responsive">
                  <table id="example" class="display" style="width:100%">
                      <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Nasabah</th>
                            <th>NIK Nasabah</th>
                            <th>Nomor Whatsapp</th>
                            <th>Email</th>
                            <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                         <?php
                            $no = 1;
                            include 'config.php';
                            $pegadaian_id = $_SESSION['pegadaian_id'];
                            $sql = mysqli_query($conn, "SELECT * FROM rahin WHERE pegadaian_id = '$pegadaian_id'");
                            while ($data = mysqli_fetch_assoc($sql)) {
                            ?>
                             <tr>
                                 <td><?= $no++; ?></td>
                                 <td><?= $data['nama_rahin']; ?> </td>    
                                 <td><?= $data['nik_rahin']; ?> </td>    
                                 <td><?= $data['no_whatsapp']; ?> </td>    
                                 <td><?= $data['email']; ?> </td>    
                                

                                 <td>
    <a class="btn" data-bs-toggle="modal" 
        style="font-size: 14px; color: white; padding: 8px 12px; background-color: #20a1ad;" 
                data-bs-target="#modalEditRahin"
                data-id="<?= $data['id_rahin'] ?>"
                data-nama="<?= htmlspecialchars($data['nama_rahin'], ENT_QUOTES) ?>"
                data-nik="<?= htmlspecialchars($data['nik_rahin'], ENT_QUOTES) ?>"
                data-whatsapp="<?= htmlspecialchars($data['no_whatsapp'], ENT_QUOTES) ?>"
                data-email="<?= htmlspecialchars($data['email'], ENT_QUOTES) ?>">
            Edit
        </a>
                                     <a class="btn" href="hapus_rahin.php?id_rahin=<?= $data['id_rahin']; ?>" onclick="return confirm('Yakin Akan Menghapus Data Ini?')"  style="font-size: 14px; color: white; padding: 8px 12px; background-color: #152453;">Hapus</a>
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
        <!-- Modal Tambah Locker -->
<div class="modal fade" id="modalTambahRahin" tabindex="-1" aria-labelledby="modalTambahRahinLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTambahRahinLabel">Tambah Nasabah</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="tambah_rahin.php" method="POST">
        <div class="modal-body">
          <div class="mb-3">
           <label for="nama_rahin" class="form-label">Nama Nasabah</label>
           <input type="text" class="form-control" id="nama_rahin" name="nama_rahin" placeholder="Masukkan Nama Nasabah" required>
         </div>
           <div class="mb-3">
            <label for="nik_rahin" class="form-label">NIK Nasabah</label>
            <input type="text" class="form-control" id="nik_rahin" name="nik_rahin" placeholder="Masukkan Nomor Induk Kependudukan" required>
          </div>
           <div class="mb-3">
            <label for="no_whatsapp" class="form-label">Nomer Whatsapp</label>
            <input type="text" class="form-control" id="no_whatsapp" name="no_whatsapp" placeholder="Masukkan Nomer Whatsapp" required>
          </div>
          <div class="mb-3">
            <label for="kodeLocker" class="form-label">Email</label>
            <div class="input-group">
              <input type="text" class="form-control" id="kodeLocker" name="kode_locker" placeholder="Email" required>
              <span class="input-group-text" id="basic-addon1">@gmail.com</span>
            </div>
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
// JavaScript untuk modal edit Rahin
document.addEventListener('DOMContentLoaded', function() {
  var editModal = document.getElementById('modalEditRahin');

  editModal.addEventListener('show.bs.modal', function(event) {
    var button = event.relatedTarget;

    // Ambil data dari atribut data-*
    var id = button.getAttribute('data-id');
    var nama = button.getAttribute('data-nama');
    var nik = button.getAttribute('data-nik');
    var whatsapp = button.getAttribute('data-whatsapp');
    var email = button.getAttribute('data-email');

    // Set nilai ke form modal edit
    document.getElementById('editIdRahin').value = id;
    document.getElementById('nama_rahin').value = nama;
    document.getElementById('nik_rahin').value = nik;
    document.getElementById('no_whatsapp').value = whatsapp;

    // Hanya ambil bagian sebelum @gmail.com jika ada
    var emailValue = email;
    if (emailValue && emailValue.endsWith('@gmail.com')) {
      emailValue = emailValue.replace(/@gmail\.com$/i, '');
    }
    document.getElementById('email').value = emailValue;
  });
});
</script>
        <?php
        include "footer.php";
        ?>
