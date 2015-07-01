<?php
require_once 'koneksi.php';
class baca{
	/**
	 * buat baca semua sms yang masuk, yang belum dibaca (readed = false)
	 * kalau sms masuk dikenali, diproses, kalau nggak, diabaikan (misal dari Indosat, unregistered, dll)
	 */
	public function baca_sms(){
		$username = "";
		$koneksi = new database();
		$koneksi->connectDatabase();
		$query = "SELECT ReceivingDateTime, SenderNumber, TextDecoded FROM inbox WHERE readed='false'";
		$koneksi->query($query);
		if ($koneksi->numRows()){
			$result = $koneksi->rows();
			foreach ($result as $index => $container){
				$nomor = $container['SenderNumber'];
				$query = "SELECT username FROM user WHERE nomor_hp='$nomor'";
				$koneksi->query($query);
				if ($koneksi->numRows()>0){
					$hasil = $koneksi->rows();
					foreach ($hasil as $key => $value){
						$username = $value['username'];
					}
					$isi_pesan = $container['TextDecoded'];
					if ($isi_pesan == 'OK, SIRAMIN'){
						$query = "UPDATE device SET token='ON' WHERE username='$username'";
						$koneksi->query($query);
					}
					$query = "UPDATE inbox SET readed='true' WHERE SenderNumber='".$nomor."'";
					$koneksi->query($query);
				}
			}
		}
	}
}
?>