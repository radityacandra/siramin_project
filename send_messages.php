<?php
require_once 'koneksi.php';
class send_messages{
	/**
	 * buat ngirim sms ke user tertentu
	 * @param String $no_hp
	 * @param String $isi_pesan
	 * @param String $username
	 */
	public function send($no_hp, $kelembaban, $last_siramin, $username){
		$koneksi = new database();
		$koneksi->connectDatabase();
		$isi_pesan = "Ladang anda perlu penyiraman, terakhir disiram pada DD/MM/YYYY. Saat ini kelembaban tanah XX. Balas 'OK SIRAMIN' untuk menyiram";
		$query = "INSERT INTO outbox (DestinationNumber, TextDecoded, CreatorID) VALUES ('$no_hp', '$isi_pesan', '$username')";
		$koneksi->query($query);
	}
}
?>