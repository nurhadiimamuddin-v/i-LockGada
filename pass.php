<?php
include 'config.php';

if (isset($_GET['id_manager'])) {
  $id_manager = $_GET['id_manager'];
  $sql = mysqli_query($conn, "SELECT * FROM manager WHERE id_manager = '$id_manager'");
  $data = mysqli_fetch_assoc($sql);

  if ($data) {
    ?>
    <table>
      <tr>
        <td><strong>Password :</strong></td>
        <td><?= $data['password']; ?></td>
      </tr>
    </table>
    <?php
  } else {
    echo "Data tidak ditemukan.";
  }
} else {
  echo "ID Manager tidak disediakan.";
}
?>
<br>
<a href="manager.php"><button>Kembali</button></a>