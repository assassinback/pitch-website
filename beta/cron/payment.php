<?php 
$logFile = 'payment-subscription';

$date = date('Y-m-d');
$now = date('Y-m-d H:i:s');
generateLog($logFile, 'Start : ' . $now);
$users = $db->query('SELECT *, user_test_plan.* FROM ' . $dbPrefix . 'user_test_plan as user_test_plan INNER JOIN ' . $dbPrefix . 'user as USER ON user.id = user_test_plan.user_id WHERE user_test_plan.next_payment_date < ?', array($date));

if ($users->num_rows() == 0) {
    generateLog($logFile, 'No user Found');
} else {
    
    $users = $users->result_array();
    foreach ($users as $user) {
        
        $user_id = $user['user_id'];
        $price = $user['price'];
        $plan_type = $user['type'];
        $plan_id = $user['test_plan_id'];
        
        generateLog($logFile, 'user_id : ' . $user_id);
        
        $judo_parameters = getJudoAPIToken();
        $judopay = new \Judopay($judo_parameters);
        
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
            generateLog($logFile, 'response : ' . print_r($response, true));
        } catch (\Judopay\Exception\ValidationError $e) {
            $error =  $e->getSummary();
        } catch (\Judopay\Exception\ApiException $e) {
            $error =  $e->getSummary();
        } catch (\Exception $e) {
            $error =  $e->getMessage();
        }
        
        $plan = $db->query('SELECT * FROM ' . $dbPrefix . 'test_plan WHERE status = 1 and id = ?', array($plan_id));
        $planInfo = $plan->row_array();
        
        if (!isset($error)) {
            
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
            
            $emailSubject = 'Subscription Plan Renewal';
            $emailMessage = '<p>Hi ' . $user['first_name'] . ' ' . $user['last_name'] . '</p>';
            $emailMessage .= '<p>Your plan has been renewed successfully. Please find the plan details below:</p>';
            $emailMessage .= '<p><b>Plan</b> : ' . $planInfo['title'] . ' - ' . $planInfo['sub_title'] . '</p>';
            if ($price != 0) {
                $emailMessage .= '<p><b>Type</b> : ' . $plan_type . '</p>';
            }
            $emailMessage .= '<p><b>Price</b> : ' . formatPrice($price) . '</p>';
            $emailMessage .= '<p><b>Receipt ID</b> : ' . $payment_receipt_id . '</p>';
            $emailMessage .= '<p><b>Next Payment Date</b> : ' . formatDate($next_payment_date) . '</p>';
            
            $msgdata = array(
                'to' => array($user['email']),
                'subject' => $emailSubject,
                'message' => $emailMessage
            );
            sendMsg($msgdata);
            
            generateLog($logFile, 'success');
            generateLog($logFile, "\n");
        } else {
            
            generateLog($logFile, 'Error : ' . $error);
            
            $plan = $db->query('SELECT * FROM ' . $dbPrefix . 'test_plan WHERE status = 1 ORDER BY monthly_price ASC LIMIT 1', array());
            
            if ($plan->num_rows() > 0) {
                
                $planInfo = $plan->row_array();
                $plan_id = $planInfo['id'];
                generateLog($logFile, 'Plan : ' . $plan_id);
                
                $purchase_date = date('Y-m-d H:i:s');
                $planData = array(
                                    'test_plan_id' => $planInfo['id'],
                                    'price' => $planInfo['monthly_price'],
                                    'allowed_test' => $planInfo['allowed_test'],
                                    'next_payment_date' => NULL,
                                    'payment_reference' => NULL,
                                    'payment_receipt_id' => NULL,
                                );
                
                $where ='user_id =' . $user_id .'';
                $update = updateData('user_test_plan', $planData, $where);
                
                $planAmenityData = array(
                                    'availability' => 0,
                                    'date_modified' => $date
                                );
                $where ='user_id =' . $user_id .' AND test_plan_id > 0';
                updateData('user_amenities', $planAmenityData, $where);
                
                $transactionData = array(
                                'user_id' => $user_id,
                                'test_plan_id' => $plan_id,
                                'amount' => $price,
                                'transaction_date' => date('Y-m-d H:i:s')
                            );
            
                insertData('user_transaction', $transactionData);
                
                /** Update Ranking **/
                updatePlayerRanking($user_id, $planInfo['allowed_test']);
                /** Update Ranking **/
                
                generateLog($logFile, 'Plan Updated');
                
                $emailSubject = 'Subscription Plan Renewal Failed';
                $emailMessage = '<p>Hi ' . $user['first_name'] . ' ' . $user['last_name'] . '</p>';
                $emailMessage .= '<p>Your subscription plan renewal has been failed. So your plan has been changed to ' . $planInfo['title'] . ' plan. Please find the plan details below:</p>';
                $emailMessage .= '<p><b>Plan</b> : ' . $planInfo['title'] . ' - ' . $planInfo['sub_title'] . '</p>';
                $emailMessage .= '<p><b>Price</b> : FREE</p>';
                
                $msgdata = array(
                    'to' => array($user['email']),
                    'subject' => $emailSubject,
                    'message' => $emailMessage
                );
                sendMsg($msgdata);
            }
            
            generateLog($logFile, "\n");
        }
    }
}

generateLog($logFile, "End : " . date('Y-m-d H:i:s'));
generateLog($logFile, "\n");
generateLog($logFile, "\n");