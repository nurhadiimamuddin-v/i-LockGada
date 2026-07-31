<?php
include "header.php";

// Koneksi database
include "config.php";

// Proses pencarian locker
$showGadaiForm = false;
$lockerData = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {
  // Get selected locker codes from the multi-select
  $selectedLockers = $_POST['locker_codes'] ?? [];
  
  if (!empty($selectedLockers)) {
    // Get the first selected locker (since we'll only process one at a time)
    $selectedLocker = $selectedLockers[0];
    // Check if the locker is available
    $query = "SELECT * FROM locker WHERE kode_locker = '$selectedLocker' AND status='belum_terisi'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
      $lockerData = mysqli_fetch_assoc($result);
      $showGadaiForm = true; 
    }
  }
}

// Get all available lockers for the dropdown
$availableLockers = [];
$pegadaian_id = $_SESSION['pegadaian_id'];
$query = "SELECT * FROM locker WHERE status='belum_terisi' AND pegadaian_id = '$pegadaian_id'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
  $availableLockers[] = $row;
}

// Query to fetch barang_gadai data with joins
$barangGadaiData = [];
$query = "
  SELECT 
    bg.id_barang, 
    n.nama AS nama_nasabah, 
    bg.tgl_digadai, 
    bg.foto_nasabah, 
    bg.foto_barang, 
    bg.status_barang, 
    l.kode_locker, 
    r.nama_rahin, 
    r.nik_rahin, 
    r.no_whatsapp, 
    r.email, 
    jb.jenis_barang
  FROM 
    barang_gadai bg
  JOIN 
    rahin r ON bg.rahin_id = r.id_rahin
  JOIN 
    nasabah n ON bg.nasabah_id = n.id_nasabah
  JOIN 
    locker l ON bg.locker_id = l.id_locker
  JOIN 
    jenis_barang jb ON bg.jenis_id = jb.id_jenis_barang
";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
  $barangGadaiData[] = $row;
}
?>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="d-flex justify-content-between flex-wrap">
                    <div class="d-flex align-items-end flex-wrap">
                        <div class="mr-md-3 mr-xl-5">
                            <h2>Data Barang yang akan digadaikan</h2>
                            <p class="mb-md-0">Silahkan Untuk mencari <strong>locker yang kosong</strong> terlebih dahulu</p>
                        </div>
                        <div class="d-flex">
                            <img src="images/ty.png" alt="logo" style="height: 63px; margin-left: 450px;" class="d-none d-md-block">
                        </div>
                    </div>
                </div>   
            </div>
        
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">DATA LOCKER YANG KOSONG</h4>
                        
                        <form class="forms-sample d-flex align-items-center" method="POST" id="locker-form">
                          <div class="form-group flex-grow-1">
                          <label for="locker-search">Berikut Data Locker yang Belum Terisi:</label>
                          <select class="js-example-basic-multiple" name="locker_codes[]" id="locker-search" multiple="multiple" style="width: 100%">
                            <?php foreach ($availableLockers as $locker): ?>
                            <option value="<?php echo htmlspecialchars($locker['kode_locker']); ?>">
                              <?php echo htmlspecialchars($locker['kode_locker']); ?> 
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
                                Locker tidak ditemukan atau tidak tersedia. Silakan cari dengan kode yang lain.
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($showGadaiForm && $lockerData): ?>
                            <div class="alert alert-success mt-3">
                                Locker tersedia: <strong><?php echo htmlspecialchars($lockerData['kode_locker']); ?></strong>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
