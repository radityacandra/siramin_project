<?php
require_once 'koneksi.php';
require_once 'send_messages.php';
class check{
	/**
	 * buat ngecek kelembaban masing-masing jenis sayuran. kalau kelembaban kurang, dikasih sms ke pemiliknya
	 */
	public function cek_sms(){
		$koneksi = new database();
		$koneksi->connectDatabase();
		$sms = new send_messages();
		$nomor = 0;
		//untuk jenis sayuran, diambil yang kelembabannya tertentu 
		$query = "SELECT kelembaban, last_siramin, username FROM device WHERE jenis_sayuran='sawi' AND kelembaban<130";
		$koneksi->query($query);
		if($koneksi->numRows()>0){
			$result = $koneksi->rows();
			foreach ($result as $index => $container){
				$username = $container['username'];
				$query = "SELECT nomor_hp FROM user WHERE username='$username'";
				$koneksi->query($query);
				if ($koneksi->numRows()>0){
					$result_nomor = $koneksi->rows();
					foreach ($result_nomor as $index => $nomor_hp){
						$nomor = $nomor_hp['nomor_hp'];
						echo $nomor;
					}
				}
				
				$sms->send($nomor, $container['kelembaban'], $container['last_siramin'], $container['username']);
			}
		}
		
		//untuk jenis buah, diambil yang kelembabannya tertentu
		$query = "SELECT kelembaban, last_siramin, username FROM device WHERE jenis_sayuran='tomat' AND kelembaban<130";
		$koneksi->query($query);
		if($koneksi->numRows()>0){
			$result = $koneksi->rows();
			foreach ($result as $index => $container){
				$username = $container['username'];
				$query = "SELECT nomor_hp FROM user WHERE username='$username'";
				$koneksi->query($query);
				if ($koneksi->numRows()>0){
					$result_nomor = $koneksi->rows();
					foreach ($result_nomor as $index => $nomor_hp){
						$nomor = $nomor_hp['nomor_hp'];
						echo $nomor;
					}
				}
				
				$sms->send($nomor, $container['kelembaban'], $container['last_siramin'], $container['username']);
			}
		}
	}
}	
?>