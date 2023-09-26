<?php
$URL = "https://gw1.judopay.com/transactions/payments";
$URL = "https://gw1.judopay-sandbox.com/transactions/payments";

$judoId = '100403-658';
$apiToken = '2hdFclZ3IzumACTs';
$apiSecret = 'e8e2d4c6dd5b3af2071d858f1c4a443e29435ee03c281d65fe4a4e023d98b483';
$oneUseToken = "KN+hkYkeT3LVfvAlb43K+LuWuM9Z0w+zjf/fK0rNv2yZUt3TI1kjf9CsF2a3haJWA8TiDEorUpSUTHCd9UzRcC+P/VtbuOP4uqZOXS6EMjvmMom4isuHjKPB5PumnB2bMGn/PSVt0yDjTPt0Ar7tQTDphEqCYOoIqVjxZZ947aIb4bgx/3eGRd3Uj0Y972juqq7SkzPUPS/ObZFfym5MkvY9gCKoUPFtixlqbBTHdp1LH2Hu52Wd+z8UkHLktw+FrMhDAHGfkBhrW0Gp4L9M9xHFuil0xqj7otq2ZPsWujws8UbUD+ajkoEGE1XP7WOMtsMR90h0PqbezGFrQ5auPtOX+glfnEZwKxCDvldkm9oLzGQnw8fabDFxxW4lb1iq6msmAsm9WWC1xGtuZ66HAEW/z9sm7JgDKw1ddmIFa2aw6sndUTG+xxG1+Sp2kgnx4en0woCqHMiPYeHQNMhlguAAKHehoVwzWH9WEAurO9Gurgj9HipGJfscYvobRMled5lGoIC3BER/Rr/aVbG6P1FhA67x/oQoensgJDVN1HHlvkmABnXjEE0X0YEKTb3lUYj6/4I/EPE16U77hL1qxk9KNY4c8LtzEY9iTohh+Ilau2S7+fVNakk9+twHxwxGzyliDEU9rhgGsk76OBO1qoPvTkHJdC7UPbS/pPA7MyA=";

/* $post = [
    'judoId' => $judoId,
    'yourConsumerReference' => '123457',
    'yourPaymentReference' => '123457',
    'amount' => 1.03,
    'currency' => 'GBP',
    'oneUseToken' => $oneUseToken
]; */

$post = [
  "yourConsumerReference" => "consumer0053252",
  "yourPaymentReference" => "payment12412312",
  "judoId" => $judoId,
  "amount" => 12.34,
  "cardNumber" => "4976000000003436",
  "expiryDate" => "12/20",
  "cv2" => "452",
  "currency" => "GBP"
];

$postvars = '';
foreach($post as $key=>$value) {
    $postvars .= $key . "=" . $value . "&";
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$URL);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "API-Version: 5.2",
    "Content-Type: application/json"
));
curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_USERPWD, "$apiToken:$apiSecret");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
$result=curl_exec ($ch);
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
if (curl_error($ch)) {
    echo "Error : " . $error_msg = curl_error($ch);
}
curl_close ($ch);

print_r($result);

?>