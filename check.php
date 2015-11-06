<?php
require_once 'koneksi.php';
require_once 'koneksi_2.php';
require_once 'send_messages.php';
class check{
	/**
	 * buat ngecek kelembaban masing-masing jenis sayuran. kalau kelembaban kurang, dikasih sms ke pemiliknya
	 */
	public function cek_sms(){
		$sms = new send_messages();
		$nomor = 0;
		$iterasi = 0;
		$koneksi = new database();
		/* $koneksi_2 = new database_2();
		$koneksi_2->connectDatabase_2(); */
		
		/* $query = "SELECT * FROM lampu_pi";
		$koneksi_2->query_2($query);
		var_dump($koneksi_2->rows_2()); */
		
		//VIA QUERY / REMOTE DATABASE
		/* //untuk jenis sayuran, diambil yang kelembabannya tertentu 
		$query = "SELECT kelembaban, last_siramin, username, iterasi_check FROM lampu_pi WHERE jenis_sayuran='sawi' AND kelembaban<130";
		$koneksi_2->query_2($query);
		if($koneksi_2->numRows_2()>0){
			$result = $koneksi_2->rows_2();
			foreach ($result as $index => $container){
				$username = $container['username'];
				echo $username;
				$iterasi = $container['iterasi_check'];
				if ($iterasi == 30){
					$koneksi->connectDatabase();
					$query = "SELECT nomor_hp FROM user WHERE username='$username'";
					$koneksi->query($query);
					if ($koneksi->numRows()>0){
						$result_nomor = $koneksi->rows();
						foreach ($result_nomor as $index => $nomor_hp){
							$nomor = $nomor_hp['nomor_hp'];
							echo $nomor;
						}
					}
					$sms->send($nomor, $container['kelembaban'], $container['last_siramin'], $username);
					
					$koneksi_2->connectDatabase_2();
					$query = "UPDATE lampu_pi SET iterasi_check = 0 WHERE username='$username'";
					$koneksi_2->query_2($query);
				}
				elseif ($iterasi<30){
					$query = "UPDATE device SET iterasi_check = iterasi_check + 1 WHERE username='$username'";
					$koneksi->query($query);
					$query = "UPDATE lampu_pi SET iterasi_check = iterasi_check + 1 WHERE username='$username'";
					$koneksi_2->query_2($query);
				}
			}
		} */
		
		//VIA WEB SERVICE
		$url = "http://api.siramin.web.id/index.php/lampu_pi";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		$decoded_result = json_decode($result,true);
		var_dump($decoded_result, true);
		if(sizeof($decoded_result)!=0){
			foreach ($decoded_result as $key =>$value){
				
				if ($value['username']!=""){
					$username = $value['username'];
					echo "username adalah: ".$username;
					$iterasi = $value['iterasi_check'] + 1;
					echo "iterasi ke-".$iterasi;
					if ($value['iterasi_check']==100){
						$koneksi->connectDatabase();
						$query = "SELECT nomor_hp FROM user WHERE username='$username'";
						$koneksi->query($query);
						if ($koneksi->numRows()>0){
							$result_nomor = $koneksi->rows();
							foreach ($result_nomor as $index => $nomor_hp){
								$nomor = $nomor_hp['nomor_hp'];
								echo $nomor;
							}
						}
						$sms->send($nomor, $value['kelembaban'], $value['last_siramin'], $username);
						
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
					}
					elseif ($value['iterasi_check']<100){
						//VIA WEB SERVICE
						$status = "";
						$iterasi = $value['iterasi_check'] + 1;
						$receive_date = "";
						$url = "http://api.siramin.web.id/index.php/lampu_pi_status_last_siramin";
						$curl = curl_init($url);
						$data_container = array(
								'iterasi' => $iterasi,
								'username' => $username,
						);
						$encoded_data = json_encode($data_container);
						curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
						curl_setopt($curl, CURLOPT_POSTFIELDS, $encoded_data);
						curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
						
						$respon = curl_exec($curl);
					}
				}	
			}
		}
	}
}	
?>