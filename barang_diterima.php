<?php
include "header.php";

// Koneksi database
include "config.php";
$showGadaiForm = false;
$rahinData = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {
    $selectedNik = $_POST['nik_rahin'] ?? [];

    if (!empty($selectedNik)) {
        $selectedNik = $selectedNik[0]; // hanya proses 1 nik dulu

        // Ambil data barang_gadai dengan status digadai dan nik yang dipilih
        $query = "
            SELECT 
                bg.*, 
                r.nama_rahin, 
                r.nik_rahin, 
                r.no_whatsapp, 
                r.email 
            FROM barang_gadai bg 
            JOIN rahin r ON bg.rahin_id = r.id_rahin 
            WHERE r.nik_rahin = '$selectedNik' 
              AND bg.status_barang = 'digadai'
            LIMIT 1
        ";

        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            $rahinData = mysqli_fetch_assoc($result);
            $showGadaiForm = true;
        }
    }
}

// Ambil semua NIK Rahin dari barang yang statusnya masih digadai
$availableNIKs = [];
     $pegadaian_id = $_SESSION['pegadaian_id'];

$query = "
    SELECT DISTINCT 
        r.nik_rahin, r.nama_rahin 
    FROM barang_gadai bg 
    JOIN rahin r ON bg.rahin_id = r.id_rahin 
    WHERE bg.status_barang = 'digadai'
      AND bg.pegadaian_id = '$pegadaian_id'
";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $availableNIKs[] = $row;
}

?>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="d-flex justify-content-between flex-wrap">
                    <div class="d-flex align-items-end flex-wrap">
                        <div class="mr-md-3 mr-xl-5">
                            <h2>Data Barang yang akan diterima</h2>
                            <p class="mb-md-0">Silahkan Untuk mencari <strong> NIK NASABAH </strong> terlebih dahulu</p>
                            <p class="mb-md-0" style="color: red;">
                              <strong>Note : </strong> Nomor Induk Kependudukan Rahin dijadikan sebagai Kode barang yang di gadaikan
                            </p>
                        </div>
                        <div class="d-flex">
                            <img src="images/ty.png" alt="logo" style="height: 63px; margin-left: 360px;" class="d-none d-md-block">
                        </div>
                    </div>
                </div>   
            </div>
        
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">DATA KODE BARANG YANG DIGADAIKAN</h4>
                        
                        <form class="forms-sample d-flex align-items-center" method="POST" id="locker-form">
  <div class="form-group flex-grow-1">
    <label for="nik-search">Pilih NIK Nasabah (Barang Masih Digadai):</label>
    <select class="js-example-basic-multiple" name="nik_rahin[]" id="nik-search" multiple="multiple" style="width: 100%">
      <?php foreach ($availableNIKs as $nik): ?>
        <option value="<?php echo htmlspecialchars($nik['nik_rahin']); ?>">
          <?php echo htmlspecialchars($nik['nik_rahin'] . ' - ' . $nik['nama_rahin']); ?>
        </option>
      <?php endforeach; ?>
    </select>  
  </div>
  <div class="form-group ml-3">
    <button type="submit" name="search" class="btn mt-4" style="color: white; background-color: #20a1ad;">Search</button>
  </div>
</form>


                      <?php if (isset($_POST['search']) && !$showGadaiForm): ?>
  <div class="alert alert-danger mt-3">
    Data dengan NIK tersebut tidak ditemukan atau tidak dalam status "digadai".
  </div>
<?php endif; ?>

<?php if ($showGadaiForm && $rahinData): ?>
  <div class="alert alert-success mt-3">
    Ditemukan data dengan NIK: <strong><?php echo htmlspecialchars($rahinData['nik_rahin']); ?></strong>
  </div>
