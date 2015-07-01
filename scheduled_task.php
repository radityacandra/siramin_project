<?php
require_once 'baca.php';
require_once 'check.php';
require_once 'send_messages.php';

//pertama nge-cek keadaan device dulu
$device = new check();
$device->cek_sms();

//kedua baca kalau-kalau ada sms masuk dari user
$inbox = new baca();
$inbox->baca_sms();
?>