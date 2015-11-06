<?php
require_once 'koneksi.php';
require_once 'koneksi_2.php';
require_once 'send_messages.php';
class baca{
	/**
	 * buat baca semua sms yang masuk, yang belum dibaca (readed = false)
	 * kalau sms masuk dikenali, diproses, kalau nggak, diabaikan (misal dari Indosat, unregistered, dll)
	 * semua sms yang sudah dibaca diset readed = true
	 */
	public function baca_sms(){
		//echo 'test';
		$username = "";
		$koneksi = new database();
		$koneksi->connectDatabase();
		$iterasi = 0;
		$query = "SELECT ReceivingDateTime, SenderNumber, TextDecoded FROM inbox WHERE readed='false'";
		$koneksi->query($query);
		if ($koneksi->numRows()>=0){
			//echo 'test2';
			$result = $koneksi->rows();
			foreach ($result as $index => $container){
				$nomor = $container['SenderNumber'];
				echo $nomor;
				$query = "SELECT username FROM user WHERE nomor_hp='$nomor'";
				$koneksi->query($query);
				if ($koneksi->numRows()>0){
					$hasil = $koneksi->rows();
					foreach ($hasil as $key => $value){
						$username = $value['username'];
					}
					
					$isi_pesan = $container['TextDecoded'];
					//echo $isi_pesan;
					if ($isi_pesan == 'OK' || 'Ok' || 'ok'){
						$query = "UPDATE device SET token='ON' WHERE username='$username'";
						$koneksi->query($query);
						
						//VIA WEB SERVICE
						$status = 1;
						$iterasi = 0;
						$receive_date = $container['ReceivingDateTime'];
						$url = "http://api.siramin.web.id/index.php/lampu_pi";
						$curl = curl_init($url);
						$data_container = array(
							'iterasi' => $iterasi,
							'status' => $status,
							'last_siramin' => $receive_date,
							'username' => $username,
						);
						$encoded_data = json_encode($data_container);
						curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
						curl_setopt($curl, CURLOPT_POSTFIELDS, $encoded_data);
						curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
						
						$respon = curl_exec($curl);
						$kirim = new send_messages();
						$kirim->confirm_send($nomor, $username);
						
						//VIA QUERY / REMOTE DATABASE
						/* $koneksi_2 = new database_2();
						$koneksi_2->connectDatabase_2();
						$query = "UPDATE lampu_pi SET status='1' WHERE username='$username'";
						$koneksi_2->query_2($query);
						
						$query = "UPDATE lampu_pi SET iterasi_check = 0 WHERE username='$username'";
						$koneksi_2->query_2($query);
						
						$kirim = new send_messages();
						$kirim->confirm_send($nomor, $username);
						
						$query = "UPDATE lampu_pi SET last_siramin ='$receive_date' WHERE username='$username'";
						$koneksi_2->query_2($query); */
					}
					if ($isi_pesan == 'NO'){
						//VIA WEB SERVICE
						$status = 0;
						$iterasi = 0;
						$receive_date = "";
						$url = "http://api.siramin.web.id/index.php/lampu_pi_last_siramin";
						$curl = curl_init($url);
						$data_container = array(
								'iterasi' => $iterasi,
								'status' => $status,
								'username' => $username,
						);
						$encoded_data = json_encode($data_container);
						curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
						curl_setopt($curl, CURLOPT_POSTFIELDS, $encoded_data);
						curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
						
						$respon = curl_exec($curl);
						
						//VIA QUERY / REMOTE DATABASE
						/* $query = "UPDATE lampu_pi SET iterasi_check = 0 WHERE username='$username'";
						$koneksi_2->query_2($query); */
					}
					$koneksi->connectDatabase();
					$query = "UPDATE inbox SET readed='true' WHERE SenderNumber='$nomor'";
					$koneksi->query($query);
				}
			}
		}
	}
}
?>