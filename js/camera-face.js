// js/camera-face.js

document.addEventListener("DOMContentLoaded", async function () {
  const video = document.getElementById("cameraPreview");
  const canvas = document.getElementById("photoCanvas");
  const takePhotoBtn = document.getElementById("takePhotoBtn");
  const openCameraBtn = document.querySelector(
    'button[data-target="#cameraModal"]'
  );
  const retakePhotoBtn = document.getElementById("retakePhotoBtn");
  const photoPreviewDiv = document.getElementById("photoPreview");
  const previewImage = document.getElementById("previewImage");
  const fotoNasabahInput = document.getElementById("foto_nasabah");
  let stream = null;
  let faceDetected = false;

  // Path ke model Anda. Sesuaikan jika perlu.
  const MODEL_URL = "/smart_locker/models/tiny_face_detector";

  try {
    console.log("Memuat model face-api...");
    await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
    console.log("Model berhasil dimuat.");
  } catch (error) {
    console.error("Error memuat model face-api:", error);
    alert(
      "Gagal memuat model deteksi wajah. Fitur kamera mungkin tidak berfungsi dengan benar."
    );
    if (openCameraBtn) {
      openCameraBtn.disabled = true;
      openCameraBtn.textContent = "Model Gagal Dimuat";
    }
    return;
  }

  function startCamera() {
    if (stream) {
      stream.getTracks().forEach((track) => track.stop());
    }
    navigator.mediaDevices
      .getUserMedia({ video: true, audio: false })
      .then(function (s) {
        stream = s;
        video.srcObject = stream;
        video.play();
        takePhotoBtn.disabled = true;
        takePhotoBtn.textContent = "Tidak Ada Wajah Terdeteksi";
        takePhotoBtn.classList.remove("btn-success");
        takePhotoBtn.classList.add("btn-danger");
      })
      .catch(function (err) {
        console.error("Error mengakses kamera: ", err);
        alert("Tidak dapat mengakses kamera. Pastikan Anda memberikan izin.");
      });
  }

  $("#cameraModal").on("shown.bs.modal", function () {
    startCamera();
  });

  $("#cameraModal").on("hidden.bs.modal", function () {
    if (stream) {
      stream.getTracks().forEach((track) => track.stop());
      stream = null;
      console.log("Kamera dihentikan.");
    }
    if (fotoNasabahInput.value === "") {
      takePhotoBtn.style.display = "inline-block";
      if (retakePhotoBtn) retakePhotoBtn.style.display = "none";
      photoPreviewDiv.style.display = "none";
    }
  });

  video.addEventListener("play", () => {
    console.log("Video sedang diputar, memulai deteksi wajah...");
    const displaySize = { width: video.width, height: video.height };
    const interval = setInterval(async () => {
      if (!stream || video.paused || video.ended) {
        if (fotoNasabahInput.value === "") {
          takePhotoBtn.disabled = true;
          takePhotoBtn.textContent = "Kamera Tidak Aktif";
          takePhotoBtn.classList.remove("btn-success");
          takePhotoBtn.classList.add("btn-danger");
        }
        return;
      }
      const detections = await faceapi.detectAllFaces(
        video,
        new faceapi.TinyFaceDetectorOptions()
      );
      faceDetected = detections.length > 0;
      if (faceDetected) {
        takePhotoBtn.disabled = false;
        takePhotoBtn.textContent = "Ambil Foto (Wajah Terdeteksi)";
        takePhotoBtn.classList.remove("btn-danger");
        takePhotoBtn.classList.add("btn-success");
      } else {
        takePhotoBtn.disabled = true;
        takePhotoBtn.textContent = "Tidak Ada Wajah Terdeteksi";
        takePhotoBtn.classList.remove("btn-success");
        takePhotoBtn.classList.add("btn-danger");
      }
    }, 500);
    $("#cameraModal").on("hide.bs.modal", function () {
      clearInterval(interval);
      console.log("Interval deteksi dihentikan.");
    });
    takePhotoBtn.addEventListener("click", function () {
      if (faceDetected) clearInterval(interval);
    });
  });

  takePhotoBtn.addEventListener("click", function () {
    if (!faceDetected && !video.paused) {
      alert("Wajah tidak terdeteksi atau kamera belum siap.");
      return;
    }
    if (video.readyState < video.HAVE_ENOUGH_DATA) {
      alert("Kamera belum siap sepenuhnya.");
      return;
    }
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext("2d").drawImage(video, 0, 0, canvas.width, canvas.height);
    const imageData = canvas.toDataURL("image/jpeg", 0.9);
    if (previewImage) previewImage.src = imageData;
    photoPreviewDiv.style.display = "block";
    fotoNasabahInput.value = imageData;
    $("#cameraModal").modal("hide");
    if (openCameraBtn) openCameraBtn.textContent = "Ubah Foto Nasabah";
  });

  if (retakePhotoBtn) {
    retakePhotoBtn.addEventListener("click", function () {
      photoPreviewDiv.style.display = "none";
      if (previewImage) previewImage.src = "";
      fotoNasabahInput.value = "";
      if (openCameraBtn) openCameraBtn.textContent = "Buka Kamera";
      takePhotoBtn.disabled = true;
      takePhotoBtn.textContent = "Tidak Ada Wajah Terdeteksi";
      takePhotoBtn.classList.remove("btn-success");
      takePhotoBtn.classList.add("btn-danger");
    });
  }

  window.addEventListener("beforeunload", function () {
    if (stream) {
      stream.getTracks().forEach((track) => track.stop());
    }
  });

  var form = document.querySelector(
    'form.forms-sample[action="tambah_barang_gadai.php"]'
  );
  if (form) {
    form.addEventListener("submit", function () {
      if (stream) {
        stream.getTracks().forEach((track) => track.stop());
      }
      if (fotoNasabahInput.required && fotoNasabahInput.value === "") {
        alert("Foto nasabah wajib diambil.");
      }
    });
  }
});
