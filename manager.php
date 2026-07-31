
<?php 
include "head.php";
?>
<!-- Modal Edit Pegadaian -->
<div class="modal fade" id="modalEditManager" tabindex="-1" aria-labelledby="modalEditManagerLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditManagerLabel">Edit Manager</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="edit_Manager.php" method="POST">
        <div class="modal-body">
          <input type="hidden" id="editId" name="id_manager">
          <div class="mb-3">
            <label for="editNamaManager" class="form-label">Nama Manager</label>
            <input type="text" class="form-control" id="editNamaManager" name="nama" placeholder="Masukkan Nama Manager" required>
          </div>
          <div class="mb-3">
            <label for="editUsernameManager" class="form-label">Username</label>
            <input type="text" class="form-control" id="editUsernameManager" name="username" placeholder="Masukkan Username" required>
          </div>
          <div class="mb-3">
            <label for="editKodePegadaian" class="form-label">Kode Pegadaian</label>
            <select class="form-select" id="editKodePegadaian" name="pegadaian_id" style="font-size: 13px;" required>
              <option value="" disabled>--- PILIH ---</option>
              <?php
              include 'config.php';

              // Get the current pegadaian_id of the manager being edited
              $currentPegadaianId = isset($_GET['id']) ? getCurrentPegadaianId($_GET['id'], $conn) : null;

              // Fetch IDs already assigned to other managers
              $usedIds = [];
              $usedResult = mysqli_query($conn, "SELECT pegadaian_id FROM Manager WHERE pegadaian_id != '$currentPegadaianId'");
              while ($usedRow = mysqli_fetch_assoc($usedResult)) {
                $usedIds[] = $usedRow['pegadaian_id'];
              }

              // Fetch all Pegadaian options
              $result = mysqli_query($conn, "SELECT id, kode_Pegadaian, lokasi_pegadaian FROM Pegadaian");
              while ($row = mysqli_fetch_assoc($result)) {
                if (!in_array($row['id'], $usedIds) || $row['id'] == $currentPegadaianId) {
                  $selected = ($row['id'] == $currentPegadaianId) ? 'selected' : '';
                  echo "<option value='{$row['id']}' $selected>{$row['kode_Pegadaian']} - {$row['lokasi_pegadaian']}</option>";
                }
              }

              // Function to get current pegadaian_id of manager
              function getCurrentPegadaianId($managerId, $conn) {
                $query = "SELECT pegadaian_id FROM Manager WHERE id_manager = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $managerId);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($row = $result->fetch_assoc()) {
                  return $row['pegadaian_id'];
                }
                return null;
              }
              ?>
            </select>
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
                    <h2>Data Manager</h2>
                    <p class="mb-md-0"> Data berbentuk Tabel untuk Menampilkan Data Manager</p>
                  </div>
                  <div class="d-flex">
                    <img src="images/ty.png" alt="logo" style="height: 63px; margin-left: 550px;" class="d-none d-md-block">
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
          <p class="card-title mb-0">Data Manager</p>
          <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahManager">
            <span> Tambah</span>
          </a>
        </div>
        <div class="table-responsive">
          <table id="example" class="display" style="width:100%">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Manager</th>
                <th>Username</th>
                <th>Password</th>
                <th>Kode Pegadaian</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              include 'config.php';
              $sql = mysqli_query($conn, "SELECT manager.*, Pegadaian.kode_Pegadaian 
                                          FROM manager 
                                          JOIN Pegadaian ON manager.pegadaian_id = Pegadaian.id");
              while ($data = mysqli_fetch_assoc($sql)) {
              ?>
                <tr>
                  <td><?= $no++; ?></td>
                  <td><?= $data['nama']; ?></td>
                  <td><?= $data['username']; ?></td>
                  <td><a href="pass.php?id_manager=<?= urlencode($data['id_manager']); ?>" target="_blank" style="text-decoration: none; color: blue;"><i class="mdi mdi-eye"></i> View</a></td>
                  <td><?= $data['kode_Pegadaian']; ?></td>
                  <td>
                    <a class="btn" 
                       style="font-size: 14px; color: white; padding: 8px 12px; background-color: #20a1ad;" 
                      data-bs-toggle="modal" data-bs-target="#modalEditManager" 
        data-id="<?php echo $data['id_manager']; ?>"
        data-nama="<?php echo $data['nama']; ?>"
        data-username="<?php echo $data['username']; ?>">
                      Edit
                    </a>
                    <a class="btn" href="hapus_manager.php?id_manager=<?= $data['id_manager']; ?>" onclick="return confirm('Yakin Akan Menghapus Data Ini?')" style="font-size: 14px; color: white; padding: 8px 12px; background-color: #152453;">Hapus</a>
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
<!-- Modal Tambah Manager -->
<div class="modal fade" id="modalTambahManager" tabindex="-1" aria-labelledby="modalTambahManagerLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTambahManagerLabel">Tambah Manager</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="tambah_Manager.php" method="POST">
        <div class="modal-body">
          <div class="mb-3">
            <label for="namaManager" class="form-label">Nama Manager</label>
            <input type="text" class="form-control" id="namaManager" name="nama" placeholder="Masukkan Nama Manager" required>
          </div>
          <div class="mb-3">
            <label for="usernameManager" class="form-label">Username</label>
            <input type="text" class="form-control" id="usernameManager" name="username" placeholder="Masukkan Username" required>
          </div>
          <div class="mb-3">
            <label for="passwordManager" class="form-label">Password</label>
            <input type="text" class="form-control" id="passwordManager" name="password" placeholder="Masukkan Password" required>
          </div>
          <div class="mb-3">
            <label for="kodePegadaian" class="form-label">Kode Pegadaian</label>
            <select class="form-select" id="kodePegadaian" name="pegadaian_id" style="font-size: 13px;" required>
              <option value="" disabled selected>--- PILIH ---</option>
              <?php
              include 'config.php';
              // Fetch IDs already assigned to managers
              $usedIds = [];
              $usedResult = mysqli_query($conn, "SELECT pegadaian_id FROM Manager");
              while ($usedRow = mysqli_fetch_assoc($usedResult)) {
                $usedIds[] = $usedRow['pegadaian_id'];
              }

              // Fetch available Pegadaian IDs
              $result = mysqli_query($conn, "SELECT id, kode_Pegadaian, lokasi_pegadaian FROM Pegadaian");
              while ($row = mysqli_fetch_assoc($result)) {
                if (!in_array($row['id'], $usedIds)) {
                  echo "<option value='{$row['id']}'>{$row['kode_Pegadaian']} - {$row['lokasi_pegadaian']}</option>";
                }
              }
              ?>
            </select>
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
<!-- Modal Edit Manager -->

<script>
// JavaScript to populate the modal with existing data when editing
document.addEventListener('DOMContentLoaded', function() {
  var editModal = document.getElementById('modalEditManager');
  
  editModal.addEventListener('show.bs.modal', function(event) {
    var button = event.relatedTarget; // Button that triggered the modal
    var id = button.getAttribute('data-id');
    var nama = button.getAttribute('data-nama');
    var username = button.getAttribute('data-username');
    
    // Update the modal's content
    document.getElementById('editId').value = id;
    document.getElementById('editNamaManager').value = nama;
    document.getElementById('editUsernameManager').value = username;
    
    // Note: The pegadaian_id dropdown will be handled by the PHP code above
  });
});
</script>

<?php
include "foot.php";
?>