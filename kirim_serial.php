<?php
require_once("php_serial.class.php");

$serial = new phpSerial();
$serial->deviceSet("COM3"); // Ganti COM3 sesuai dengan port NodeMCU kamu (cek di Device Manager)
$serial->confBaudRate(9600);
$serial->deviceOpen();

$perintah = $_POST['perintah'] ?? '';
$serial->sendMessage($perintah . "\n");

$serial->deviceClose();
echo "Perintah dikirim: $perintah";
?>