<?php if ($showGadaiForm && $lockerData): ?>
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">FORM BARANG YANG AKAN DIGADAIKAN</h4>
                        <p class="card-description">
                            Silahkan isi form berikut untuk menggadaikan barang
                        </p>
                        <form class="forms-sample" action="tambah_barang_gadai.php" method="POST" enctype="multipart/form-data">
                          <input type="hidden" name="id_locker" value="<?php echo $lockerData['id_locker']; ?>">
                        <!-- Form Section 1 -->
                        <div class="form-group">
                            <label for="rahin_id">Nama Nasabah</label>
                            <select class="form-control" id="rahin_id" name="rahin_id" required>
                                <option value="">Pilih Nama Nasabah</option>
                                <?php
                                $pegadaian_id = $_SESSION['pegadaian_id'];
                                // Query to fetch rahin yang belum digunakan di barang_gadai
                                $rahinQuery = "
                                    SELECT r.id_rahin, r.nama_rahin, r.nik_rahin, r.no_whatsapp, r.email
                                    FROM rahin r
                                    LEFT JOIN barang_gadai bg ON r.id_rahin = bg.rahin_id
                                    WHERE bg.rahin_id IS NULL
                                    AND r.pegadaian_id = '$pegadaian_id'
                                ";
                                $rahinResult = mysqli_query($conn, $rahinQuery);
                                while ($rahin = mysqli_fetch_assoc($rahinResult)) {
                                    echo '<option value="' . htmlspecialchars($rahin['id_rahin']) . '" 
                                        data-nik="' . htmlspecialchars($rahin['nik_rahin']) . '" 
                                        data-no_whatsapp="' . htmlspecialchars($rahin['no_whatsapp']) . '" 
                                        data-email="' . htmlspecialchars($rahin['email']) . '">'
                                        . htmlspecialchars($rahin['nama_rahin']) . 
                                        '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="nik">NIK Nasabah <span style="color: red;">(Kode barang yang digadaikan)</span></label>
                            <input type="text" class="form-control" id="nik" name="nik_rahin" placeholder="NIK" readonly required>
                        </div>

                        <div class="form-group">
                            <label for="no_whatsapp">Nomor Whatsapp</label>
                            <input type="text" class="form-control" id="no_whatsapp" name="no_whatsapp" placeholder="Nomor Whatsapp" readonly required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" readonly required>
                        </div>

                        <div id="form-nasabah-section" style="display: none;">
                            <div style="border-top: 15px solid #20a1ad; margin: 50px 0;"></div>

                            <!-- Form Section 2 -->
                            <div class="form-group">
                                <label for="no_identitas">Nama Staff Pegadaian</label>
                                <input type="text" class="form-control" id="no_identitas" name="nama" placeholder="Nama Nasabah" value="<?php echo htmlspecialchars($_SESSION['nama']); ?>" readonly required>
                            </div>
                            <div class="form-group">
                                <label for="no_identitas">Kode Pegadaian</label>
                                <input type="text" class="form-control" id="pegadaian_id" name="pegadaian_id" placeholder="Nama Nasabah" value="<?php echo htmlspecialchars($_SESSION['kode_pegadaian']); ?>" readonly required>
                            </div>
                            
                            <div class="form-group">
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
                            
                            <div class="form-group">
                                <label for="tanggal_gadai">Tanggal yang digadaikan</label>
                                <input type="text" class="form-control" id="tanggal_digadai" name="tanggal_digadai" value="<?php echo date('d F Y'); ?>" readonly>
                            </div>

                            <hr> <!-- Separator between sections -->

                            <!-- Form Section 3 -->
                            <div class="form-group">
                                <label for="foto_barang">Foto Pegawai Pegadaian </label>
                                <div>
                                    <button type="button" class="btn" style="color: white; background-color: #20a1ad;" data-toggle="modal" data-target="#cameraModal">
                                        Buka Kamera
                                    </button>
                                    <input type="hidden" id="foto_nasabah" name="foto_nasabah" required>
                                    <div id="photoPreview" class="mt-2" style="display: none;">
                                        <img id="previewImage" style="max-width: 100%; max-height: 200px;">
                                        <button type="button" id="retakePhotoBtn" class="btn btn-secondary btn-sm mt-2">Ambil Ulang</button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="nilai_barang">Upload Foto Barang</label>
                                <input type="file" class="form-control" id="foto_barang" name="foto_barang" placeholder="Dalam Rupiah" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="status_barang">Status</label>
                                <input type="text" class="form-control" id="status_barang" name="status_barang" value="digadai" readonly required>
                            </div>
                            
                            <div class="form-group">
                                <label for="akses">Akses Locker</label>
                                <div>
                                    <button 
                                        type="button" 
                                        class="btn btn-success" 
                                        data-toggle="modal" 
                                        data-target="#aksesModal"
                                        data-locker="<?php echo htmlspecialchars($lockerData['kode_locker']); ?>"
                                        onclick="setSelectedLocker('<?php echo htmlspecialchars($lockerData['kode_locker']); ?>')"
                                    >
                                       Open / Close Locker (<?php echo htmlspecialchars($lockerData['kode_locker']); ?>)
                                    </button>
                                </div>
                            </div>
                           
                            <div class="form-group">
                                <label>Locker yang dipilih</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($lockerData['kode_locker']); ?>" readonly>
                            </div>
                        </div>

                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                                var rahinSelect = document.getElementById('rahin_id');
                                var nikInput = document.getElementById('nik');
                                var waInput = document.getElementById('no_whatsapp');
                                var emailInput = document.getElementById('email');
                                var formNasabahSection = document.getElementById('form-nasabah-section');

                                function toggleFormNasabahSection() {
                                        if (rahinSelect.value) {
                                                formNasabahSection.style.display = 'block';
                                        } else {
                                                formNasabahSection.style.display = 'none';
                                        }
                                }

                                rahinSelect.addEventListener('change', function() {
                                        var selected = rahinSelect.options[rahinSelect.selectedIndex];
                                        nikInput.value = selected.getAttribute('data-nik') || '';
                                        waInput.value = selected.getAttribute('data-no_whatsapp') || '';
                                        emailInput.value = selected.getAttribute('data-email') || '';
                                        toggleFormNasabahSection();
                                });

                                // Initial state
                                toggleFormNasabahSection();
                        });
                        </script>
                          
                          <button type="submit" class="btn btn-success mr-2">Submit</button>
                          <button type="reset" class="btn btn-secondary">Reset</button>
                        </form>
                    </div>
                </div>
            </div>
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
<!-- Modal Akses Locker -->
<!-- Modal Akses Locker -->
<div class="modal fade" id="aksesModal" tabindex="-1" role="dialog" aria-labelledby="aksesModalLabel">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="aksesModalLabel">Kontrol Locker <?php echo $lockerData['kode_locker']; ?></h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        
        <div class="btn-group">
          <button type="button" class="btn btn-lg m-3" style="color: white; background-color: #20a1ad;" onclick="controlLocker('open')">
            <i class="mdi mdi-lock-open"></i> Buka Locker
          </button>
          <button type="button" class="btn btn-lg m-3" style="color: white; background-color: #152453;" onclick="controlLocker('close')">
            <i class="mdi mdi-lock"></i> Tutup Locker
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
// Global variables
const currentLockerId = <?php echo $lockerData['id_locker']; ?>; // ID database locker

