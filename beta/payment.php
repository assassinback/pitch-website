<?php include('config.php');
include('admin/inc/judopay/vendor/autoload.php');

$date = date('Y-m-d');
$now = date('Y-m-d H:i:s');
generateLog('payment', 'Start : ' . $now);
$users = $db->query('SELECT * FROM ' . $dbPrefix . 'user_test_plan WHERE next_payment_date < ?', array($date));

if ($users->num_rows() == 0) {
    generateLog('payment', 'No user Found');
    exit();
}

$users = $users->result_array();    
foreach ($users as $user) {
    
    $user_id = $user['user_id'];
    $price = $user['price'];
    $plan_type = $user['type'];
    $plan_id = $user['test_plan_id'];
    
    generateLog('payment', 'user_id : ' . $user_id);
    
    $judopay = new \Judopay(
        array(
            'apiToken' => JUDO_API_TOKEN,
            'apiSecret' => JUDO_API_SECRET,
            'judoId' => JUDO_ID
        )
    );
    
    $reference = JUDO_REFERENCE_PREFIX . '-' . $user_id . '-' . date('YmdHis');
    $tokenPayment = $judopay->getModel('TokenPayment');
    $tokenPayment->setAttributeValues(
        array(
            'judoId' => JUDO_ID,
            'yourConsumerReference' => $user['consumer_reference'],
            'yourPaymentReference' => $reference,
            'amount' => $price,
            'currency' => CURRENCY_CODE,
            'consumerToken' => $user['consumer_token'],
            'cardToken' => $user['card_token'],
        )
    );
    
    try {
        $response = $tokenPayment->create();
        if ($response['result'] === 'Success') {
            $payment_receipt_id = $response['receiptId'];
        } else {
            $error = 'There were some problems while processing your payment';
        }
        generateLog('payment', 'response : ' . print_r($response, true));
    } catch (\Judopay\Exception\ValidationError $e) {
        $error =  $e->getSummary();
    } catch (\Judopay\Exception\ApiException $e) {
        $error =  $e->getSummary();
    } catch (\Exception $e) {
        $error =  $e->getMessage();
    }
    
    if (!isset($error)) {
        
        $plan = $db->query('SELECT * FROM ' . $dbPrefix . 'test_plan WHERE status = 1 and id = ?', array($plan_id));
        $planInfo = $plan->row_array();
        
        if ($price != 0) {
            $previous_payment_date = date('Y-m-d H:i:s');
            if ($plan_type == 'year') {
                $next_payment_date = date('Y-m-d H:i:s', strtotime('+1 years'));
            } else {
                $next_payment_date = date('Y-m-d H:i:s', strtotime('+1 months'));
            }
        } else {
            $plan_type = null;
            $previous_payment_date = null;
            $next_payment_date = null;
        }
        
        $planData = array(
                            'previous_payment_date' => $previous_payment_date,
                            'next_payment_date' => $next_payment_date,
                            'payment_reference' => $reference,
                            'payment_receipt_id' => $payment_receipt_id,
                        );
        
        $where ='user_id =' . $user_id .'';
        $update = updateData('user_test_plan', $planData, $where);
        
        $transactionData = array(
                            'user_id' => $user_id,
                            'test_plan_id' => $plan_id,
                            'type' => $plan_type,
                            'receipt_id' => $payment_receipt_id,
                            'amount' => $price,
                            'transaction_date' => date('Y-m-d H:i:s')
                        );
        
        insertData('user_transaction', $transactionData);
        
        $amenities = $db->query('SELECT * FROM ' . $dbPrefix . 'test_plan_amenities as test_plan_amenities INNER JOIN ' . $dbPrefix . 'amenities as amenities ON (amenities.id = test_plan_amenities.amenity_id AND amenities.status = 1) WHERE test_plan_amenities.test_plan_id = ?', array($plan_id));
        $amenities = $amenities->result_array();
        
        $date = date('Y-m-d H:i:s');
        $planAmenityData = array(
                            'availability' => 0,
                            'date_modified' => $date
                        );
        $where ='user_id =' . $user_id .' AND test_plan_id > 0';
        updateData('user_amenities', $planAmenityData, $where);
        
        generateLog('payment', 'success');
        generateLog('payment', "\n");
    } else {
        generateLog('payment', 'Error : ' . $error);
        generateLog('payment', "\n");
    }
}

generateLog('payment', "End : " . date('Y-m-d H:i:s'));
generateLog('payment', "\n");
generateLog('payment', "\n");