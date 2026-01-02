<?php
$url = 'https://api-m.sandbox.paypal.com';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // đảm bảo kiểm tra SSL
$response = curl_exec($ch);
if(curl_errno($ch)){
    echo 'cURL error: ' . curl_error($ch);
} else {
    echo 'Response received successfully!';
}
curl_close($ch);
?>