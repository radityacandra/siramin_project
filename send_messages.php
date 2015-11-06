<?php
require_once 'koneksi.php';
require_once 'koneksi_2.php';
class send_messages{
	/**
	 * buat ngirim sms ke user tertentu
	 * @param String $no_hp
	 * @param String $isi_pesan
	 * @param String $username
	 */
	public function send($no_hp, $kelembaban, $last_siramin, $username){
		//echo 'test';
		$koneksi = new database();
		$koneksi->connectDatabase();
		$isi_pesan = "Lahan anda perlu penyiraman, terakhir disiram pada ".$last_siramin.". Saat ini kelembaban tanah ".$kelembaban.". Balas OK untuk menyiram, balas NO untuk menunda selama 6 jam.";
		$query = "INSERT INTO outbox (DestinationNumber, TextDecoded, CreatorID, MultiPart) VALUES ('$no_hp', '$isi_pesan', '$username', 'true')";
		$koneksi->query($query);
		
		/* $koneksi2 = new database();
		$koneksi2->connectDatabase();
		$isi_pesan2 = "Balas OK untuk menyiram, balas NO untuk menunda selama 6 jam.";
		$query2 = "INSERT INTO outbox (DestinationNumber, TextDecoded, CreatorID) VALUES ('$no_hp', '$isi_pesan2', '$username')";
		$koneksi2->query($query2); */
		
		/* $query = "UPDATE lampu_pi SET iterasi_check = 0 WHERE username='$username'";
		$koneksi_3 = new database_2();
		$koneksi_3->connectDatabase_2();
		$koneksi_3->query_2($query2); */
	}
	
	public function confirm_send($no_hp, $username){
		$koneksi = new database();
		$koneksi->connectDatabase();
		
		$isi_pesan = "Lahan Anda akan disiram dalam waktu kurang dari 5 menit dari sekarang secara otomatis.";
		$query = "INSERT INTO outbox (DestinationNumber, TextDecoded, CreatorID) VALUES ('$no_hp', '$isi_pesan', '$username')";
		$koneksi->query($query);
	}
}
?>