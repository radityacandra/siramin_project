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
		$isi_pesan = "Ladang anda perlu penyiraman, terakhir disiram pada DD/MM/YYYY. Saat ini kelembaban tanah XX.";
		$query = "INSERT INTO outbox (DestinationNumber, TextDecoded, CreatorID) VALUES ('$no_hp', '$isi_pesan', '$username')";
		$koneksi->query($query);
		
		$koneksi2 = new database();
		$koneksi2->connectDatabase();
		$isi_pesan2 = "Balas OK untuk menyiram";
		$query2 = "INSERT INTO outbox (DestinationNumber, TextDecoded, CreatorID) VALUES ('$no_hp', '$isi_pesan2', '$username')";
		$koneksi2->query($query2);
	}
	
	public function confirm_send($no_hp, $username){
		$koneksi = new database();
		$koneksi->connectDatabase();
		
		$isi_pesan = "ladang anda akan disiram dalam waktu kurang dari 5 menit dari sekarang secara otomatis.";
		$query = "INSERT INTO outbox (DestinationNumber, TextDecoded, CreatorID) VALUES ('$no_hp', '$isi_pesan', '$username')";
		$koneksi->query($query);
	}
}
?>