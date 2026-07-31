<?php
include 'config.php';

if (isset($_GET['id_nasabah'])) {
  $id_nasabah = $_GET['id_nasabah'];
  $sql = mysqli_query($conn, "SELECT * FROM nasabah WHERE id_nasabah = '$id_nasabah'");
  $data = mysqli_fetch_assoc($sql);

  if ($data) {
    ?>
    <table>
      <tr>
        <td><strong>Password :</strong></td>
        <td>
          <?php
            // Gantikan setiap karakter password dengan angka 0 atau 1 secara acak
            $password = $data['password'];
            $masked = '';
            for ($i = 0; $i < strlen($password); $i++) {
              $masked .= rand(0, 1);
            }
            echo $masked;
          ?>
        </td>
      </tr>
    </table>
    <?php
  } else {
    echo "Data tidak ditemukan.";
  }
} else {
  echo "ID nasabah tidak disediakan.";
}
?>
<br>
<a href="nasabah.php"><button>Kembali</button></a>