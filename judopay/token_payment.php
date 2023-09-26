<?php
require("../admin/inc/judopay/vendor/autoload.php");

$judoId = '100403-658';
$apiToken = '2hdFclZ3IzumACTs';
$apiSecret = 'e8e2d4c6dd5b3af2071d858f1c4a443e29435ee03c281d65fe4a4e023d98b483';

$judopay = new \Judopay(
    array(
        'apiToken' => $apiToken,
        'apiSecret' => $apiSecret,
        'judoId' => $judoId
    )
);

$consumerToken = 'SgZFVX3S47nMssqf';
$cardToken = 'wpl43OavqZTwS3TNq4rpiEu2Dl24lpOf';
$oneUseToken = 'm/YXoaPF8mCqJqN/EDZ2LqyVt9XLiVdDBMAioRk5441Lwhy1sT…IMcg/Rk0iGOpctdGBUn4+G40vwQ3IWGoC02kZ5gh/Ox9u4WU=';

$tokenPayment = $judopay->getModel('OneUseTokenPayment');
$tokenPayment->setAttributeValues(
    array(
        'judoId' => $judoId,
        'yourConsumerReference' => '123457',
        'yourPaymentReference' => '123457',
        'amount' => 1.03,
        'currency' => 'GBP',
        'oneUseToken' => $oneUseToken
    )
);

try {
    $response = $tokenPayment->create();
    if ($response['result'] === 'Success') {
        echo 'Token payment successful';
        echo '<pre>';
        print_R($response);
    } else {
        echo 'There were some problems while processing your payment';
    }
} catch (\Judopay\Exception\ValidationError $e) {
    echo $e->getSummary();
} catch (\Judopay\Exception\ApiException $e) {
    echo $e->getSummary();
} catch (\Exception $e) {
    echo $e->getMessage();
}