function controlLocker(action) {
    const btn = event.target;
    const originalHtml = btn.innerHTML;
    
    // Show loading
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Memproses...';
    btn.disabled = true;
    
    // Call ESP API
    fetch(`http://192.168.193.46/control?id_locker=${currentLockerId}&action=${action}`)
        .then(response => response.json())
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
                throw new Error(data.message);
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: error.message
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
    var tagSelector = new MultiSelectTag('locker-search', {
        maxSelection: 1,              // Only allow selecting 1 locker
        required: true,               // default false.
        placeholder: 'Cari locker...', // default 'Search'.
        onChange: function(selected) {
            console.log('Selection changed:', selected);
        }
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const video = document.getElementById('cameraPreview');
    const canvas = document.getElementById('photoCanvas');
    const takePhotoBtn = document.getElementById('takePhotoBtn');
    const retakePhotoBtn = document.getElementById('retakePhotoBtn');
    const photoPreview = document.getElementById('photoPreview');
    const previewImage = document.getElementById('previewImage');
    const fotoNasabahInput = document.getElementById('foto_nasabah'); // <-- ganti di sini
    let stream = null;

    // Start camera when page loads
    startCamera();

    function startCamera() {
        navigator.mediaDevices.getUserMedia({ video: true, audio: false })
            .then(function(s) {
                stream = s;
                video.srcObject = stream;
            })
            .catch(function(err) {
                console.error("Error accessing camera: ", err);
                alert("Tidak dapat mengakses kamera. Pastikan Anda memberikan izin akses kamera.");
            });
    }

    takePhotoBtn.addEventListener('click', function() {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

        const imageData = canvas.toDataURL('image/jpeg', 0.8);

        previewImage.src = imageData;
        photoPreview.style.display = 'block';
        fotoNasabahInput.value = imageData; // <-- simpan base64 ke hidden input

        takePhotoBtn.style.display = 'none';
        retakePhotoBtn.style.display = 'inline-block';
    });

    retakePhotoBtn.addEventListener('click', function() {
        photoPreview.style.display = 'none';
        takePhotoBtn.style.display = 'inline-block';
        retakePhotoBtn.style.display = 'none';
        fotoNasabahInput.value = '';
    });

    window.addEventListener('beforeunload', function() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
    });

    document.querySelector('form').addEventListener('submit', function() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
    });
});
</script>
<?php
include "footer.php";
?>            