<meta http-equiv="refresh" content="3">
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

echo '\n ------------------------------------------------------------';
/* $url = "http://api.siramin.web.id/index.php/lampu_pi";
 $ch = curl_init($url);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 $result = curl_exec($ch);
 $decoded_result = json_decode($result,true);
 foreach ($decoded_result as $key => $value){
 	echo $value['username'];
 } */
 
/* $status = "1";
$iterasi = 5;
$receive_date = "asdf";
$username = "radityacandra";
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

$body = json_decode($encoded_data);
var_dump($body);
if ($body->last_siramin == "" && $body->status != ""){
  			echo "semua ada kecuali last_siramin";
  		}
  		elseif ($body->last_siramin == "" && $body->status == ""){
			echo "last_siramin sama status tidak ada";
  		} */
/* $sms = new send_messages();
$sms->send('+6282245976275', '23', '14/10/2015 08:31', 'radityacandra'); */
?>