<?php endif; ?>

                    </div>
                </div>
            </div>
  <?php if ($showGadaiForm && $rahinData): ?>
    <?php
        // Query detail barang gadai dengan join lengkap
        $nik_rahin = $rahinData['nik_rahin'];
        $queryDetail = "
            SELECT 
                bg.*, 
                n.nama AS nama_nasabah, 
                n.nik AS nik_nasabah,
                l.kode_locker,
                l.id_locker,
                jb.jenis_barang,
                r.nama_rahin, r.nik_rahin, r.no_whatsapp, r.email,
                m.id_manager
            FROM barang_gadai bg
            JOIN nasabah n ON bg.nasabah_id = n.id_nasabah
            JOIN locker l ON bg.locker_id = l.id_locker
            JOIN jenis_barang jb ON bg.jenis_id = jb.id_jenis_barang
            JOIN rahin r ON bg.rahin_id = r.id_rahin
            JOIN manager m ON bg.manager_id = m.id_manager
            WHERE r.nik_rahin = '$nik_rahin' AND bg.status_barang = 'digadai'
        ";
        $resultDetail = mysqli_query($conn, $queryDetail);
        $dataDetail = mysqli_fetch_assoc($resultDetail); // Tambahkan ini
    ?>


    <?php if (mysqli_num_rows($resultDetail) > 0): ?>
       
 

            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">FORM BARANG YANG AKAN DIAMBIL</h4>
                        <p class="card-description">
                            Silahkan isi form berikut untuk mengambil barang
                        </p>
                        <form class="forms-sample" action="tambah_barang_diterima.php" method="POST" enctype="multipart/form-data">
                          <input type="hidden" name="nik_rahin" value="<?php echo $rahinData['nik_rahin']; ?>">
                            
                            <div class="form-group">
                                <label for="no_identitas">Nama Nasabah</label>
                                <input type="text" class="form-control" id="no_identitas" name="nama" placeholder="Nama Nasabah" value="<?php echo htmlspecialchars($_SESSION['nama']); ?>" readonly required>
                            </div>
                            <div class="form-group">
                                <label for="no_identitas">Kode Pegadaian</label>
                                <input type="text" class="form-control" id="kode_pegadaian" name="kode_pegadaian" placeholder="Nama Nasabah" value="<?php echo htmlspecialchars($_SESSION['kode_pegadaian']); ?>" readonly required>
                            </div>
                            
                            <!-- <div class="form-group">
                                <label for="jenis_barang">Jenis Barang</label>
                                <select class="form-control" id="jenis_id" name="jenis_id" required>
                                    <option value="">Pilih Jenis Barang</option>
                                    <?php
                                    // Query to fetch all jenis_barang
                                    $jenisBarangQuery = "SELECT id_jenis_barang, jenis_barang FROM jenis_barang";
                                    $jenisBarangResult = mysqli_query($conn, $jenisBarangQuery);
                                    while ($jenis = mysqli_fetch_assoc($jenisBarangResult)) {
                                        echo '<option value="' . htmlspecialchars($jenis['id_jenis_barang']) . '">' . htmlspecialchars($jenis['jenis_barang']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="deskripsi_barang">Deskripsi Barang</label>
                                <textarea class="form-control" id="deskripsi_barang" name="deskripsi_barang" rows="3" placeholder="Deskripsikan barang secara detail" required></textarea>
                            </div>
                             -->
                            <div class="form-group">
                                <label for="tgl_diterima">Tanggal diambil</label>
                                <input type="text" class="form-control" id="tanggal_diterima" name="tgl_diterima" value="<?php echo date('d F Y'); ?>" readonly>
                            </div>

                            <hr> <!-- Separator between sections -->

                            <!-- Form Section 3 -->
                            <div class="form-group">
                                <label for="finishing_foto_nasabah">Verifikasi Foto Nasabah </label>
                                <div>
                                    <button type="button" class="btn" style="color: white; background-color: #20a1ad;" data-toggle="modal" data-target="#cameraModal" required>
                                        Buka Kamera
                                    </button>
                                    <input type="hidden" id="finishing_foto_nasabah" name="finishing_foto_nasabah" required>
                                    <div id="photoPreview" class="mt-2" style="display: none;">
                                        <img id="previewImage" style="max-width: 100%; max-height: 200px;">
                                        <button type="button" id="retakePhotoBtn" class="btn btn-secondary btn-sm mt-2">Ambil Ulang</button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="finishing_foto_barang"> Verifikasi Foto Barang</label>
                                <input type="file" class="form-control" id="finishing_foto_barang" name="finishing_foto_barang" placeholder="Dalam Rupiah" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="status_barang">Status</label>
                                <input type="text" class="form-control" id="status_barang" name="status_barang" value="diambil" readonly required>
                            </div>
                            <div class="form-group">
                                <label for="akses">Akses Locker</label>
                                <div>
                                    <button 
                                        type="button" 
                                        class="btn btn-success" 
                                        data-toggle="modal" 
                                        data-target="#aksesModal"
                                        data-locker="<?php echo htmlspecialchars($dataDetail['kode_locker']); ?>"
                                        onclick="setSelectedLocker('<?php echo htmlspecialchars($dataDetail['kode_locker']); ?>')"
                                    >
                                       Open / Close Locker (<?php echo htmlspecialchars($dataDetail['kode_locker']); ?>)
                                    </button>
                                </div>
                            </div>
<div class="form-group">
    <label>Locker yang dipilih</label>
    <input type="text" class="form-control" value="<?php echo isset($dataDetail['kode_locker']) ? $dataDetail['kode_locker'] : ''; ?>" readonly>
</div>


                          <button type="submit" class="btn btn-success mr-2">Submit</button>
                          <button type="reset" class="btn btn-secondary">Reset</button>
                        </form>
                    </div>
                </div>
            </div>
                <?php endif; ?>
                <?php endif; ?>
          </div>
        </div>
</div>
<div class="modal fade" id="cameraModal" tabindex="-1" role="dialog" aria-labelledby="cameraModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cameraModalLabel">Ambil Foto Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <video id="cameraPreview" autoplay playsinline style="width: 100%; max-width: 600px; background: #000;"></video>
                <canvas id="photoCanvas" style="display: none;"></canvas>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" id="takePhotoBtn" class="btn btn-success">Ambil Foto</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="aksesModal" tabindex="-1" role="dialog" aria-labelledby="aksesModalLabel">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="aksesModalLabel">Kontrol Locker <?php echo $dataDetail['kode_locker']; ?></h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <div class="btn-group">
          <button type="button" class="btn btn-lg m-3" style="color: white; background-color: #20a1ad;" 
          onclick="controlLocker('open', <?php echo $dataDetail['id_locker']; ?>)">
            <i class="mdi mdi-lock-open"></i> Buka Locker
          </button>
          <button type="button" class="btn btn-lg m-3" style="color: white; background-color: #152453;" 
          onclick="controlLocker('close', <?php echo $dataDetail['id_locker']; ?>)">
            <i class="mdi mdi-lock"></i> Tutup Locker
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// Global variables
const espIp = "192.168.194.46"; // IP ESP8266 (pastikan benar dan tidak ada spasi)

function controlLocker(action, lockerId) {
    const btn = event.target;
    const originalHtml = btn.innerHTML;
    
    // Show loading
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Memproses...';
    btn.disabled = true;
    
    // Debug log
    console.log(`Mengontrol locker ID: ${lockerId}, Aksi: ${action}`);
    
    // Call ESP API
    fetch(`http://${espIp}/control?id_locker=${lockerId}&action=${action}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === "success") {
                updateLockerStatus(action === 'open');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: data.message,
                    timer: 2000
                });
            } else {
                throw new Error(data.message || 'Unknown error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: error.message || 'Terjadi kesalahan saat mengontrol locker tersebut.'
            });
        })
        .finally(() => {
            btn.innerHTML = originalHtml;
            btn.disabled = false;
        });
}

function updateLockerStatus(isOpen) {
    const indicator = document.querySelector('.status-indicator');
    const statusText = document.querySelector('.status-text');
    
    if (isOpen) {
        indicator.className = 'status-indicator bg-success d-inline-block';
        statusText.textContent = 'Status: Terbuka';
    } else {
        indicator.className = 'status-indicator bg-danger d-inline-block';
        statusText.textContent = 'Status: Tertutup';
    }
}

// Fungsi untuk cek koneksi ke ESP
function checkESPConnection() {
    fetch(`http://${espIp}/control?action=check`)
        .then(response => {
            if (!response.ok) {
                throw new Error('ESP tidak merespon');
            }
            return response.json();
        })
        .catch(error => {
            console.error('ESP Connection Error:', error);
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Tidak dapat terhubung ke perangkat locker. Pastikan perangkat menyala dan terhubung ke jaringan yang sama.'
            });
        });
}

// Jalankan cek koneksi saat halaman dimuat
window.addEventListener('load', checkESPConnection);
</script>
<style>
.status-indicator {
    width: 15px;
    height: 15px;
    border-radius: 50%;
    margin-right: 8px;
}
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@4.0.1/dist/css/multi-select-tag.min.css">
<script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@4.0.1/dist/js/multi-select-tag.min.js"></script>
<script>
    var tagSelector = new MultiSelectTag('nik-search', {
        maxSelection: 1,              // Only allow selecting 1 locker
        required: true,               // default false.
        placeholder: 'Cari Nomor Induk Kependudukan Nasabah...', // default 'Search'.
        onChange: function(selected) {
            console.log('Selection changed:', selected);
        }
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const video = document.getElementById('cameraPreview');
    const canvas = document.getElementById('photoCanvas');
    const takePhotoBtn = document.getElementById('takePhotoBtn');
    const retakePhotoBtn = document.getElementById('retakePhotoBtn');
    const photoPreview = document.getElementById('photoPreview');
    const previewImage = document.getElementById('previewImage');
    const fotoNasabahInput = document.getElementById('finishing_foto_nasabah');
    let stream = null;

    startCamera();

    function startCamera() {
        navigator.mediaDevices.getUserMedia({ video: true, audio: false })
            .then(function (s) {
                stream = s;
                video.srcObject = stream;
            })
            .catch(function (err) {
                console.error("Error accessing camera: ", err);
                alert("Tidak dapat mengakses kamera. Pastikan Anda memberikan izin akses kamera.");
                fallbackToFileUpload();
            });
    }

    function fallbackToFileUpload() {
        document.querySelector('.camera-container').innerHTML = `
            <input type="file" class="form-control-file" id="finishing_foto_nasabah" name="finishing_foto_nasabah" accept="image/*" capture="environment" required>
            <small class="text-muted">Kamera tidak tersedia. Silakan unggah foto dari galeri.</small>
        `;
    }

    takePhotoBtn.addEventListener('click', function () {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

        const imageData = canvas.toDataURL('image/jpeg', 0.8);
        previewImage.src = imageData;
        photoPreview.style.display = 'block';

        fotoNasabahInput.value = imageData;

        takePhotoBtn.style.display = 'none';
        retakePhotoBtn.style.display = 'inline-block';
    });

    retakePhotoBtn.addEventListener('click', function () {
        photoPreview.style.display = 'none';
        takePhotoBtn.style.display = 'inline-block';
        retakePhotoBtn.style.display = 'none';
        fotoNasabahInput.value = '';
    });

    window.addEventListener('beforeunload', function () {
        if (stream) stream.getTracks().forEach(track => track.stop());
    });

    document.querySelector('form').addEventListener('submit', function () {
        if (stream) stream.getTracks().forEach(track => track.stop());
    });
});
</script>

<?php
include "footer.php";
?>            